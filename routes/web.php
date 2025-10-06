<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Models\Reservation;
use App\Http\Controllers\MypageController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');

    // Reservationに関するCRUD処理のルート
    Route::get('/reservations/complete', [ReservationController::class, 'complete'])->name('reservations.complete');
    Route::post('/reservations/confirm-date', [ReservationController::class, 'confirmDate'])->name('reservations.confirm-date');
    Route::get('/reservations/manage', [ReservationController::class, 'manage'])->name('reservations.manage');
    Route::get('/reservations/purpose', [ReservationController::class, 'purpose'])->name('reservations.purpose');
    Route::delete('/reservations/bulk-delete', [ReservationController::class, 'bulkDelete'])->name('reservations.bulk-delete');
    Route::resource('reservations', ReservationController::class);
});

require __DIR__.'/auth.php';
