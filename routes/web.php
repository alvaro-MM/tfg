<?php

use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PublicMenuController;
use App\Http\Controllers\PublicOrderController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\DrinkController;
use App\Http\Controllers\AllergenController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public routes for QR menu access
Route::prefix('menu')->group(function () {
    Route::get('/{token}', [PublicMenuController::class, 'show'])->name('public.menu');
    Route::get('/{token}/data', [PublicMenuController::class, 'getMenuData'])->name('public.menu.data');
});


Route::prefix('order')->group(function () {
    Route::post('/{token}/send', [PublicOrderController::class, 'sendToKitchen'])->name('public.order.send');
    Route::get('/{token}/confirm/{orderId}', [PublicOrderController::class, 'confirm'])->name('public.order.confirm');
});

Route::prefix('checkout')->group(function () {
    Route::get('/{token}', [PublicOrderController::class, 'showPayment'])->name('public.payment');
    Route::post('/{token}', [PublicOrderController::class, 'checkout'])->name('public.checkout');
    Route::get('/{token}/status', [PublicOrderController::class, 'getBuffetStatus'])->name('public.buffet.status');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    Route::resource('categories', CategoryController::class);
    Route::resource('dishes', DishController::class);
    Route::resource('drinks', DrinkController::class);
    Route::resource('allergens', AllergenController::class);
    Route::resource('review', ReviewController::class);
    Route::resource('tables', TableController::class);
    Route::resource('menus', MenuController::class)->except(['show']);

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__ . '/auth.php';
