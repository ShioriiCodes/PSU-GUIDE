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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('/', function () {
    return view('index');
})->name('home');
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/announcement', function () {
    return view('announcement');
})->name('announcement');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');


// Use a group to protect both routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index']);
    Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/dashboard/admin/import/faculty', [AdminImportController::class, 'importFaculty'])->name('admin.import.faculty');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

// ✅ NEW CLEAN ROUTES
Route::middleware(['auth'])->group(function () {
    Route::get('/registrar', function () {
        return view('dashboard.registrar');
    })->name('dashboard.registrar');

    Route::get('/usg', function () {
        return view('dashboard.usg');   
    })->name('dashboard.usg');
});

Route::get('/setup-users', [UserSetupController::class, 'insertTestUsers']);
Route::post('/faculty/{id}/export', [FacultyController::class, 'export'])->name('faculty.export');

Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    Route::post('/admin/import/students', [AdminImportController::class, 'importStudents'])->name('admin.import.students');
});

Route::middleware(['auth'])->prefix('students')->group(function () {
    Route::get('/{id}', [StudentController::class, 'show'])->name('students.show');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/faculty/{id}', [FacultyController::class, 'show'])->name('faculty.show');
});

Route::post('/students/{id}/export', [StudentController::class, 'export'])->name('students.export');

Route::prefix('students')->middleware(['auth'])->group(function () {
    Route::get('/{id}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::post('/{id}/toggle-status', [StudentController::class, 'toggleStatus'])->name('students.toggleStatus');
    Route::delete('/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
});



Route::middleware(['auth'])->get('/dashboard', function () {
    $role = Auth::user()->role;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'registrar' => redirect()->route('dashboard.registrar'),
        'usg' => redirect()->route('dashboard.usg'),
        default => redirect('/'),
    };
})->name('dashboard');
