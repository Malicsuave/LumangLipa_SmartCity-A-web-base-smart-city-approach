<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TwoFactorAuthenticationController;
use App\Http\Controllers\AdminTwoFactorController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\UnauthorizedController;
use App\Http\Controllers\AdminApprovalController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\ResidentIdController;
use App\Http\Controllers\Admin\AccessRequestController;
use App\Http\Controllers\Admin\SeniorCitizenController;
use App\Http\Controllers\HealthServiceController;
use App\Http\Controllers\HealthMeetingController;
use App\Http\Controllers\Admin\GadController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\Admin\AgentConversationController as AdminAgentConversationController;
use App\Http\Controllers\AgentConversationController as UserAgentConversationController;
use App\Http\Controllers\Admin\UserActivityController as AdminUserActivityController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\TestController;

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



// Officials Management Routes
Route::middleware(['role:Barangay Captain,Barangay Secretary'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('officials/edit-single', [App\Http\Controllers\Admin\BarangayOfficialController::class, 'edit'])->name('officials.edit-single');
    Route::post('officials/edit-single', [App\Http\Controllers\Admin\BarangayOfficialController::class, 'update'])->name('officials.update-single');
    Route::delete('officials/photo/{field}', [App\Http\Controllers\Admin\BarangayOfficialController::class, 'deletePhoto'])->name('officials.delete-photo');
    
   
});

// Resident Management Routes
// Removed duplicate: Route::get('/admin/residents', [ResidentController::class, 'index'])->name('admin.residents');

// Public routes
Route::get('/', [PublicController::class, 'home'])->name('public.home');
Route::get('/about', [PublicController::class, 'about'])->name('public.about');
Route::get('/services', [PublicController::class, 'services'])->name('public.services');
Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');

// Officials and Announcements routes
Route::get('/officials', [PublicController::class, 'officials'])->name('public.officials');
Route::get('/announcements', [PublicController::class, 'announcements'])->name('public.announcements');

// Chatbot API routes
Route::post('/chatbot', [App\Http\Controllers\ChatbotController::class, 'chat'])->name('chatbot.chat');

// Test routes
Route::get('/test-admin-response', function() {
    return view('test-admin-response');
});

Route::get('/admin-chat-debug', function() {
    return view('admin-chat-debug');
});

// Public Pre-Registration Routes - Multi-step
Route::get('/register', [\App\Http\Controllers\Public\PreRegistrationController::class, 'chooseRegistrationType'])->name('public.register');
Route::prefix('pre-registration')->name('public.pre-registration.')->group(function () {
    Route::get('step1', [\App\Http\Controllers\Public\PreRegistrationController::class, 'createStep1'])->name('step1');
    Route::post('step1', [\App\Http\Controllers\Public\PreRegistrationController::class, 'storeStep1'])->name('step1.store');
    Route::get('step2', [\App\Http\Controllers\Public\PreRegistrationController::class, 'createStep2'])->name('step2');
    Route::post('step2', [\App\Http\Controllers\Public\PreRegistrationController::class, 'storeStep2'])->name('step2.store');
    Route::get('step3', [\App\Http\Controllers\Public\PreRegistrationController::class, 'createStep3'])->name('step3');
    Route::post('step3', [\App\Http\Controllers\Public\PreRegistrationController::class, 'storeStep3'])->name('step3.store');
    Route::get('step4', [\App\Http\Controllers\Public\PreRegistrationController::class, 'createStep4'])->name('step4');
    Route::post('step4', [\App\Http\Controllers\Public\PreRegistrationController::class, 'storeStep4'])->name('step4.store');
    Route::get('step4-senior', [\App\Http\Controllers\Public\PreRegistrationController::class, 'createStep4Senior'])->name('step4-senior');
    Route::post('step4-senior', [\App\Http\Controllers\Public\PreRegistrationController::class, 'storeStep4Senior'])->name('step4-senior.store');
    Route::get('step5', [\App\Http\Controllers\Public\PreRegistrationController::class, 'createStep5'])->name('step5');
    Route::post('step5', [\App\Http\Controllers\Public\PreRegistrationController::class, 'storeStep5'])->name('step5.store');
    Route::get('review', [\App\Http\Controllers\Public\PreRegistrationController::class, 'createReview'])->name('review');
    Route::post('submit', [\App\Http\Controllers\Public\PreRegistrationController::class, 'store'])->name('submit');
    Route::get('success', [\App\Http\Controllers\Public\PreRegistrationController::class, 'success'])->name('success');
    Route::match(['get', 'post'], 'check-status', [\App\Http\Controllers\Public\PreRegistrationController::class, 'checkStatus'])->name('check-status');
});

