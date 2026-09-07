<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Support\PublicImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentMethodController extends Controller
{
    public function index(): View
    {
        $paymentMethods = PaymentMethod::latest()->paginate(10);

        return view('admin.payment_methods.index', compact('paymentMethods'));
    }

    public function create(): View
    {
        return view('admin.payment_methods.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bank_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:255'],
            'account_holder' => ['required', 'string', 'max:255'],
            'qris_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $qrisPath = null;

        if ($request->hasFile('qris_image')) {
            $qrisPath = PublicImageStorage::store($request->file('qris_image'), 'qris');
        }

        PaymentMethod::create([
            'bank_name' => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'account_holder' => $validated['account_holder'],
            'qris_image' => $qrisPath,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function edit(PaymentMethod $paymentMethod): View
    {
        return view('admin.payment_methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        $validated = $request->validate([
            'bank_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:255'],
            'account_holder' => ['required', 'string', 'max:255'],
            'qris_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $qrisPath = $paymentMethod->qris_image;

        if ($request->hasFile('qris_image')) {
            if ($paymentMethod->qris_image) {
                PublicImageStorage::delete($paymentMethod->qris_image);
            }

            $qrisPath = PublicImageStorage::store($request->file('qris_image'), 'qris');
        }

        $paymentMethod->update([
            'bank_name' => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'account_holder' => $validated['account_holder'],
            'qris_image' => $qrisPath,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    public function destroy(PaymentMethod $paymentMethod): RedirectResponse
    {
        if ($paymentMethod->qris_image) {
            PublicImageStorage::delete($paymentMethod->qris_image);
        }

        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')->with('success', 'Metode pembayaran berhasil dihapus.');
    }
}
