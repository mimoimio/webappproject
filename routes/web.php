<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        // Redirect based on user type
        if (auth()->user()->isVendor()) {
            return redirect()->route('vendor.orders.new');
        } else {
            return redirect()->route('customer.vendors');
        }
    })->name('dashboard');

    // Customer Routes
    Route::middleware(['customer'])->prefix('customer')->name('customer.')->group(function () {
        Route::get('/vendors', \App\Livewire\Customer\VendorList::class)->name('vendors');
        Route::get('/vendor/{vendorId}/menu', \App\Livewire\Customer\VendorMenu::class)->name('vendor.menu');
        Route::get('/checkout', \App\Livewire\Customer\Checkout::class)->name('checkout');
        Route::get('/orders', \App\Livewire\Customer\MyOrders::class)->name('orders');
    });

    // Vendor Routes
    Route::middleware(['vendor'])->prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/profile', \App\Livewire\Vendor\QRCodeUpload::class)->name('profile');
        Route::get('/menu', \App\Livewire\Vendor\MenuManager::class)->name('menu');
        Route::get('/orders/new', \App\Livewire\Vendor\NewOrders::class)->name('orders.new');
        Route::get('/orders/preparing', \App\Livewire\Vendor\PreparingOrders::class)->name('orders.preparing');
        Route::get('/orders/completed', \App\Livewire\Vendor\CompletedOrders::class)->name('orders.completed');
    });
});
