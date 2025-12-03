<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UnitDirectorController;
use App\Http\Controllers\OfficeHeadController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DirectoryReportsController;
use App\Http\Controllers\PendingApprovalsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TrainingRecordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainingProofController;
use App\Http\Controllers\TrainingAssignmentController;

/*
|--------------------------------------------------------------------------
| Authentication Routes (Login/Register/Logout)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

// Group routes that should only be accessible to guests (not logged in)
Route::middleware('guest')->group(function () {
    // Registration Form
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    
    // Handle Registration Submission
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Login Form
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    
    // Handle Login Submission
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});


// Group routes that require the user to be authenticated
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // --- Role-Based Dashboards ---
    // These need to be protected by the CheckRole middleware for security
    
    // Unit Director Dashboard
    Route::get('/admin/dashboard', [UnitDirectorController::class, 'index'])
        ->middleware('checkrole:unit_director,unit director')
        ->name('admin.dashboard');
    
    // Office Head Dashboard
    Route::get('/office-head/dashboard', [OfficeHeadController::class, 'index'])
        ->middleware('checkrole:head')
        ->name('office_head.dashboard');
    
    // Staff Dashboard
    Route::get('/staff/dashboard', [StaffController::class, 'index'])
        ->middleware('checkrole:staff')
        ->name('staff.dashboard');
        
    // Directory and Reports
    Route::get('/directory-reports', [DirectoryReportsController::class, 'index'])
        ->name('directory_reports.index');
        
    // Pending Approvals (Unit Director only)
    Route::get('/pending-approvals', [PendingApprovalsController::class, 'index'])
        ->middleware('checkrole:unit_director,unit director')
        ->name('pending_approvals.index');
        
    Route::post('/pending-approvals/{id}/approve', [PendingApprovalsController::class, 'approve'])
        ->middleware('checkrole:unit_director,unit director')
        ->name('pending_approvals.approve');
        
    Route::post('/pending-approvals/{id}/reject', [PendingApprovalsController::class, 'reject'])
        ->middleware('checkrole:unit_director,unit director')
        ->name('pending_approvals.reject');
        
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'getNotifications'])
        ->name('notifications.get');
        
    Route::post('/notifications/mark-read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.mark_read');
        
    Route::post('/notifications/delete', [NotificationController::class, 'deleteNotifications'])
        ->name('notifications.delete');
        
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])
        ->name('notifications.unread_count');
        
    Route::post('/notifications/broadcast', [NotificationController::class, 'broadcast'])
        ->middleware('checkrole:unit_director,unit director')
        ->name('notifications.broadcast');

    // Office Head: send notification to staff in their office + unit director(s)
    Route::post('/notifications/office-broadcast', [NotificationController::class, 'officeBroadcast'])
        ->middleware('checkrole:head')
        ->name('notifications.office_broadcast');
        
    // Training Records
    Route::resource('training_records', TrainingRecordController::class);
    Route::get('/training_records/{training_record}/edit-ajax', [TrainingRecordController::class, 'editAjax'])->name('training_records.edit_ajax');
    
    Route::post('/training_records/{id}/status', [TrainingRecordController::class, 'updateStatus'])
        ->name('training_records.update_status');
        
    // Training Assignments
    Route::middleware('checkrole:unit_director,unit director')->group(function () {
        Route::get('/training_assignments', [TrainingAssignmentController::class, 'index'])
            ->name('training_assignments.index');
        Route::get('/training_assignments/create', [TrainingAssignmentController::class, 'create'])
            ->name('training_assignments.create');
        Route::post('/training_assignments', [TrainingAssignmentController::class, 'store'])
            ->name('training_assignments.store');
    });
    
    Route::middleware('checkrole:staff')->group(function () {
        Route::get('/my_assigned_trainings', [TrainingAssignmentController::class, 'myAssignments'])
            ->name('training_assignments.my_assignments');
    });
        
    // Training Proofs
    Route::post('/training_records/{training_record}/upload-proof', [TrainingProofController::class, 'upload'])
        ->name('training_proofs.upload');
        
// Test route removed
        
    Route::get('/training_proofs/{id}/download', [TrainingProofController::class, 'download'])
        ->name('training_proofs.download');
        
    Route::get('/training_proofs/{id}/view', [TrainingProofController::class, 'view'])
        ->name('training_proofs.view');
        
    // Training Proof Review (Unit Director only)
    Route::middleware('checkrole:unit_director,unit director')->group(function () {
        Route::get('/training_proofs/review', [TrainingProofController::class, 'reviewIndex'])
            ->name('training_proofs.review_index');
        Route::get('/training_proofs/{id}/review', [TrainingProofController::class, 'review'])
            ->name('training_proofs.review');
        Route::post('/training_proofs/{id}/process-review', [TrainingProofController::class, 'processReview'])
            ->name('training_proofs.process_review');
    });
        
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');
        
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');
        
    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
        
    Route::get('/profile/change-password', [ProfileController::class, 'showChangePasswordForm'])
        ->name('profile.change_password');
        
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])
        ->name('profile.change_password.post');
});