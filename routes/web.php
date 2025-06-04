<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TwoFactorAuthenticationController;
use App\Http\Controllers\AdminTwoFactorController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\UnauthorizedController;
use App\Http\Controllers\AdminApprovalController;

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

// Special logout page route
Route::get('/logout-page', function () {
    return view('auth.logout');
})->name('logout.page');

// Google Authentication Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Set Password for Google-authenticated accounts
Route::middleware(['auth'])->group(function () {
    Route::get('/set-password', [App\Http\Controllers\GoogleAccountPasswordController::class, 'showSetPasswordForm'])
        ->name('password.set.form');
    Route::post('/set-password', [App\Http\Controllers\GoogleAccountPasswordController::class, 'setPassword'])
        ->name('password.set');
    Route::post('/update-password', [App\Http\Controllers\GoogleAccountPasswordController::class, 'updatePassword'])
        ->name('password.update');
});

// Unauthorized access routes
Route::get('/unauthorized', [UnauthorizedController::class, 'index'])
    ->name('unauthorized.access');
Route::post('/request-access', [UnauthorizedController::class, 'requestAccess'])
    ->name('request.access');

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
        if ($user && $user->role && in_array($user->role->name, ['Barangay Captain', 'Barangay Secretary', 'Health Worker', 'Complaint Manager'])) {
            return redirect()->route('admin.dashboard');
        }
        
        // For non-admin users, show the unauthorized page
        return redirect()->route('unauthorized.access');
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
        Route::post('/admin/profile/update', [AdminProfileController::class, 'updateProfile'])
            ->name('admin.profile.update');
        Route::post('/admin/profile/photo/update', [AdminProfileController::class, 'updateProfilePhoto'])
            ->name('admin.profile.photo.update');
        Route::post('/admin/profile/photo/delete', [AdminProfileController::class, 'deleteProfilePhoto'])
            ->name('admin.profile.photo.delete');
        
        // Access Request Management Routes
        Route::get('/admin/access-requests', [App\Http\Controllers\Admin\AccessRequestController::class, 'index'])
            ->name('admin.access-requests.index');
        Route::get('/admin/access-requests/{accessRequest}', [App\Http\Controllers\Admin\AccessRequestController::class, 'show'])
            ->name('admin.access-requests.show');
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
        
        // Admin Approval Management Routes
        Route::get('/admin/approvals', [AdminApprovalController::class, 'index'])
            ->name('admin.approvals.index');
        Route::get('/admin/approvals/create', [AdminApprovalController::class, 'create'])
            ->name('admin.approvals.create');
        Route::post('/admin/approvals', [AdminApprovalController::class, 'store'])
            ->name('admin.approvals.store');
        Route::get('/admin/approvals/{approval}/edit', [AdminApprovalController::class, 'edit'])
            ->name('admin.approvals.edit');
        Route::put('/admin/approvals/{approval}', [AdminApprovalController::class, 'update'])
            ->name('admin.approvals.update');
        Route::delete('/admin/approvals/{approval}', [AdminApprovalController::class, 'destroy'])
            ->name('admin.approvals.destroy');
        Route::patch('/admin/approvals/{approval}/toggle', [AdminApprovalController::class, 'toggleStatus'])
            ->name('admin.approvals.toggle');
        Route::get('/admin/approvals/pending', [AdminApprovalController::class, 'pendingRequests'])
            ->name('admin.approvals.pending');

        Route::post('/admin/access-requests/{accessRequest}/approve', [App\Http\Controllers\Admin\AccessRequestController::class, 'approve'])
            ->name('admin.access-requests.approve');
        Route::post('/admin/access-requests/{accessRequest}/deny', [App\Http\Controllers\Admin\AccessRequestController::class, 'deny'])
            ->name('admin.access-requests.deny');
    });
});

// Temporary diagnostic route - remove after debugging
Route::get('/debug-access-requests', function () {
    try {
        $requests = \App\Models\AccessRequest::with(['user', 'role'])->get();
        
        echo "<h2>Access Requests Diagnostic</h2>";
        echo "<p>Total requests: " . $requests->count() . "</p>";
        
        foreach ($requests as $index => $request) {
            echo "<hr>";
            echo "<h3>Request #" . ($index + 1) . "</h3>";
            echo "<p><strong>ID:</strong> " . $request->id . "</p>";
            echo "<p><strong>Status:</strong> " . $request->status . "</p>";
            
            // Check user relationship
            if ($request->user) {
                echo "<p><strong>User:</strong> " . $request->user->name . " (" . $request->user->email . ")</p>";
            } else {
                echo "<p style='color:red'><strong>User:</strong> NULL - Missing user relationship</p>";
            }
            
            // Check role relationship
            if ($request->role) {
                echo "<p><strong>Role:</strong> " . $request->role->name . "</p>";
            } else {
                echo "<p style='color:red'><strong>Role:</strong> NULL - Missing role relationship</p>";
            }
            
            echo "<p><strong>Requested At:</strong> " . $request->requested_at . "</p>";
            echo "<p><strong>Reason:</strong> " . $request->reason . "</p>";
        }
        
        return '<style>body{font-family:sans-serif;padding:20px;}</style>';
    } catch (\Exception $e) {
        return "<h1>Error</h1><pre>" . $e->getMessage() . "\n" . $e->getTraceAsString() . "</pre>";
    }
})->middleware('auth');

