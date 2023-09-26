<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::post('/login', [LoginController::class, 'login'])->name('login');
Auth::routes();

// Admin routes with middleware admin === 1
Route::middleware(['auth', 'admin'])->prefix('admin-dashboard')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
});
Route::middleware(['auth', 'checkUserStatus'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});
Route::post('/store', [AdminController::class, 'store']);
Route::post('/createSubject', [AdminController::class, 'createSubject']);
Route::get('/getUnassignedSubjects', [AdminController::class, 'getUnassignedSubjects']);
Route::get('/getUsersWithoutSubject', [AdminController::class, 'getUsersWithoutSubject']);
Route::post('/assignSubject', [AdminController::class, 'assignSubject']);
Route::post('/storeMark', [AdminController::class,'storeMark']);
Route::post('/updateUser', [AdminController::class,'updateUser'])->name('updateUser');
Route::post('/deleteUser', [AdminController::class,'deleteUser'])->name('deleteUser');

Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index']);
    // Route::get('/chat/{user}', [ChatController::class, 'show'])->name('chat.show');
    Route::get('/chat/{receiverId}', [ChatController::class, 'show']);
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
});


// Home route
// Route::get('/home', [HomeController::class, 'index'])->name('home');
