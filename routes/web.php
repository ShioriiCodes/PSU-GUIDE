<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserSetupController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminImportController;
use App\Http\Controllers\AdminDashboardController;
use App\Models\User;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\AnnouncementController;


// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
    Route::post('/dashboard/admin/import/students', [AdminImportController::class, 'importStudents'])->name('admin.import.students');
});

// Registrar and USG Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/registrar', function () {
        return view('dashboard.registrar');
    })->name('dashboard.registrar');

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
});

// Student Routes
Route::middleware(['auth'])->prefix('students')->group(function () {
    Route::get('/{id}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::post('/{id}/toggle-status', [StudentController::class, 'toggleStatus'])->name('students.toggleStatus');
    Route::delete('/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::post('/{id}/export', [StudentController::class, 'export'])->name('students.export');
});

// Dashboard Route (Smart Redirection Based on Role)
Route::middleware(['auth'])->get('/dashboard', function () {
    $role = Auth::user()->role;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'registrar' => redirect()->route('dashboard.registrar'),
        'usg' => redirect()->route('dashboard.usg'),
        default => redirect('/'),
    };
})->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::post('/admin/announcements', [AdminDashboardController::class, 'store'])->name('announcement.store');
});

Route::post('/admin/announcements', [AnnouncementController::class, 'store'])->name('announcement.store');
