<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session('cart', []);
        $products = Product::with(['category', 'images'])
            ->whereIn('id', array_keys($cart))
            ->get();

        return view('cart.index', compact('cart', 'products'));
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        if ($product->stock < 1) {
            return back()->with('error', 'Maaf, stok produk sedang habis atau tidak tersedia pada tanggal yang dipilih.');
        }

        $validated = $request->validate([
            'qty' => ['nullable', 'integer', 'min:1'],
        ]);

        $qty = (int) ($validated['qty'] ?? 1);
        $cart = session('cart', []);
        $cart[$product->id] = min($product->stock, ($cart[$product->id] ?? 0) + $qty);

        session(['cart' => $cart]);

        return back()->with('success', $product->name . ' berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'qty' => ['required', 'array'],
            'qty.*' => ['required', 'integer', 'min:1'],
        ]);

        $cart = [];
        $products = Product::whereIn('id', array_keys($validated['qty']))
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        foreach ($validated['qty'] as $productId => $qty) {
            $product = $products->get((int) $productId);

            if ($product && $product->stock > 0) {
                $cart[(int) $productId] = min((int) $qty, $product->stock);
            }
        }

        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diperbarui.',
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove(Product $product): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);

        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
