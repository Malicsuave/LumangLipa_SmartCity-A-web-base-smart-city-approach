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

// Document Request Public Routes
Route::get('/request-document', [App\Http\Controllers\DocumentRequestController::class, 'create'])->name('documents.request');
Route::post('/request-document', [App\Http\Controllers\DocumentRequestController::class, 'store'])->name('documents.store');
Route::post('/check-resident', [App\Http\Controllers\DocumentRequestController::class, 'checkResident'])->name('documents.check-resident');
Route::post('/send-otp', [App\Http\Controllers\DocumentRequestController::class, 'sendOtp'])->name('documents.send-otp');
Route::post('/verify-otp', [App\Http\Controllers\DocumentRequestController::class, 'verifyOtp'])->name('documents.verify-otp');

// Health Service Public Routes
Route::get('/health/request', [App\Http\Controllers\HealthServiceController::class, 'create'])->name('health.request');
Route::post('/health/request', [App\Http\Controllers\HealthServiceController::class, 'store'])->name('health.store');
Route::post('/health/check-resident', [App\Http\Controllers\HealthServiceController::class, 'checkResident'])->name('health.check-resident');
Route::post('/health/send-otp', [App\Http\Controllers\HealthServiceController::class, 'sendOtp'])->name('health.send-otp');
Route::post('/health/verify-otp', [App\Http\Controllers\HealthServiceController::class, 'verifyOtp'])->name('health.verify-otp');

// Complaint Public Routes
Route::get('/complaints/file', [App\Http\Controllers\ComplaintController::class, 'create'])->name('complaints.create');
Route::post('/complaints/file', [App\Http\Controllers\ComplaintController::class, 'store'])->name('complaints.store');
Route::post('/complaints/check-resident', [App\Http\Controllers\ComplaintController::class, 'checkResident'])->name('complaints.check-resident');
Route::post('/complaints/send-otp', [App\Http\Controllers\ComplaintController::class, 'sendOtp'])->name('complaints.send-otp');
Route::post('/complaints/verify-otp', [App\Http\Controllers\ComplaintController::class, 'verifyOtp'])->name('complaints.verify-otp');

