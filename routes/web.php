<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [HomeController::class, 'products'])->name('products');
Route::get('/booking/{product:slug}', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/booking/{product:slug}', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/invoice/{invoiceNumber}', [BookingController::class, 'invoice'])->name('bookings.invoice');
Route::get('/invoice/{invoiceNumber}/download', [BookingController::class, 'downloadInvoicePdf'])->name('bookings.invoice-download');
Route::post('/invoice/{invoiceNumber}/payment-proof', [BookingController::class, 'uploadPaymentProof'])->name('bookings.payment-proof');
Route::get('/payment-success/{invoiceNumber}', [BookingController::class, 'paymentSuccess'])->name('bookings.payment-success');
Route::get('/riwayat-pesanan', [BookingController::class, 'orderHistory'])->name('orders.history');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product:slug}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{product:slug}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.process');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);
    Route::patch('/products/images/{productImage}', [ProductController::class, 'replaceImage'])->name('products.images.replace');
    Route::delete('/products/images/{productImage}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::resource('payment-methods', PaymentMethodController::class)->except(['show']);
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [AdminBookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [AdminBookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/bookings/{booking}/invoice-pdf', [AdminBookingController::class, 'invoicePdf'])->name('bookings.invoice-pdf');
    Route::patch('/bookings/{booking}/approve', [AdminBookingController::class, 'approve'])->name('bookings.approve');
    Route::patch('/bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
    Route::get('/settings', [WebsiteSettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [WebsiteSettingController::class, 'update'])->name('settings.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
