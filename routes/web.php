<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

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

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authenticated routes
Route::middleware(['auth', 'student.owns.result'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home'); // Add this line
    
    Route::get('/results', [ResultController::class, 'index'])->name('results.index');
    Route::get('/results/semester/{semester}', [ResultController::class, 'show'])->name('results.semester');
    Route::get('/results/search', [ResultController::class, 'search'])->name('results.search');
    
    Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');
    Route::get('/progress/chart-data', [ProgressController::class, 'chartData'])->name('progress.chart-data');
    
    Route::get('/export/pdf/{semester}', [ExportController::class, 'pdf'])->name('export.pdf');
    Route::get('/export/csv/{semester}', [ExportController::class, 'csv'])->name('export.csv');
});

// Admin routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        // Redirect /admin to /admin/dashboard
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });
        
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Semesters CRUD
        Route::resource('semesters', \App\Http\Controllers\Admin\SemesterController::class);
        
        // Subjects CRUD
        Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
        
        // Students CRUD
        Route::resource('students', AdminStudentController::class);
        
        // Results CRUD
        Route::resource('results', \App\Http\Controllers\Admin\ResultController::class);
        
        // Grade Scales CRUD
        Route::resource('grade-scales', \App\Http\Controllers\Admin\GradeScaleController::class);
    });

