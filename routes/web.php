<?php

use App\Livewire\Auth\Login as LoginPage;
use App\Livewire\Auth\Register as RegisterPage;
use App\Livewire\Products\Create as ProductCreate;
use App\Livewire\Products\Edit as ProductEdit;
use App\Livewire\Products\Index as ProductIndex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Cart\Index as CartPage;
use App\Livewire\Home\Index as HomePage;
use App\Livewire\Checkout\Index as CheckoutPage;
use App\Livewire\Orders\Index as OrdersPage;


// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', LoginPage::class)
        ->name('login');

    Route::get('/register', RegisterPage::class)
        ->name('register');
});

// Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');

// Administrator product management
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/products', ProductIndex::class)
            ->name('products.index');

        Route::get('/products/create', ProductCreate::class)
            ->name('products.create');

        Route::get('/products/{product}/edit', ProductEdit::class)
            ->name('products.edit');

        Route::get('/orders', OrdersPage::class)
            ->name('orders.index');
    });

// Temporary home page until we build the shop landing page
Route::get('/', HomePage::class)->name('home');

// Temporary cart page until we build the cart functionality
Route::get('/cart', CartPage::class)->name('cart');

// Checkout page
Route::get('/checkout', CheckoutPage::class)
    ->middleware('auth')
    ->name('checkout');
