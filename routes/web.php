<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Socialite Authentication
Route::get('/auth/google', [AuthController::class, 'googlelogin'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'googleAuthentication'])->name('auth.google.callback');

Route::middleware('guest')->group(function () {
// Manual Authentication Routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginSubmit'])->name('login');
});
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Routes
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/donor/dashboard', [DonorController::class, 'dashboard'])->name('donor.dashboard');
Route::get('/hospital/dashboard', [HospitalController::class, 'dashboard'])->name('hospital.dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin protected roles resource
Route::middleware(['auth'])->group(function () {
    Route::resource('roles', RoleController::class);

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    });
});

 require __DIR__.'/auth.php';
