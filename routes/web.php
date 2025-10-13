<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminImportController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ModeratorController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserProfileController;
use App\Models\Announcement;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\AnnouncementStatsController;


// User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('/profile/update', [UserController::class, 'update'])->name('user.update')->middleware('auth');
    Route::put('/user/update-password', [UserController::class, 'updatePassword'])->name('user.updatePassword');
    Route::put('/user/preferences', [UserController::class, 'updatePreferences'])->name('user.preferences.update');;
Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');

});

require __DIR__.'/auth.php';

// Static Pages
Route::get('/', function () {
    return view('index');
})->name('home');
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/announcement', [AnnouncementController::class, 'index'])->name('announcement');
Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcement.store');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Admin Dashboard and Import Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index']);
    Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])->name('dashboard.admin');
    Route::post('/dashboard/admin/import/faculty', [AdminImportController::class, 'importFaculty'])->name('admin.import.faculty');
    Route::post('/admin/import-students', [AdminImportController::class, 'importStudents'])->name('admin.import.students');
    Route::put('/admin/update-password', [UserController::class, 'updatePassword'])->name('admin.updatePassword')->middleware('auth');
    Route::get('/activity-logs/export/{format}', [ActivityLogController::class, 'export'])->name('activityLogs.export');
    Route::get('/activity-logs/export-raw/excel', [ActivityLogController::class, 'exportExcelRaw'])->name('activityLogs.exportRaw');
    // Route::middleware(['auth'])->get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.admin');
    Route::post('/admin/moderators', [AdminDashboardController::class, 'storeModerator'])->name('admin.moderators.store');
    Route::patch('/announcement/{id}/approve', [AnnouncementController::class, 'approve'])->name('announcement.approve');
    Route::patch('/announcement/{id}/reject', [AnnouncementController::class, 'reject'])->name('announcement.reject');
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    Route::get('/announcements/{id}', [AnnouncementController::class, 'show'])->name('announcements.show');
    Route::get('/announcements/{id}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{id}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::post('/admin/announcements', [AnnouncementController::class, 'store'])->name('announcement.admin.store');

});

Route::get('/account/{id}/edit', [UserProfileController::class, 'edit'])->name('account.edit');
Route::put('/account/{id}', [UserProfileController::class, 'update'])->name('account.update');

Route::get('/announcement/{id}/full', [AnnouncementController::class, 'loadFull'])->name('announcement.full');

// Registrar and USG Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/registrar', [\App\Http\Controllers\AnnouncementController::class, 'allPosts'])
        ->name('dashboard.registrar');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcement.store');
    Route::post('/admin/announcements', [AnnouncementController::class, 'store'])->name('announcement.admin.store');
    Route::put('/admin/profile/update', [AdminController::class, 'update'])->name('admin.update');

    Route::get('/usg', function () {
        return view('dashboard.usg');
    })->name('dashboard.usg');
});

Route::get('/activity-logs/export/{format}', [ActivityLogController::class, 'export'])->name('activityLogs.export');

// Faculty Export Route
Route::post('/faculty/{id}/export', [FacultyController::class, 'export'])->name('faculty.export');
// mods
Route::get('/moderators/{id}', [ModeratorController::class, 'show'])->name('moderators.show');
Route::post('/moderators/{id}/export', [ModeratorController::class, 'export'])->name('moderators.export');

Route::get('/dashboard/usg', [ModeratorController::class, 'usgDashboard'])->name('dashboard.usg');

Route::get('/test-model', function () {
    return Announcement::count();
});

// Faculty Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/faculty/{id}', [FacultyController::class, 'show'])->name('faculty.show');
    // View single student
    Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
    // Edit student
    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    // Export formats (JSON, Excel, pdf)
    Route::get('/students/{id}/export', [StudentController::class, 'export'])->name('students.export');
    // Toggle active/inactive
    Route::post('/students/{id}/toggle-status', [StudentController::class, 'toggleStatus'])->name('students.toggleStatus');
    // Delete 
    Route::delete('/accounts/{id}', [StudentController::class, 'destroy'])->name('accounts.destroy');
});

    // comment
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');
    Route::get('/announcement/{id}/comments', [AnnouncementController::class, 'loadComments'])->name('announcement.comments');

    // in routes/web.php
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Dashboard Route (Smart Redirection Based on Role)
    Route::middleware(['auth'])->get('/dashboard', function () {
        $role = Auth::user()->role;

        return match ($role) {
            'admin' => redirect()->route('dashboard.admin'),
            'registrar' => redirect()->route('dashboard.registrar'),
            'usg' => redirect()->route('dashboard.usg'),
            default => redirect('/'),
        };
    })->name('dashboard');

    Route::get('/notifications/mark-read', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'read']);
    })->name('notifications.markAllRead')->middleware('auth');

    Route::post('/admin/announcements', [AnnouncementController::class, 'store'])->name('announcement.admin.store');
    Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');
    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');
    Route::get('/logs/export/{format}', [ActivityLogController::class, 'exportLogs'])->name('logs.export');
    Route::get('/dashboard/admin', [ActivityLogController::class, 'recent'])->name('dashboard.admin');
        
    // Show forgot password form
 