// Senior Citizen Pre-Registration Routes
Route::prefix('senior-registration')->name('public.senior-registration.')->group(function () {
    Route::get('step1', [\App\Http\Controllers\Public\SeniorRegistrationController::class, 'createStep1'])->name('step1');
    Route::post('step1', [\App\Http\Controllers\Public\SeniorRegistrationController::class, 'storeStep1'])->name('step1.store');
    Route::get('step2', [\App\Http\Controllers\Public\SeniorRegistrationController::class, 'createStep2'])->name('step2');
    Route::post('step2', [\App\Http\Controllers\Public\SeniorRegistrationController::class, 'storeStep2'])->name('step2.store');
    Route::get('step3', [\App\Http\Controllers\Public\SeniorRegistrationController::class, 'createStep3'])->name('step3');
    Route::post('step3', [\App\Http\Controllers\Public\SeniorRegistrationController::class, 'storeStep3'])->name('step3.store');
    Route::get('step4', [\App\Http\Controllers\Public\SeniorRegistrationController::class, 'createStep4'])->name('step4');
    Route::post('step4', [\App\Http\Controllers\Public\SeniorRegistrationController::class, 'storeStep4'])->name('step4.store');
    Route::get('step5', [\App\Http\Controllers\Public\SeniorRegistrationController::class, 'createStep5'])->name('step5');
    Route::post('step5', [\App\Http\Controllers\Public\SeniorRegistrationController::class, 'storeStep5'])->name('step5.store');
    Route::get('review', [\App\Http\Controllers\Public\SeniorRegistrationController::class, 'createReview'])->name('review');
    Route::post('submit', [\App\Http\Controllers\Public\SeniorRegistrationController::class, 'store'])->name('submit');
    Route::get('success', [\App\Http\Controllers\Public\SeniorRegistrationController::class, 'success'])->name('success');
});

// Document Request Public Routes
Route::get('/request-document', [App\Http\Controllers\DocumentRequestController::class, 'create'])->name('documents.request');
Route::post('/request-document', [App\Http\Controllers\DocumentRequestController::class, 'store'])->name('documents.store');
Route::post('/check-resident', [App\Http\Controllers\DocumentRequestController::class, 'checkResident'])->name('documents.check-resident');
Route::post('/send-otp', [App\Http\Controllers\DocumentRequestController::class, 'sendOtp'])->name('documents.send-otp');
Route::post('/verify-otp', [App\Http\Controllers\DocumentRequestController::class, 'verifyOtp'])->name('documents.verify-otp');
Route::post('/decode-qr', [App\Http\Controllers\DocumentRequestController::class, 'decodeQr'])->name('documents.decode-qr');
Route::get('/verify/{uuid}', [App\Http\Controllers\DocumentVerificationController::class, 'show'])->name('documents.verify');

// Health Request Public Routes
Route::get('/health/request', function () {
    return view('public.forms.health-request');
})->name('health.request');
Route::post('/health/check-resident', [App\Http\Controllers\HealthServiceController::class, 'checkResident'])->name('health.check-resident');
Route::post('/health/send-otp', [App\Http\Controllers\HealthServiceController::class, 'sendOtp'])->name('health.send-otp');
Route::post('/health/verify-otp', [App\Http\Controllers\HealthServiceController::class, 'verifyOtp'])->name('health.verify-otp');
Route::post('/health/decode-qr', [App\Http\Controllers\HealthServiceController::class, 'decodeQr'])->name('health.decode-qr');
Route::post('/health/request', [App\Http\Controllers\HealthServiceController::class, 'store'])->name('health.store');



// Blotter/Complaint Public Routes (Unified System)
Route::get('/blotter-complaint/request', [App\Http\Controllers\BlotterComplaintController::class, 'create'])->name('blotter-complaint.request');
Route::post('/blotter-complaint/store', [App\Http\Controllers\BlotterComplaintController::class, 'store'])->name('blotter-complaint.store');
Route::post('/blotter-complaint/check-resident', [App\Http\Controllers\BlotterComplaintController::class, 'checkResident'])->name('blotter-complaint.check-resident');
Route::post('/blotter-complaint/send-otp', [App\Http\Controllers\BlotterComplaintController::class, 'sendOtp'])->name('blotter-complaint.send-otp');
Route::post('/blotter-complaint/verify-otp', [App\Http\Controllers\BlotterComplaintController::class, 'verifyOtp'])->name('blotter-complaint.verify-otp');
Route::post('/blotter-complaint/decode-qr', [App\Http\Controllers\BlotterComplaintController::class, 'decodeQr'])->name('blotter-complaint.decode-qr');

