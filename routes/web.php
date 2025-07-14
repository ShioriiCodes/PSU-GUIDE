<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserSetupController;
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
use App\Http\Controllers\AuthController;

// User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('/profile/update', [UserController::class, 'update'])->name('user.update')->middleware('auth');
    Route::put('/user/update-password', [UserController::class, 'updatePassword'])->name('user.updatePassword');
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('/profile/update', [UserController::class, 'update'])->name('user.update');  
    Route::put('/user/preferences', [UserController::class, 'updatePreferences'])->name('user.preferences.update');;

});

require __DIR__.'/auth.php';

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
    Route::middleware(['auth'])->get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.admin');
    Route::post('/admin/moderators', [AdminDashboardController::class, 'storeModerator'])->name('admin.moderators.store');
    Route::patch('/announcement/{id}/approve', [AnnouncementController::class, 'approve'])->name('announcement.approve');
    Route::patch('/announcement/{id}/reject', [AnnouncementController::class, 'reject'])->name('announcement.reject');
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    Route::get('/announcements/{id}', [AnnouncementController::class, 'show'])->name('announcements.show');
    Route::get('/announcements/{id}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{id}', [AnnouncementController::class, 'update'])->name('announcements.update');


});

// Registrar and USG Routes
Route::middleware(['auth'])->group(function () {
    Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/registrar', [\App\Http\Controllers\AnnouncementController::class, 'allPosts'])
        ->name('dashboard.registrar');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcement.store');
    Route::post('/admin/announcements', [AnnouncementController::class, 'store'])->name('announcement.admin.store');
    Route::put('/admin/profile/update', [AdminController::class, 'update'])->name('admin.update');
});

    Route::get('/usg', function () {
        return view('dashboard.usg');
    })->name('dashboard.usg');
});

// Setup Users (for testing or initial setup)
Route::get('/setup-users', [UserSetupController::class, 'insertTestUsers']);

// Faculty Export Route
Route::post('/faculty/{id}/export', [FacultyController::class, 'export'])->name('faculty.export');

// Faculty Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/faculty/{id}', [FacultyController::class, 'show'])->name('faculty.show');
    // View single student
    Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');

    // Edit student
    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');

    // Export formats (JSON, Excel)
    Route::get('/students/{id}/export', [StudentController::class, 'export'])->name('students.export');

    // Toggle active/inactive
    Route::post('/students/{id}/toggle-status', [StudentController::class, 'toggleStatus'])->name('students.toggleStatus');

    // Delete 
    Route::delete('/accounts/{id}', [StudentController::class, 'destroy'])->name('accounts.destroy');

});

// Student Routes

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


Route::post('/admin/announcements', [AnnouncementController::class, 'store'])->name('announcement.admin.store');

Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');
