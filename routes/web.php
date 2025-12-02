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

/*
|--------------------------------------------------------------------------
| Authentication Routes (Login/Register/Logout)
|--------------------------------------------------------------------------
*/

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
        
    // Training Records
    Route::resource('training_records', TrainingRecordController::class);
    
    Route::post('/training_records/{id}/status', [TrainingRecordController::class, 'updateStatus'])
        ->name('training_records.update_status');
        
    // Training Proofs
    Route::post('/training_records/{id}/upload-proof', [TrainingProofController::class, 'upload'])
        ->name('training_proofs.upload');
        
// Test route removed
        
    Route::get('/training_proofs/{id}/download', [TrainingProofController::class, 'download'])
        ->name('training_proofs.download');
        
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