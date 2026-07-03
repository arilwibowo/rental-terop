<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $products = Product::with('category')
            ->whereIn('id', array_keys($cart))
            ->get();

        $stockErrors = $products->filter(function ($product) use ($cart) {
            return $product->stock < ($cart[$product->id] ?? 0);
        });

        return view('checkout.index', compact('cart', 'products', 'stockErrors'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (empty(session('cart', []))) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string'],
            'event_location' => ['required', 'string', 'max:255'],
            'rental_start_date' => ['required', 'date', 'after_or_equal:today'],
            'rental_end_date' => ['required', 'date', 'after_or_equal:rental_start_date'],
            'notes' => ['nullable', 'string'],
        ]);

        $cart = session('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        if ($products->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Produk di keranjang tidak tersedia.');
        }

        foreach ($cart as $productId => $qty) {
            $product = $products->get((int) $productId);

            if (! $product || ! $product->is_active || $product->stock < (int) $qty) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', 'Maaf, stok produk sedang habis atau tidak tersedia pada tanggal yang dipilih.');
            }
        }

        $startDate = Carbon::parse($validated['rental_start_date']);
        $endDate = Carbon::parse($validated['rental_end_date']);
        $rentalDays = max(1, $startDate->diffInDays($endDate));

        $booking = DB::transaction(function () use ($validated, $cart, $products, $rentalDays) {
            $firstProduct = $products->first();
            $totalQuantity = 0;
            $grandTotal = 0;

            foreach ($cart as $productId => $qty) {
                $product = $products->get((int) $productId);

                if (! $product) {
                    continue;
                }

                $quantity = (int) $qty;
                $totalQuantity += $quantity;
                $grandTotal += $product->price * $quantity * $rentalDays;
            }

            $booking = Booking::create([
                'product_id' => $firstProduct->id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'customer_name' => $validated['customer_name'],
                'phone_number' => $validated['phone_number'],
                'customer_email' => $validated['email'] ?? null,
                'address' => $validated['address'],
                'event_location' => $validated['event_location'],
                'notes' => $validated['notes'] ?? null,
                'event_date' => $validated['rental_start_date'],
                'rental_start_date' => $validated['rental_start_date'],
                'rental_end_date' => $validated['rental_end_date'],
                'rental_days' => $rentalDays,
                'quantity' => $totalQuantity,
                'unit_price' => $firstProduct->price,
                'total_price' => $grandTotal,
                'payment_status' => 'menunggu_pembayaran',
                'booking_status' => 'pending',
            ]);

            foreach ($cart as $productId => $qty) {
                $product = $products->get((int) $productId);

                if (! $product) {
                    continue;
                }

                $quantity = (int) $qty;

                $booking->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'rental_days' => $rentalDays,
                    'subtotal' => $product->price * $quantity * $rentalDays,
                ]);
            }

            return $booking;
        });

        session()->forget(['cart', 'checkout_customer']);

        return redirect()->route('bookings.invoice', $booking->invoice_number);
    }

    private function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . now()->format('Ymd') . '-';
        $latestBooking = Booking::whereDate('created_at', today())
            ->lockForUpdate()
            ->latest('id')
            ->first();
        $nextNumber = $latestBooking ? ((int) substr($latestBooking->invoice_number, -4)) + 1 : 1;

        return $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
