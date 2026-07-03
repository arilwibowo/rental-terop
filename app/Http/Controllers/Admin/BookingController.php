<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentMethod;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    private const PAID_STATUSES = ['sudah_bayar', 'lunas'];

    public function index(Request $request): View
    {
        $bookings = Booking::with(['product.category', 'items.product.category'])
            ->when($request->filled('invoice'), fn ($query) => $query->where('invoice_number', 'like', '%' . $request->invoice . '%'))
            ->when($request->filled('status'), fn ($query) => $query->where('payment_status', $request->status))
            ->orderByRaw('COALESCE(rental_start_date, event_date) ASC')
            ->orderByRaw('COALESCE(rental_end_date, event_date) ASC')
            ->paginate(10)
            ->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        $booking->load(['product.category', 'items.product.category']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking): View
    {
        $booking->load(['product.category', 'items.product.category']);

        return view('admin.bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:30'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string'],
            'event_location' => ['nullable', 'string', 'max:255'],
            'rental_start_date' => ['required', 'date'],
            'rental_end_date' => ['required', 'date', 'after_or_equal:rental_start_date'],
            'payment_status' => ['required', Rule::in([
                'belum_bayar',
                'menunggu_pembayaran',
                'menunggu_verifikasi',
                'sudah_bayar',
                'lunas',
                'ditolak',
            ])],
            'booking_status' => ['required', Rule::in([
                'pending',
                'confirmed',
                'diproses',
                'selesai',
                'dibatalkan',
            ])],
            'notes' => ['nullable', 'string'],
        ]);

        $startDate = Carbon::parse($validated['rental_start_date']);
        $endDate = Carbon::parse($validated['rental_end_date']);
        $rentalDays = max(1, $startDate->diffInDays($endDate));

        DB::transaction(function () use ($booking, $validated, $rentalDays) {
            $booking->load('items');

            $grandTotal = 0;

            foreach ($booking->items as $item) {
                $subtotal = $item->unit_price * $item->quantity * $rentalDays;
                $grandTotal += $subtotal;

                $item->update([
                    'rental_days' => $rentalDays,
                    'subtotal' => $subtotal,
                ]);
            }

            if ($booking->items->isEmpty()) {
                $grandTotal = $booking->unit_price * $booking->quantity * $rentalDays;
            }

            $booking->update([
                'customer_name' => $validated['customer_name'],
                'phone_number' => $validated['phone_number'],
                'customer_email' => $validated['customer_email'] ?? null,
                'address' => $validated['address'],
                'event_location' => $validated['event_location'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'event_date' => $validated['rental_start_date'],
                'rental_start_date' => $validated['rental_start_date'],
                'rental_end_date' => $validated['rental_end_date'],
                'rental_days' => $rentalDays,
                'total_price' => $grandTotal,
                'payment_status' => $validated['payment_status'],
                'booking_status' => $validated['booking_status'],
            ]);
        });

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Booking berhasil diperbarui.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil dihapus.');
    }

    public function invoicePdf(Booking $booking): Response
    {
        $booking->load(['product.category', 'items.product.category']);
        $paymentMethods = PaymentMethod::where('is_active', true)->latest()->get();
        $pdf = Pdf::loadView('admin.bookings.invoice_pdf', compact('booking', 'paymentMethods'))->setPaper('a4');

        return $pdf->download('invoice-' . $booking->invoice_number . '.pdf');
    }

    public function approve(Booking $booking): RedirectResponse
    {
        DB::transaction(function () use ($booking) {
            $booking->load('items');

            if (in_array($booking->payment_status, self::PAID_STATUSES, true)) {
                return;
            }

            if ($booking->items->isNotEmpty()) {
                $items = $booking->items;
                $products = Product::whereIn('id', $items->pluck('product_id'))
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($items as $item) {
                    $product = $products->get($item->product_id);

                    if (! $product || $product->stock < $item->quantity) {
                        abort(422, 'Stok produk tidak mencukupi untuk menyetujui booking ini.');
                    }
                }

                foreach ($items as $item) {
                    $products->get($item->product_id)->decrement('stock', $item->quantity);
                }
            } else {
                $product = $booking->product()->lockForUpdate()->first();

                if (! $product || $product->stock < $booking->quantity) {
                    abort(422, 'Stok produk tidak mencukupi untuk menyetujui booking ini.');
                }

                $product->decrement('stock', $booking->quantity);
            }

            $booking->update([
                'payment_status' => 'lunas',
                'booking_status' => 'confirmed',
                'payment_verified_at' => now(),
                'payment_rejection_reason' => null,
            ]);
        });

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', 'Pembayaran disetujui dan stok produk berhasil dikurangi.');
    }

    public function reject(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'payment_rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        if (in_array($booking->payment_status, self::PAID_STATUSES, true)) {
            return back()->with('error', 'Pembayaran yang sudah disetujui tidak bisa ditolak.');
        }

        $booking->update([
            'payment_status' => 'ditolak',
            'booking_status' => 'pending',
            'payment_verified_at' => null,
            'payment_rejection_reason' => $validated['payment_rejection_reason'] ?? 'Bukti pembayaran ditolak admin.',
        ]);

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', 'Bukti pembayaran berhasil ditolak.');
    }
}