// Resident ID full preview route
Route::get('/resident/{resident}/id/full-preview', [App\Http\Controllers\ResidentIdController::class, 'fullPreview'])->name('id.full-preview');

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
        Route::get('/admin/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Document Requests Management
        Route::get('/admin/documents', [App\Http\Controllers\DocumentRequestController::class, 'index'])->name('admin.documents');
        Route::get('/admin/documents/{documentRequest}', [App\Http\Controllers\DocumentRequestController::class, 'show']);
        Route::post('/admin/documents/{documentRequest}/approve', [App\Http\Controllers\DocumentRequestController::class, 'approve']);
        Route::post('/admin/documents/{documentRequest}/reject', [App\Http\Controllers\DocumentRequestController::class, 'reject']);
        Route::get('/admin/documents/{documentRequest}/view', [App\Http\Controllers\DocumentGeneratorController::class, 'generateDocument'])->name('admin.documents.view');
        Route::get('/admin/documents/{documentRequest}/print', [App\Http\Controllers\DocumentGeneratorController::class, 'generateDocument'])->name('admin.documents.print');
        
        // Resident Management Routes - Multi-step Form
        Route::prefix('admin/residents')->name('admin.residents.')->group(function () {
            Route::get('/', [App\Http\Controllers\ResidentController::class, 'index'])->name('index');
            Route::get('/archived', [App\Http\Controllers\ResidentController::class, 'archived'])->name('archived');
            Route::post('/restore/{id}', [App\Http\Controllers\ResidentController::class, 'restore'])->name('restore');
            Route::delete('/force-delete/{id}', [App\Http\Controllers\ResidentController::class, 'forceDelete'])->name('force-delete');
            Route::get('/create', [App\Http\Controllers\ResidentController::class, 'create'])->name('create');
            
            // Batch ID Management - MOVED BEFORE DYNAMIC ROUTES
            Route::get('/pending-ids', [App\Http\Controllers\ResidentIdController::class, 'pendingIds'])->name('id.pending');
            Route::post('/batch-issue', [App\Http\Controllers\ResidentIdController::class, 'batchIssue'])->name('id.batch-issue');
            // Bulk Upload Routes
            Route::get('/bulk-upload', [App\Http\Controllers\ResidentIdController::class, 'bulkUpload'])->name('id.bulk-upload');
            Route::post('/bulk-upload', [App\Http\Controllers\ResidentIdController::class, 'processBulkUpload'])->name('id.bulk-upload');
            Route::post('/bulk-signature-upload', [App\Http\Controllers\ResidentIdController::class, 'processBulkSignatureUpload'])->name('id.bulk-signature-upload');
            Route::post('/bulk-issue', [App\Http\Controllers\ResidentIdController::class, 'bulkIssue'])->name('id.bulk-issue');
            
            // Multi-step form routes
            Route::get('/create/step1', [App\Http\Controllers\ResidentController::class, 'createStep1'])->name('create.step1');
            Route::post('/create/step1', [App\Http\Controllers\ResidentController::class, 'storeStep1'])->name('create.step1.store');
            Route::get('/create/step2', [App\Http\Controllers\ResidentController::class, 'createStep2'])->name('create.step2');
            Route::post('/create/step2', [App\Http\Controllers\ResidentController::class, 'storeStep2'])->name('create.step2.store');
            Route::get('/create/step3', [App\Http\Controllers\ResidentController::class, 'createStep3'])->name('create.step3');
            Route::post('/create/step3', [App\Http\Controllers\ResidentController::class, 'storeStep3'])->name('create.step3.store');
            
            // Senior Citizen Step (step 4)
            Route::get('/create/step4-senior', [App\Http\Controllers\ResidentController::class, 'createStep4Senior'])->name('create.step4-senior');
            Route::post('/create/step4-senior', [App\Http\Controllers\ResidentController::class, 'storeStep4Senior'])->name('create.step4-senior.store');
            
            // Family Members (step 5 - was previously step 4)
            Route::get('/create/step4', [App\Http\Controllers\ResidentController::class, 'createStep4'])->name('create.step4');
            Route::post('/create/step4', [App\Http\Controllers\ResidentController::class, 'storeStep4'])->name('create.step4.store');
            
            // Additional Info (step 6 - was previously step 5)
            Route::get('/create/step5', [App\Http\Controllers\ResidentController::class, 'createStep5'])->name('create.step5');
            Route::post('/create/step5', [App\Http\Controllers\ResidentController::class, 'storeStep5'])->name('create.step5.store');
            
            // Final Review (step 7 - was previously step 6)
            Route::get('/create/review', [App\Http\Controllers\ResidentController::class, 'createReview'])->name('create.review');
            Route::post('/store', [App\Http\Controllers\ResidentController::class, 'store'])->name('store');
            
            // DYNAMIC ROUTES - NOW AFTER STATIC ROUTES
            Route::get('/{resident}', [App\Http\Controllers\ResidentController::class, 'show'])->name('show');
            Route::get('/{resident}/edit', [App\Http\Controllers\ResidentController::class, 'edit'])->name('edit');
            Route::put('/{resident}', [App\Http\Controllers\ResidentController::class, 'update'])->name('update');
            Route::delete('/{resident}', [App\Http\Controllers\ResidentController::class, 'destroy'])->name('destroy');
            
            // Services route for residents
            Route::get('/{resident}/services', [App\Http\Controllers\ResidentController::class, 'services'])->name('services');
            
            // ID Card Management Routes
            Route::get('/{resident}/id', [App\Http\Controllers\ResidentIdController::class, 'show'])->name('id.show');
            Route::post('/{resident}/id/upload-photo', [App\Http\Controllers\ResidentIdController::class, 'uploadPhoto'])->name('id.upload-photo');
            Route::post('/{resident}/id/upload-signature', [App\Http\Controllers\ResidentIdController::class, 'uploadSignature'])->name('id.upload-signature');
            Route::put('/{resident}/id/update', [App\Http\Controllers\ResidentIdController::class, 'updateIdInfo'])->name('id.update');
            Route::post('/{resident}/id/issue', [App\Http\Controllers\ResidentIdController::class, 'issueId'])->name('id.issue');
            Route::post('/{resident}/id/revoke', [App\Http\Controllers\ResidentIdController::class, 'revokeId'])->name('id.revoke');
            Route::get('/{resident}/id/preview', [App\Http\Controllers\ResidentIdController::class, 'previewId'])->name('id.preview');
            Route::get('/{resident}/id/download', [App\Http\Controllers\ResidentIdController::class, 'downloadId'])->name('id.download');
            Route::post('/{resident}/id/mark-renewal', [App\Http\Controllers\ResidentIdController::class, 'markForRenewal'])->name('id.mark-renewal');
            Route::post('/{resident}/id/remove-renewal', [App\Http\Controllers\ResidentIdController::class, 'removeFromRenewal'])->name('id.remove-renewal');
            Route::get('/{resident}/id/preview-data', [App\Http\Controllers\ResidentIdController::class, 'getIdPreviewData'])->name('id.preview-data');
            Route::get('/{resident}/id/full-preview', [App\Http\Controllers\ResidentIdController::class, 'fullPreview'])->name('id.full-preview'); // New route for full-page preview
        });
        
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
        Route::post('/admin/access-requests/{accessRequest}/approve', [App\Http\Controllers\Admin\AccessRequestController::class, 'approve'])
            ->name('admin.access-requests.approve');
        Route::post('/admin/access-requests/{accessRequest}/deny', [App\Http\Controllers\Admin\AccessRequestController::class, 'deny'])
            ->name('admin.access-requests.deny');
        
        // Population Duplicate Management Routes
        Route::get('/admin/population/duplicates', [App\Http\Controllers\PopulationController::class, 'duplicates'])->name('admin.population.duplicates');
        Route::post('/admin/population/merge-duplicate', [App\Http\Controllers\PopulationController::class, 'mergeDuplicate'])->name('admin.population.merge-duplicate');
        Route::delete('/admin/population/remove-duplicate/{type}/{id}', [App\Http\Controllers\PopulationController::class, 'removeDuplicate'])->name('admin.population.remove-duplicate');
    });
    
    // Health Worker routes
    Route::middleware('role:Health Worker,Barangay Captain,Barangay Secretary')->group(function () {
        Route::get('/admin/health', [App\Http\Controllers\HealthServiceController::class, 'adminDashboard'])->name('admin.health');
        
        // Health Service Management Routes
        Route::prefix('admin/health-services')->name('admin.health-services.')->group(function() {
            Route::get('/', [App\Http\Controllers\HealthServiceController::class, 'index'])->name('index');
            Route::get('/{id}', [App\Http\Controllers\HealthServiceController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [App\Http\Controllers\HealthServiceController::class, 'approve'])->name('approve');
            Route::post('/{id}/complete', [App\Http\Controllers\HealthServiceController::class, 'complete'])->name('complete');
            Route::post('/{id}/reject', [App\Http\Controllers\HealthServiceController::class, 'reject'])->name('reject');
        });
        
        // Health Meeting Management Routes
        Route::prefix('admin/health-meetings')->name('admin.health-meetings.')->group(function() {
            Route::post('/', [App\Http\Controllers\HealthMeetingController::class, 'store'])->name('store');
            Route::post('/{id}/complete', [App\Http\Controllers\HealthMeetingController::class, 'complete'])->name('complete');
            Route::post('/{id}/cancel', [App\Http\Controllers\HealthMeetingController::class, 'cancel'])->name('cancel');
        });
        
        // GAD (Gender and Development) Routes
        Route::prefix('admin/gad')->name('admin.gad.')->group(function() {
            Route::get('/', [App\Http\Controllers\Admin\GadController::class, 'index'])->name('index');
            Route::get('/archived', [App\Http\Controllers\Admin\GadController::class, 'archived'])->name('archived');
            Route::post('/restore/{id}', [App\Http\Controllers\Admin\GadController::class, 'restore'])->name('restore');
            Route::delete('/force-delete/{id}', [App\Http\Controllers\Admin\GadController::class, 'forceDelete'])->name('force-delete');
            Route::get('/create', [App\Http\Controllers\Admin\GadController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\GadController::class, 'store'])->name('store');
            Route::get('/{gad}', [App\Http\Controllers\Admin\GadController::class, 'show'])->name('show');
            Route::get('/{gad}/edit', [App\Http\Controllers\Admin\GadController::class, 'edit'])->name('edit');
            Route::put('/{gad}', [App\Http\Controllers\Admin\GadController::class, 'update'])->name('update');
            Route::delete('/{gad}', [App\Http\Controllers\Admin\GadController::class, 'destroy'])->name('destroy');
            Route::get('/reports/generate', [App\Http\Controllers\Admin\GadController::class, 'reports'])->name('reports');
        });
        
        // Senior Citizens Management Routes
        Route::prefix('admin/senior-citizens')->name('admin.senior-citizens.')->group(function() {
            Route::get('/', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'index'])->name('index');
            Route::get('/dashboard', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'dashboard'])->name('dashboard');
            Route::get('/{seniorCitizen}/edit', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'edit'])->name('edit');
            Route::put('/{seniorCitizen}', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'update'])->name('update');
            Route::post('/{seniorCitizen}/issue-id', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'issueId'])->name('issue-id');
            Route::post('/{seniorCitizen}/mark-renewal', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'markForRenewal'])->name('mark-renewal');
            Route::post('/{seniorCitizen}/revoke-id', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'revokeId'])->name('revoke-id');
            
            // Senior Citizen ID Preview and Download Routes
            Route::get('/{seniorCitizen}/id/preview', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'previewId'])->name('id.preview');
            Route::get('/{seniorCitizen}/id/download', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'downloadId'])->name('id.download');
            
            // Senior Citizen Photo and Signature Management
            Route::get('/{seniorCitizen}/id-management', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'showIdManagement'])->name('id-management');
            Route::post('/{seniorCitizen}/upload-photo', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'uploadPhoto'])->name('upload-photo');
            Route::post('/{seniorCitizen}/upload-signature', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'uploadSignature'])->name('upload-signature');
            Route::put('/{seniorCitizen}/update-id-info', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'updateIdInfo'])->name('update-id-info');
        });
    });

    // User Activity & Security routes
    // Admin User Activity Management
    Route::middleware('role:Barangay Captain,Barangay Secretary,Health Worker,Complaint Manager')->group(function () {
        Route::get('/admin/activities', [App\Http\Controllers\UserActivityController::class, 'adminIndex'])
            ->name('admin.activities');
            
        // User Activity and Security Dashboard Routes
        Route::get('/admin/security/dashboard', [App\Http\Controllers\Admin\UserActivityController::class, 'dashboard'])
            ->name('admin.security.dashboard');
        Route::get('/admin/security/activities', [App\Http\Controllers\Admin\UserActivityController::class, 'index'])
            ->name('admin.security.activities');
        Route::get('/admin/security/activities/{id}', [App\Http\Controllers\Admin\UserActivityController::class, 'show'])
            ->name('admin.security.activities.show');
    });

    // Complaint Manager routes
    Route::middleware('role:Complaint Manager,Barangay Captain,Barangay Secretary')->group(function () {
        Route::get('/admin/complaints', [App\Http\Controllers\ComplaintController::class, 'adminDashboard'])->name('admin.complaints');
        
        // Complaint Management Routes
        Route::prefix('admin/complaints')->name('admin.')->group(function() {
            Route::get('/management', [App\Http\Controllers\ComplaintController::class, 'index'])->name('complaint-management');
            Route::get('/{id}', [App\Http\Controllers\ComplaintController::class, 'show'])->name('complaints.show');
            Route::post('/{id}/approve', [App\Http\Controllers\ComplaintController::class, 'approve'])->name('complaints.approve');
            Route::post('/{id}/resolve', [App\Http\Controllers\ComplaintController::class, 'resolve'])->name('complaints.resolve');
            Route::post('/{id}/dismiss', [App\Http\Controllers\ComplaintController::class, 'dismiss'])->name('complaints.dismiss');
        });
        
        // Complaint Meeting Management Routes
        Route::prefix('admin/complaint-meetings')->name('admin.complaint-meetings.')->group(function() {
            Route::post('/', [App\Http\Controllers\ComplaintMeetingController::class, 'store'])->name('store');
            Route::post('/{id}/complete', [App\Http\Controllers\ComplaintMeetingController::class, 'complete'])->name('complete');
            Route::post('/{id}/cancel', [App\Http\Controllers\ComplaintMeetingController::class, 'cancel'])->name('cancel');
        });
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
    });
});

// Test routes for debugging
Route::prefix('test')->group(function () {
    Route::get('household-update/{resident_id}', [App\Http\Controllers\TestController::class, 'testHouseholdUpdate']);
    Route::get('database-structure', [App\Http\Controllers\TestController::class, 'showDatabaseStructure']);
    Route::get('household-data/{resident_id}', [App\Http\Controllers\TestController::class, 'getHouseholdData']);
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


