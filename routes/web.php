<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PDFController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AllergenController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\DrinkController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PublicMenuController;
use App\Http\Controllers\PublicOrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\StaffOrderController;
use App\Http\Controllers\Staff\StaffTableController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/platos', [PublicController::class, 'dishes'])->name('dishes.public');
Route::get('/bebidas', [PublicController::class, 'drinks'])->name('drinks.public');
Route::get('/precios', [PublicController::class, 'prices'])->name('prices');
Route::get('/sobre-nosotros', [PublicController::class, 'about'])->name('about');

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

Route::middleware(['auth'])->group(function () {

    Route::resource('categories', CategoryController::class);
    Route::resource('dishes', DishController::class)->except( 'store', 'update');
    Route::resource('drinks', DrinkController::class)->except( 'store', 'update');
    Route::resource('allergens', AllergenController::class);
    Route::resource('review', ReviewController::class)->except( 'store', 'update');
    Route::resource('tables', TableController::class);
    Route::post('tables/{table}/generate-qr', [TableController::class, 'generateQr'])->name('tables.generate-qr');
    Route::resource('menus', MenuController::class);
    Route::resource('offers', OfferController::class);
    Route::resource('invoices', InvoiceController::class);

    // DASHBOARD

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin-dashboard', [AdminDashboardController::class, 'index'])->name('admin-dashboard.index');
        Route::resource('admin-dashboard/users', UserController::class)->except(['show', 'create', 'store']);
        Route::get('/admin/facturacion', [AdminDashboardController::class, 'billing'])->name('admin.billing');
        Route::get('/performance', [AdminDashboardController::class, 'performance'])->name('performance');
        Route::get('/admin/pdf/daily-performance', [PDFController::class, 'dailyPerformance'])->name('admin.pdf.daily-performance');
        Route::post('/admin/pdf/save-chart', [PDFController::class, 'saveChartImage'])->name('admin.pdf.saveChart');
    });

    Route::middleware( 'role:owner')->group(function () {

        Route::get('/owner-dashboard', OwnerDashboardController::class)
            ->name('owner-dashboard.index');

        Route::post('/owner/users/{user}/make-staff',
            [\App\Http\Controllers\Owner\OwnerManagementController::class, 'makeStaff']
        )->name('owner.make-staff');

        Route::delete('/owner/users/{user}/remove-staff',
            [\App\Http\Controllers\Owner\OwnerManagementController::class, 'removeStaff']
        )->name('owner.remove-staff');

        Route::post('/owner/tables',
            [\App\Http\Controllers\Owner\OwnerManagementController::class, 'storeTable']
        )->name('owner.tables.store');

        Route::delete('/owner/tables/{table}',
            [\App\Http\Controllers\Owner\OwnerManagementController::class, 'destroyTable']
        )->name('owner.tables.destroy');

    });

    Route::middleware('role:staff')->group(function () {
        Route::get('/staff-dashboard', [StaffDashboardController::class, 'index'])
            ->name('staff-dashboard.index');

        Route::get('/staff-tables', [StaffTableController::class, 'index'])
            ->name('staff-tables.index');

        Route::get('/staff-tables/{table}', [StaffTableController::class, 'show'])
            ->name('staff-tables.show');

        Route::post('/staff-tables/{table}/occupy', [StaffTableController::class, 'occupy'])
            ->name('staff-tables.occupy');

        Route::post('/staff-tables/{table}/free', [StaffTableController::class, 'free'])
            ->name('staff-tables.free');

        Route::post('/tables/{table}/reserve', [StaffTableController::class, 'reserve'])
            ->name('staff-tables.reserve');

        Route::post('/tables/{table}/cancel-reserve', [StaffTableController::class, 'cancelReserve'])
            ->name('staff-tables.cancel-reserve');

        Route::get('/staff-orders', [StaffOrderController::class, 'index'])
            ->name('staff-orders.index');
    });

    // No hechos por nosotros

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
