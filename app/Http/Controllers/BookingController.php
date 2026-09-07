<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\WebsiteSetting;
use App\Support\PublicImageStorage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    public function create(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load('images');

        return view('bookings.create', compact('product'));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'quantity' => ['required', 'integer', 'min:1', 'max:' . max($product->stock, 1)],
        ]);

        $booking = DB::transaction(function () use ($validated, $product) {
            $quantity = (int) $validated['quantity'];
            $unitPrice = $product->price;
            $totalPrice = $unitPrice * $quantity;

            return Booking::create([
                'product_id' => $product->id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'customer_name' => $validated['customer_name'],
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'],
                'event_date' => $validated['event_date'],
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'payment_status' => 'belum_bayar',
                'booking_status' => 'pending',
            ]);
        });

        return redirect()->route('bookings.invoice', $booking->invoice_number);
    }

    public function invoice(string $invoiceNumber): View
    {
        $booking = Booking::with(['product.category', 'items.product.category'])
            ->where('invoice_number', $invoiceNumber)
            ->firstOrFail();
        $paymentMethods = PaymentMethod::where('is_active', true)->latest()->get();

        return view('bookings.invoice', compact('booking', 'paymentMethods'));
    }

    public function uploadPaymentProof(Request $request, string $invoiceNumber): RedirectResponse
    {
        $booking = Booking::where('invoice_number', $invoiceNumber)->firstOrFail();

        if (in_array($booking->payment_status, ['sudah_bayar', 'lunas'], true)) {
            return back()->with('success', 'Pembayaran sudah diverifikasi admin.');
        }

        $validated = $request->validate([
            'payment_proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($booking->payment_proof) {
            PublicImageStorage::delete($booking->payment_proof);
        }

        $path = PublicImageStorage::store($validated['payment_proof'], 'payment-proofs');

        $booking->update([
            'payment_proof' => $path,
            'payment_status' => 'menunggu_verifikasi',
            'payment_rejection_reason' => null,
        ]);

        return redirect()->route('bookings.payment-success', $booking->invoice_number);
    }

    public function paymentSuccess(string $invoiceNumber): View
    {
        $booking = Booking::where('invoice_number', $invoiceNumber)->firstOrFail();
        $setting = WebsiteSetting::current();

        return view('payment_success.show', compact('booking', 'setting'));
    }

    public function downloadInvoicePdf(string $invoiceNumber): Response
    {
        $booking = Booking::with(['product.category', 'items.product.category'])
            ->where('invoice_number', $invoiceNumber)
            ->firstOrFail();
        $paymentMethods = PaymentMethod::where('is_active', true)->latest()->get();
        $pdf = Pdf::loadView('admin.bookings.invoice_pdf', compact('booking', 'paymentMethods'))->setPaper('a4');

        return $pdf->download('invoice-' . $booking->invoice_number . '.pdf');
    }

    public function orderHistory(Request $request): View
    {
        $bookings = collect();
        $notFound = false;
        $setting = WebsiteSetting::current();

        if ($request->filled('invoice')) {
            $bookings = Booking::query()
                ->where('invoice_number', $request->invoice)
                ->latest()
                ->get();

            $notFound = $bookings->isEmpty();
        }

        return view('orders.history', compact('bookings', 'notFound', 'setting'));
    }

    private function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . now()->format('Ymd') . '-';
        $latestBooking = Booking::whereDate('created_at', today())
            ->lockForUpdate()
            ->latest('id')
            ->first();

        $nextNumber = $latestBooking
            ? ((int) substr($latestBooking->invoice_number, -4)) + 1
            : 1;

        return $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
