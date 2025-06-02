<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TwoFactorAuthenticationController;
use App\Http\Controllers\AdminTwoFactorController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\Auth\GoogleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', [PublicController::class, 'home'])->name('public.home');
Route::get('/about', [PublicController::class, 'about'])->name('public.about');
Route::get('/services', [PublicController::class, 'services'])->name('public.services');
Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');

// Google Authentication Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Define a completely separate admin 2FA route
Route::post('/admin/two-factor-enable', [AdminTwoFactorController::class, 'enable'])
    ->middleware(['web', 'auth', 'role:Barangay Captain,Barangay Secretary'])
    ->name('admin.two-factor.enable');

// Keep the original routes as they are
Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store'])
    ->middleware(['web', 'auth'])
    ->name('two-factor.enable');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Override the default Jetstream profile route to redirect to admin profile
    Route::get('/user/profile', function () {
        $user = auth()->user();
        
        // Check if user has admin roles
        if ($user->role && in_array($user->role->name, ['Barangay Captain', 'Barangay Secretary'])) {
            return redirect()->route('admin.profile');
        }
        
        // For non-admin users, redirect to dashboard or show error
        return redirect()->route('dashboard')->with('error', 'Profile access not available for your role.');
    })->name('profile.show');

    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        // Check if user has admin roles and redirect to admin dashboard
        if ($user && $user->role && in_array($user->role->name, ['Barangay Captain', 'Barangay Secretary'])) {
            return redirect()->route('admin.dashboard');
        }
        
        // For non-admin users, show the regular dashboard
        return view('dashboard');
    })->name('dashboard');

    // Admin-only routes (Barangay Captain, Secretary)
    Route::middleware('role:Barangay Captain,Barangay Secretary')->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        Route::get('/admin/documents', function () {
            return view('admin.documents');
        })->name('admin.documents');
        Route::get('/admin/residents', function () {
            return view('admin.residents');
        })->name('admin.residents');
        Route::get('/admin/profile', function () {
            return view('admin.profile');
        })->name('admin.profile');
        
        // Profile management routes
        Route::post('/admin/profile', [AdminProfileController::class, 'updateProfile'])->name('admin.profile.update');
        Route::post('/admin/profile/photo', [AdminProfileController::class, 'updateProfilePhoto'])->name('admin.profile.photo.update');
        Route::delete('/admin/profile/photo', [AdminProfileController::class, 'deleteProfilePhoto'])->name('admin.profile.photo.delete');
        Route::get('/admin/profile/debug', [AdminProfileController::class, 'debugProfile'])->name('admin.profile.debug');
    });

    // Health Worker routes
    Route::middleware('role:Health Worker,Barangay Captain,Barangay Secretary')->group(function () {
        Route::get('/admin/health', function () {
            return view('admin.health');
        })->name('admin.health');
    });

    // Complaint Manager routes
    Route::middleware('role:Complaint Manager,Barangay Captain,Barangay Secretary')->group(function () {
        Route::get('/admin/complaints', function () {
            return view('admin.complaints');
        })->name('admin.complaints');
    });

    // Super Admin (Barangay Captain) exclusive routes
    Route::middleware('role:Barangay Captain')->group(function () {
        Route::get('/admin/analytics', function () {
            return view('admin.analytics');
        })->name('admin.analytics');
    });
});

