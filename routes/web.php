<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TwoFactorAuthenticationController;
use App\Http\Controllers\AdminTwoFactorController;

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

Route::get('/', function () {
    return view('welcome');
});

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
    Route::get('/dashboard', function () {
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
        Route::get('/admin/profile', function () {
            return view('admin.profile');
        })->name('admin.profile');
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