// Resident ID full preview route
Route::get('/resident/{resident}/id/full-preview', [ResidentIdController::class, 'fullPreview'])->name('id.full-preview');

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
        ->name('password.google-update');
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
    'account.lockout',
    'session.security', 
    'enforce.password.change',
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

    // Admin-only routes (Super Admin, Barangay Captain, Secretary)
    Route::middleware('role:Barangay Captain,Barangay Secretary')->group(function () {
        Route::get('/admin/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Document Requests Management
        Route::get('/admin/documents', [App\Http\Controllers\DocumentRequestController::class, 'index'])->name('admin.documents');
        Route::get('/admin/documents/{documentRequest}', [App\Http\Controllers\DocumentRequestController::class, 'show']);
        Route::post('/admin/documents/{documentRequest}/approve', [App\Http\Controllers\DocumentRequestController::class, 'approve']);
        Route::post('/admin/documents/{documentRequest}/reject', [App\Http\Controllers\DocumentRequestController::class, 'reject']);
        Route::post('/admin/documents/{documentRequest}/mark-claimed', [App\Http\Controllers\DocumentRequestController::class, 'markAsClaimed']);
        Route::get('/admin/documents/{documentRequest}/view', [App\Http\Controllers\DocumentGeneratorController::class, 'generateDocument'])->name('admin.documents.view');
        Route::get('/admin/documents/{documentRequest}/print', [App\Http\Controllers\DocumentGeneratorController::class, 'generateDocument'])->name('admin.documents.print');
        
        // Resident Management Routes - Multi-step Form
        Route::prefix('admin/pre-registrations')->name('admin.pre-registrations.')->group(function() {
            Route::get('/', [App\Http\Controllers\Admin\PreRegistrationController::class, 'index'])->name('index');
            Route::get('/{preRegistration}', [App\Http\Controllers\Admin\PreRegistrationController::class, 'show'])->name('show');
            Route::post('/{preRegistration}/approve', [App\Http\Controllers\Admin\PreRegistrationController::class, 'approve'])->name('approve');
            Route::post('/{preRegistration}/reject', [App\Http\Controllers\Admin\PreRegistrationController::class, 'reject'])->name('reject');
        });
        
        // Resident Management Routes
        Route::prefix('admin/residents')->name('admin.residents.')->group(function() {
            Route::get('/', [ResidentController::class, 'index'])->name('index');
            Route::get('/archived', [ResidentController::class, 'archived'])->name('archived');
            Route::get('/create', [ResidentController::class, 'create'])->name('create');
            // Barangay ID Registration page for admin
            Route::get('/barangay-id-registration', [ResidentController::class, 'barangayIdRegistration'])->name('barangay-id-registration');
            Route::get('census-data', [ResidentController::class, 'censusData'])->name('census-data');
            // Census CRUD routes
            Route::post('census-data', [ResidentController::class, 'storeCensusRecord'])->name('census-data.store');
            Route::get('census-data/{household}/edit', [ResidentController::class, 'editCensusRecord'])->name('census-data.edit');
            Route::put('census-data/{household}', [ResidentController::class, 'updateCensusRecord'])->name('census-data.update');
            Route::delete('census-data/{household}', [ResidentController::class, 'destroyCensusRecord'])->name('census-data.destroy');
            
            // Census registration routes
            Route::get('/census/create', [ResidentController::class, 'createCensus'])->name('census.create');
            Route::post('/census/store', [ResidentController::class, 'storeCensus'])->name('census.store');
            
            // Multi-step census registration routes
            Route::get('/census/step1', [ResidentController::class, 'censusStep1'])->name('census.step1');
            Route::post('/census/step1', [ResidentController::class, 'storeCensusStep1'])->name('census.step1.store');
            Route::get('/census/step2', [ResidentController::class, 'censusStep2'])->name('census.step2');
            Route::post('/census/step2', [ResidentController::class, 'storeCensusStep2'])->name('census.step2.store');
            Route::get('/census/step3', [ResidentController::class, 'censusStep3'])->name('census.step3');
            Route::post('/census/step3', [ResidentController::class, 'storeCensusStep3'])->name('census.step3.store');
            // Multi-step resident creation routes
            Route::get('/create/step1', [ResidentController::class, 'createStep1'])->name('create.step1');
            Route::post('/create/step1', [ResidentController::class, 'storeStep1'])->name('create.step1.store');
            Route::get('/create/step2', [ResidentController::class, 'createStep2'])->name('create.step2');
            Route::post('/create/step2', [ResidentController::class, 'storeStep2'])->name('create.step2.store');
            Route::get('/create/step3', [ResidentController::class, 'createStep3'])->name('create.step3');
            Route::post('/create/step3', [ResidentController::class, 'storeStep3'])->name('create.step3.store');
            Route::post('/create/step3/remove-file', [ResidentController::class, 'removeUploadedFile'])->name('create.step3.remove-file');
            Route::get('/create/step4', [ResidentController::class, 'createStep4'])->name('create.step4');
            Route::post('/create/step4', [ResidentController::class, 'storeStep4'])->name('create.step4.store');
            Route::get('/create/step4-senior', [ResidentController::class, 'createStep4Senior'])->name('create.step4-senior');
            Route::post('/create/step4-senior', [ResidentController::class, 'storeStep4Senior'])->name('create.step4-senior.store');
            Route::get('/create/step5', [ResidentController::class, 'createStep5'])->name('create.step5');
            Route::post('/create/step5', [ResidentController::class, 'storeStep5'])->name('create.step5.store');
            Route::get('/create/review', [ResidentController::class, 'createReview'])->name('create.review');
            Route::post('/create/store', [App\Http\Controllers\ResidentController::class, 'store'])->name('create.store');
            
            // API routes for resident selection
            Route::get('/api/all', [ResidentController::class, 'getAllResidentsApi'])->name('api.all');
            
            Route::get('/{resident}', [App\Http\Controllers\ResidentController::class, 'show'])->name('show');
            Route::put('/{resident}', [App\Http\Controllers\ResidentController::class, 'update'])->name('update');
            Route::delete('/{resident}', [App\Http\Controllers\ResidentController::class, 'destroy'])->name('destroy');
            Route::post('/{resident}/upload-photo', [App\Http\Controllers\ResidentController::class, 'uploadPhoto'])->name('upload-photo');
            Route::post('/{resident}/upload-signature', [App\Http\Controllers\ResidentController::class, 'uploadSignature'])->name('upload-signature');
            Route::put('/{resident}/update-id-info', [App\Http\Controllers\ResidentController::class, 'updateIdInfo'])->name('update-id-info');
            Route::get('/{resident}/edit', [App\Http\Controllers\ResidentController::class, 'edit'])->name('edit');
            Route::get('/{resident}/services', [App\Http\Controllers\ResidentController::class, 'services'])->name('services');
            Route::delete('/{resident}/force-delete', [App\Http\Controllers\ResidentController::class, 'forceDelete'])->name('force-delete');
            Route::get('/{resident}/generate-issue-id', [App\Http\Controllers\ResidentIdController::class, 'generateNewIssueId'])->name('id.generate');
        });
        
        // Admin Profile Management
        Route::get('/admin/profile', [AdminProfileController::class, 'show'])->name('admin.profile');
        Route::put('/admin/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
        Route::post('/admin/profile/photo', [AdminProfileController::class, 'updatePhoto'])->name('admin.profile.photo.update');
        Route::post('/admin/profile/photo/delete', [AdminProfileController::class, 'deleteProfilePhoto'])->name('admin.profile.photo.delete');
        Route::post('/admin/profile/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.password.update');
        
        // Admin Approvals Management
        Route::prefix('admin/approvals')->name('admin.approvals.')->group(function() {
            Route::get('/', [AdminApprovalController::class, 'index'])->name('index');
            Route::get('/create', [AdminApprovalController::class, 'create'])->name('create');
            Route::post('/', [AdminApprovalController::class, 'store'])->name('store');
            Route::get('/{approval}/edit', [AdminApprovalController::class, 'edit'])->name('edit');
            Route::put('/{approval}', [AdminApprovalController::class, 'update'])->name('update');
            Route::delete('/{approval}', [AdminApprovalController::class, 'destroy'])->name('destroy');
        });
        
        // Access Requests Management
        Route::prefix('admin/access-requests')->name('admin.access-requests.')->group(function() {
            Route::get('/', [App\Http\Controllers\Admin\AccessRequestController::class, 'index'])->name('index');
            Route::get('/{accessRequest}', [App\Http\Controllers\Admin\AccessRequestController::class, 'show'])->name('show');
            Route::post('/{accessRequest}/approve', [App\Http\Controllers\Admin\AccessRequestController::class, 'approve'])->name('approve');
            Route::post('/{accessRequest}/deny', [App\Http\Controllers\Admin\AccessRequestController::class, 'deny'])->name('deny');
        });
        
        // Resident ID Management
        Route::prefix('admin/resident-ids')->name('admin.resident-ids.')->group(function() {
            Route::get('/', [App\Http\Controllers\ResidentIdController::class, 'index'])->name('index');
            Route::get('/{residentId}', [App\Http\Controllers\ResidentIdController::class, 'show'])->name('show');
            Route::post('/{residentId}/generate', [App\Http\Controllers\ResidentIdController::class, 'generate'])->name('generate');
            Route::get('/{residentId}/print', [App\Http\Controllers\ResidentIdController::class, 'print'])->name('print');
            Route::post('/{residentId}/upload-photo', [App\Http\Controllers\ResidentIdController::class, 'uploadPhoto'])->name('upload-photo');
            Route::post('/{residentId}/upload-signature', [App\Http\Controllers\ResidentIdController::class, 'uploadSignature'])->name('upload-signature');
            Route::put('/{residentId}/update-id-info', [App\Http\Controllers\ResidentIdController::class, 'updateIdInfo'])->name('update-id-info');
        });
        
        // Resident ID Pending Management - Add this route for the navigation menu
        Route::get('/admin/residents/id/pending', [ResidentIdController::class, 'pendingIds'])->name('admin.residents.id.pending');
        
        // Senior Citizens Management
        Route::prefix('admin/senior-citizens')->name('admin.senior-citizens.')->group(function() {
            Route::get('/', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'index'])->name('index');
            Route::get('/archived', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'archived'])->name('archived');
            Route::post('/restore/{id}', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'restore'])->name('restore');
            Route::delete('/force-delete/{id}', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'forceDelete'])->name('force-delete');
            
            // Multi-step registration routes
            Route::get('/register/step1', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'createStep1'])->name('register.step1');
            Route::post('/register/step1', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'storeStep1'])->name('register.step1.store');
            Route::get('/register/step2', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'createStep2'])->name('register.step2');
            Route::post('/register/step2', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'storeStep2'])->name('register.step2.store');
            Route::get('/register/step3', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'createStep3'])->name('register.step3');
            Route::post('/register/step3', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'storeStep3'])->name('register.step3.store');
            Route::get('/register/step4', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'createStep4'])->name('register.step4');
            Route::post('/register/step4', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'storeStep4'])->name('register.step4.store');
            Route::get('/register/step5', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'createStep5'])->name('register.step5');
            Route::post('/register/store', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'store'])->name('register.store');
            
            // Legacy single-step registration (redirect to step 1)
            Route::get('/register', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'redirectToStep1'])->name('register');
            
            Route::get('/id/pending', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'idPending'])->name('id.pending');
            Route::post('/{seniorCitizen}/revoke-id', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'revokeId'])->name('revoke-id');
            Route::post('/{seniorCitizen}/remove-issuance', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'removeFromIssuanceQueue'])->name('remove-issuance');
            Route::post('/{seniorCitizen}/remove-renewal', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'removeFromRenewalQueue'])->name('remove-renewal');
            Route::get('/{seniorCitizen}/services', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'services'])->name('services');
            Route::post('/{seniorCitizen}/archive', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'archive'])->name('archive');
           Route::get('/{seniorCitizen}', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'showIdManagement'])->name('show');
            Route::put('/{seniorCitizen}', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'update'])->name('update');
            Route::delete('/{seniorCitizen}', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'destroy'])->name('destroy');
            Route::post('/{seniorCitizen}/upload-photo', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'uploadPhoto'])->name('upload-photo');
            Route::post('/{seniorCitizen}/upload-signature', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'uploadSignature'])->name('upload-signature');
            Route::put('/{seniorCitizen}/update-id-info', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'updateIdInfo'])->name('update-id-info');
            Route::post('/{seniorCitizen}/mark-renewal', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'markForRenewal'])->name('mark-renewal');
            
            // API routes for senior citizen selection
            Route::get('/api/all', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'getAllSeniorCitizensApi'])->name('api.all');
            Route::get('/api/all', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'getAllSeniorCitizensApi'])->name('api.all');
        });
    });
    
    // Health Worker routes
    Route::middleware('role:Health Worker,Barangay Captain,Barangay Secretary')->group(function () {
        Route::get('/admin/health', [HealthServiceController::class, 'adminDashboard'])->name('admin.health');
        
        // Health Service Management Routes
        Route::prefix('admin/health-services')->name('admin.health-services.')->group(function() {
            Route::get('/', [HealthServiceController::class, 'index'])->name('index');
            Route::get('/{id}', [HealthServiceController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [HealthServiceController::class, 'approve'])->name('approve');
            Route::post('/{id}/complete', [HealthServiceController::class, 'complete'])->name('complete');
            Route::post('/{id}/reject', [HealthServiceController::class, 'reject'])->name('reject');
        });
        
        // Health Meeting Management Routes
        Route::prefix('admin/health-meetings')->name('admin.health-meetings.')->group(function() {
            Route::post('/', [HealthMeetingController::class, 'store'])->name('store');
            Route::post('/{id}/complete', [HealthMeetingController::class, 'complete'])->name('complete');
            Route::post('/{id}/cancel', [HealthMeetingController::class, 'cancel'])->name('cancel');
        });
        
        // GAD (Gender and Development) Routes
        Route::prefix('admin/gad')->name('admin.gad.')->group(function() {
            Route::get('/', [GadController::class, 'index'])->name('index');
            Route::get('/create', [GadController::class, 'create'])->name('create');
            Route::post('/', [GadController::class, 'store'])->name('store');
            Route::get('/{gad}/edit', [GadController::class, 'edit'])->name('edit');
            Route::put('/{gad}', [GadController::class, 'update'])->name('update');
            Route::delete('/{gad}', [GadController::class, 'destroy'])->name('destroy');
        });
        
        // GAD Archived Management
        Route::get('/admin/gad/archived', [GadController::class, 'archived'])->name('admin.gad.archived');
        
        // GAD Reports Management
        Route::get('/admin/gad/reports', [GadController::class, 'reports'])->name('admin.gad.reports');
        
        // Senior Citizens Management
        Route::prefix('admin/senior-citizens')->name('admin.senior-citizens.')->group(function() {
            Route::get('/', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'index'])->name('index');
            Route::get('/{seniorCitizen}', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'showIdManagement'])->name('show');
            Route::put('/{seniorCitizen}', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'update'])->name('update');
            Route::delete('/{seniorCitizen}', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'destroy'])->name('destroy');
            Route::post('/{seniorCitizen}/upload-photo', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'uploadPhoto'])->name('upload-photo');
            Route::post('/{seniorCitizen}/upload-signature', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'uploadSignature'])->name('upload-signature');
            Route::put('/{seniorCitizen}/update-id-info', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'updateIdInfo'])->name('update-id-info');
        });
        
        // Reports Management Routes
        Route::prefix('admin/reports')->name('admin.reports.')->group(function() {
            Route::get('/residents', [ResidentController::class, 'reports'])->name('residents');
            Route::get('/senior-citizens', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'reports'])->name('senior-citizens');
            Route::get('/archived-residents', [ResidentController::class, 'archivedReports'])->name('archived-residents');
            Route::get('/documents', [App\Http\Controllers\DocumentRequestController::class, 'reports'])->name('documents');
        });
    });
    
    // Health Worker routes
    Route::middleware('role:Health Worker,Barangay Captain,Barangay Secretary')->group(function () {
        Route::get('/admin/health', [HealthServiceController::class, 'adminDashboard'])->name('admin.health');
        
        // Health Service Management Routes
        Route::prefix('admin/health-services')->name('admin.health-services.')->group(function() {
            Route::get('/', [HealthServiceController::class, 'index'])->name('index');
            Route::get('/{id}', [HealthServiceController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [HealthServiceController::class, 'approve'])->name('approve');
            Route::post('/{id}/complete', [HealthServiceController::class, 'complete'])->name('complete');
            Route::post('/{id}/reject', [HealthServiceController::class, 'reject'])->name('reject');
        });
        
        // Health Meeting Management Routes
        Route::prefix('admin/health-meetings')->name('admin.health-meetings.')->group(function() {
            Route::post('/', [HealthMeetingController::class, 'store'])->name('store');
            Route::post('/{id}/complete', [HealthMeetingController::class, 'complete'])->name('complete');
            Route::post('/{id}/cancel', [HealthMeetingController::class, 'cancel'])->name('cancel');
        });
        
        // GAD (Gender and Development) Routes
        Route::prefix('admin/gad')->name('admin.gad.')->group(function() {
            Route::get('/', [GadController::class, 'index'])->name('index');
            Route::get('/create', [GadController::class, 'create'])->name('create');
            Route::post('/', [GadController::class, 'store'])->name('store');
            Route::get('/{gad}/edit', [GadController::class, 'edit'])->name('edit');
            Route::put('/{gad}', [GadController::class, 'update'])->name('update');
            Route::delete('/{gad}', [GadController::class, 'destroy'])->name('destroy');
        });
        
        // GAD Archived Management
        Route::get('/admin/gad/archived', [GadController::class, 'archived'])->name('admin.gad.archived');
        
        // GAD Reports Management
        Route::get('/admin/gad/reports', [GadController::class, 'reports'])->name('admin.gad.reports');
        
        // Senior Citizens Management
        Route::prefix('admin/senior-citizens')->name('admin.senior-citizens.')->group(function() {
            Route::get('/', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'index'])->name('index');
            Route::get('/{seniorCitizen}', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'showIdManagement'])->name('show');
            Route::put('/{seniorCitizen}', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'update'])->name('update');
            Route::delete('/{seniorCitizen}', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'destroy'])->name('destroy');
            Route::post('/{seniorCitizen}/upload-photo', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'uploadPhoto'])->name('upload-photo');
            Route::post('/{seniorCitizen}/upload-signature', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'uploadSignature'])->name('upload-signature');
            Route::put('/{seniorCitizen}/update-id-info', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'updateIdInfo'])->name('update-id-info');
        });
    });

    // User Activity & Security routes
    // Admin User Activity Management
    Route::middleware('role:Barangay Captain,Barangay Secretary,Health Worker,Complaint Manager')->group(function () {
        Route::get('/admin/activities', [UserActivityController::class, 'adminIndex'])
            ->name('admin.activities');
            
        // User Activity and Security Dashboard Routes - RENAMED to avoid conflict
        Route::get('/admin/security/user-activities', [AdminUserActivityController::class, 'dashboard'])
            ->name('admin.security.user-activities');
        Route::get('/admin/security/activities', [AdminUserActivityController::class, 'activities'])
            ->name('admin.security.activities');
        Route::get('/admin/security/users/{user}/sessions', [AdminUserActivityController::class, 'userSessions'])
            ->name('admin.security.user-sessions');
        Route::delete('/admin/security/sessions/{sessionId}', [AdminUserActivityController::class, 'terminateSession'])
            ->name('admin.security.terminate-session');
    });

    // Enhanced Security Management Routes (Barangay Captain and Secretary only)
    Route::middleware('role:Barangay Captain,Barangay Secretary')->group(function () {
        // Main Security Dashboard - This should be the primary security dashboard
        Route::get('/admin/security/dashboard', [SecurityController::class, 'dashboard'])
            ->name('admin.security.dashboard');
        Route::get('/admin/security', [SecurityController::class, 'dashboard'])
            ->name('admin.security.index');
        Route::get('/admin/security/analytics', [SecurityController::class, 'getAnalytics'])
            ->name('admin.security.analytics');
        // Audit Log Page
        Route::get('/admin/security/audit-logs', [AuditLogController::class, 'index'])->name('admin.security.audit-logs');
        
        // Analytics Route for Barangay Captain and Secretary
        Route::get('/admin/analytics', [AnalyticsController::class, 'index'])
            ->name('admin.analytics');
        
        // Account management actions
        Route::post('/admin/security/users/{user}/unlock', [SecurityController::class, 'unlockAccount'])
            ->name('admin.security.unlock-account');
        Route::post('/admin/security/users/{user}/force-password-change', [SecurityController::class, 'forcePasswordChange'])
            ->name('admin.security.force-password-change');
        Route::post('/admin/security/users/{user}/disable', [SecurityController::class, 'disableAccount'])
            ->name('admin.security.disable-account');
        
        // Session management
        Route::get('/admin/security/users/{user}/sessions', [SecurityController::class, 'getUserSessions'])
            ->name('admin.security.get-user-sessions');
        Route::delete('/admin/security/users/{user}/sessions/{sessionId}', [SecurityController::class, 'terminateSession'])
            ->name('admin.security.terminate-user-session');
    });
});

