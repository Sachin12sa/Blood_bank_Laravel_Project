<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminHospitalController;
use App\Http\Controllers\Admin\AdminDonorController;
use App\Http\Controllers\Admin\AdminBloodRequestController;
use App\Http\Controllers\Admin\BloodUnitController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\AdminDonationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminBloodInventoryController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicController;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::post('/search-blood', [PublicController::class, 'searchBlood'])->name('search.blood');

// Socialite Authentication
Route::get('/auth/google', [AuthController::class, 'googlelogin'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'googleAuthentication'])->name('auth.google.callback');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginSubmit'])->name('login.submit');
});
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Generic dashboard — redirects to role-specific dashboard
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('admin'))    return redirect()->route('admin.dashboard');
    if ($user->hasRole('hospital')) return redirect()->route('hospital.dashboard');
    return redirect()->route('donor.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ── ADMIN ROUTES ────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('donations', AdminDonationController::class)->only(['index']);
    Route::patch('donations/{donation}/approve', [AdminDonationController::class, 'approve'])->name('donations.approve');
    Route::patch('donations/{donation}/reject', [AdminDonationController::class, 'reject'])->name('donations.reject');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('blood-units', BloodUnitController::class);

    // Hospitals management
    Route::get('/hospitals', [AdminHospitalController::class, 'index'])->name('hospitals.index');
    Route::patch('/hospitals/{hospital}/approve', [AdminHospitalController::class, 'approve'])->name('hospitals.approve');
    Route::patch('/hospitals/{hospital}/reject', [AdminHospitalController::class, 'reject'])->name('hospitals.reject');

    // Donors management
    Route::get('/donors', [AdminDonorController::class, 'index'])->name('donors.index');
    Route::get('/donors/{donor}', [AdminDonorController::class, 'show'])->name('donors.show');

    // Blood requests management
    Route::get('/blood-requests', [AdminBloodRequestController::class, 'index'])->name('blood-requests.index');
    Route::get('/blood-requests/{bloodRequest}', [AdminBloodRequestController::class, 'show'])->name('blood-requests.show');
    Route::patch('/blood-requests/{bloodRequest}/approve', [AdminBloodRequestController::class, 'approve'])->name('blood-requests.approve');
    Route::patch('/blood-requests/{bloodRequest}/dispatch', [AdminBloodRequestController::class, 'dispatch'])->name('blood-requests.dispatch');
    Route::patch('/blood-requests/{bloodRequest}/reject', [AdminBloodRequestController::class, 'reject'])->name('blood-requests.reject');

    // Reports
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');
    
    // Campaigns
    Route::resource('campaigns', CampaignController::class);
    
    // Blood Inventory
    Route::get('/inventories', [AdminBloodInventoryController::class, 'index'])->name('inventories.index');
    Route::post('/inventories/refresh', [AdminBloodInventoryController::class, 'refreshInventory'])->name('inventories.refresh');
});

// Roles (admin only)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('roles', RoleController::class);
});

// ── DONOR ROUTES ────────────────────────────────────────
Route::middleware(['auth', 'role:donor'])->prefix('donor')->name('donor.')->group(function () {
    Route::get('/dashboard', [DonorController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [DonorController::class, 'profile'])->name('profile');
    Route::put('/profile', [DonorController::class, 'updateProfile'])->name('profile.update');
    Route::post('/donate', [DonorController::class, 'donate'])->name('donate');
    Route::get('/history', [DonorController::class, 'history'])->name('history');
    Route::get('/certificates', [DonorController::class, 'certificates'])->name('certificates');
    Route::get('/certificate/{donation}', [DonorController::class, 'downloadCertificate'])->name('certificate.download');
});

// ── HOSPITAL ROUTES ─────────────────────────────────────
Route::middleware(['auth', 'role:hospital'])->prefix('hospital')->name('hospital.')->group(function () {
    Route::get('/dashboard', [HospitalController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [HospitalController::class, 'profile'])->name('profile');
    Route::put('/profile', [HospitalController::class, 'updateProfile'])->name('profile.update');
    Route::get('/requests', [HospitalController::class, 'myRequests'])->name('requests.index');
    Route::get('/requests/create', [HospitalController::class, 'createRequest'])->name('requests.create');
    Route::post('/requests', [HospitalController::class, 'storeRequest'])->name('requests.store');
    Route::get('/requests/{bloodRequest}', [HospitalController::class, 'showRequest'])->name('requests.show');
    Route::patch('/requests/{bloodRequest}/received', [HospitalController::class, 'updateStatus'])->name('requests.mark-received');
});

// ── PROFILE ROUTES (all authenticated) ──────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
