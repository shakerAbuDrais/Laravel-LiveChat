<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::post('/login', [LoginController::class, 'login']);
Auth::routes();

// Admin routes with middleware
Route::middleware(['auth', 'admin'])->prefix('admin-dashboard')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    // Add other admin routes here
});
Route::post('/store', [AdminController::class, 'store']);
Route::post('/createSubject', [AdminController::class, 'createSubject']);
Route::get('/getUnassignedSubjects', [AdminController::class, 'getUnassignedSubjects']);
Route::get('/getUsersWithoutSubject', [AdminController::class, 'getUsersWithoutSubject']);
Route::post('/assignSubject', [AdminController::class, 'assignSubject']);

// Home route
Route::get('/home', [HomeController::class, 'index'])->name('home');