// Test routes for debugging
Route::prefix('test')->group(function () {
    Route::get('household-update/{resident_id}', [TestController::class, 'testHouseholdUpdate']);
    Route::get('database-structure', [TestController::class, 'showDatabaseStructure']);
    Route::get('household-data/{resident_id}', [TestController::class, 'getHouseholdData']);
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

// Public Pre-Registration Routes - Multi-step
Route::get('/debug-escalations', function () {
    $controller = new App\Http\Controllers\LiveChatController();
    return $controller->getActiveEscalations();
});

// Debug auth status
Route::get('/debug-auth', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->user(),
        'role' => auth()->user() ? auth()->user()->role : null,
        'time' => now()
    ]);
});

// Test chat layout
Route::get('/test-chat-layout', function () {
    return view('test-chat-layout');
});

// Resident ID Bulk Upload Management
Route::get('/admin/residents/id/bulk-upload', [ResidentIdController::class, 'bulkUpload'])->name('admin.residents.bulk-upload');
Route::post('/admin/residents/id/bulk-upload', [ResidentIdController::class, 'processBulkUpload'])->name('admin.residents.id.bulk-upload.process');
Route::post('/admin/residents/id/bulk-signature-upload', [ResidentIdController::class, 'processBulkSignatureUpload'])->name('admin.residents.id.bulk-signature-upload');
Route::post('/admin/residents/id/bulk-issue', [ResidentIdController::class, 'bulkIssue'])->name('admin.residents.id.bulk-issue');
Route::get('/admin/residents/id/{resident}', [ResidentIdController::class, 'show'])->name('admin.residents.id.show');
Route::get('/admin/residents/id/{resident}/preview', [ResidentIdController::class, 'previewId'])->name('admin.residents.id.preview');
Route::get('/admin/residents/id/{resident}/download', [ResidentIdController::class, 'downloadId'])->name('admin.residents.id.download');
Route::put('/admin/residents/id/{resident}', [ResidentIdController::class, 'updateIdInfo'])->name('admin.residents.id.update');
Route::post('/admin/residents/id/{resident}/mark-renewal', [ResidentIdController::class, 'markForRenewal'])->name('admin.residents.id.mark-renewal');
Route::post('/admin/residents/id/{resident}/revoke', [ResidentIdController::class, 'revoke'])->name('admin.residents.id.revoke');
Route::post('/admin/residents/id/{resident}/issue', [ResidentIdController::class, 'issueId'])->name('admin.residents.id.issue');
Route::post('/admin/residents/id/{resident}/remove-renewal', [ResidentIdController::class, 'removeFromRenewal'])->name('admin.residents.id.remove-renewal');
Route::post('/admin/residents/id/{resident}/remove-issuance', [ResidentIdController::class, 'removeFromIssuance'])->name('admin.residents.id.remove-issuance');
// GAD Show Management
Route::get('/admin/gad/{gad}', [GadController::class, 'show'])->name('admin.gad.show');
Route::post('/admin/gad/{gad}/restore', [GadController::class, 'restore'])->name('admin.gad.restore');
Route::delete('/admin/gad/{gad}/force-delete', [GadController::class, 'forceDelete'])->name('admin.gad.force-delete');
// Senior Citizens Edit Management
Route::get('/admin/senior-citizens/{seniorCitizen}/edit', [SeniorCitizenController::class, 'edit'])->name('admin.senior-citizens.edit');
Route::get('/admin/senior-citizens/{seniorCitizen}/id-management', [SeniorCitizenController::class, 'showIdManagement'])->name('admin.senior-citizens.id-management');
Route::get('/admin/senior-citizens/{seniorCitizen}/id/preview', [SeniorCitizenController::class, 'previewId'])->name('admin.senior-citizens.id.preview');
Route::get('/admin/senior-citizens/{seniorCitizen}/id/download', [SeniorCitizenController::class, 'downloadId'])->name('admin.senior-citizens.id.download');
Route::post('/admin/senior-citizens/{seniorCitizen}/issue-id', [App\Http\Controllers\Admin\SeniorCitizenController::class, 'issueId'])->name('admin.senior-citizens.issue-id');
Route::post('/admin/residents/{resident}/restore', [ResidentController::class, 'restore'])->name('admin.residents.restore');
Route::post('/admin/residents/id/{resident}/upload-photo', [ResidentIdController::class, 'uploadPhoto'])->name('admin.residents.id.upload-photo');
Route::post('/admin/residents/id/{resident}/upload-signature', [ResidentIdController::class, 'uploadSignature'])->name('admin.residents.id.upload-signature');

