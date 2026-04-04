<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\TableController as AdminTableController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'home'])->name('home');

Route::get('/menu', [MenuController::class, 'publicIndex'])->name('menu.index');

Route::get('/dashboard', [PublicPageController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/commander', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/commander', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/mes-commandes', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/mes-commandes/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/mes-commandes/{order}/annuler', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('/reserver', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reserver', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/mes-reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/mes-reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::patch('/mes-reservations/{reservation}/annuler', [ReservationController::class, 'cancel'])->name('reservations.cancel');

    // Dashboard admin
    Route::get('/admin', [DashboardController::class, 'index'])->middleware('isAdmin')->name('admin.dashboard');
    Route::get('/admin/export-pdf', [DashboardController::class, 'exportPdf'])->middleware('isAdmin')->name('admin.dashboard.export-pdf');

    // Gestion des utilisateurs (admin)
    Route::middleware('isAdmin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->except(['create']);
        Route::resource('menus', MenuController::class);
        Route::resource('sales', SaleController::class);
        Route::resource('tables', AdminTableController::class)->except(['show']);
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
        Route::get('reservations/{reservation}', [AdminReservationController::class, 'show'])->name('reservations.show');
        Route::patch('reservations/{reservation}/status', [AdminReservationController::class, 'updateStatus'])->name('reservations.update-status');
    });
});

require __DIR__.'/auth.php';