// Single-form Barangay Officials Management
Route::middleware(['role:Barangay Captain,Barangay Secretary'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('officials/edit-single', [App\Http\Controllers\Admin\BarangayOfficialController::class, 'edit'])->name('officials.edit-single');
    Route::post('officials/edit-single', [App\Http\Controllers\Admin\BarangayOfficialController::class, 'update'])->name('officials.update-single');
    Route::delete('officials/photo/{field}', [App\Http\Controllers\Admin\BarangayOfficialController::class, 'deletePhoto'])->name('officials.delete-photo');
    
   
});

// Admin Agent Conversation API Routes (for admin chatbot)
Route::middleware('auth')->prefix('api/admin/agent-conversation')->group(function () {
    Route::get('/active', [AdminAgentConversationController::class, 'getActiveConversations']);
    Route::get('/{sessionId}/messages', [AdminAgentConversationController::class, 'getConversationMessages']);
    Route::post('/send', [AdminAgentConversationController::class, 'sendMessage']);
    Route::post('/{sessionId}/mark-read', [AdminAgentConversationController::class, 'markAsRead']);
    Route::get('/{sessionId}/new-messages', [AdminAgentConversationController::class, 'getNewMessages']);
    Route::post('/complete-and-next', [AdminAgentConversationController::class, 'completeAndNext']);
    Route::post('/accept-next', [AdminAgentConversationController::class, 'acceptNextUser']);
});

// User Agent Conversation API Routes (for user escalation)
Route::prefix('api/agent-conversation')->group(function () {
    Route::post('/escalate', [UserAgentConversationController::class, 'escalateToAgent']);
    Route::post('/send-user', [UserAgentConversationController::class, 'sendUserMessage']);
    Route::get('/{sessionId}/new-messages', [UserAgentConversationController::class, 'getNewMessagesForUser']);
    Route::get('/{sessionId}/queue-status', [UserAgentConversationController::class, 'getQueueStatus']);
});
