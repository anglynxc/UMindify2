<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mentor\MentorController;
use App\Http\Controllers\User\UserController;

// Public Routes
Route::get('/', [UserController::class, 'dashboard'])->name('home');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    
    // User Routes
    Route::middleware(['role:user'])->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/sessions', [UserController::class, 'browseSessions'])->name('sessions.browse');
        Route::get('/sessions/{id}', [UserController::class, 'sessionDetail'])->name('sessions.detail');
        Route::post('/sessions/{id}/register', [UserController::class, 'registerSession'])->name('sessions.register');
        Route::get('/my-sessions', [UserController::class, 'mySessions'])->name('sessions.my');
        Route::post('/registrations/{id}/cancel', [UserController::class, 'cancelRegistration'])->name('registrations.cancel');
        Route::get('/become-mentor', [UserController::class, 'becomeMentor'])->name('become-mentor');
        Route::post('/submit-mentor-application', [UserController::class, 'submitMentorApplication'])->name('submit-mentor-application');
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    });
    
    // Mentor Routes  
    Route::middleware(['role:mentor'])->prefix('mentor')->name('mentor.')->group(function () {
        Route::get('/dashboard', [MentorController::class, 'dashboard'])->name('dashboard');
        Route::get('/sessions', [MentorController::class, 'sessions'])->name('sessions');
        Route::get('/sessions/create', [MentorController::class, 'createSession'])->name('sessions.create');
        Route::post('/sessions/store', [MentorController::class, 'storeSession'])->name('sessions.store');
        Route::get('/sessions/{id}/edit', [MentorController::class, 'editSession'])->name('sessions.edit');
        Route::put('/sessions/{id}/update', [MentorController::class, 'updateSession'])->name('sessions.update');
        Route::delete('/sessions/{id}/delete', [MentorController::class, 'deleteSession'])->name('sessions.delete');
        Route::get('/profile', [MentorController::class, 'profile'])->name('profile');
        Route::put('/profile/update', [MentorController::class, 'updateProfile'])->name('profile.update');
        Route::get('/sessions/{id}/participants', [MentorController::class, 'sessionParticipants'])->name('sessions.participants');
        Route::post('/participants/{id}/approve', [MentorController::class, 'approveParticipant'])->name('participants.approve');
        Route::post('/participants/{id}/reject', [MentorController::class, 'rejectParticipant'])->name('participants.reject');
        Route::post('/sessions/{id}/activate', [MentorController::class, 'activateSession'])->name('sessions.activate');
        Route::post('/sessions/{id}/complete', [MentorController::class, 'completeSession'])->name('sessions.complete');
    });
    
    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/mentor-requests', [AdminController::class, 'mentorRequests'])->name('mentor-requests');
        Route::post('/approve-mentor/{id}', [AdminController::class, 'approveMentor'])->name('approve-mentor');
        Route::post('/reject-mentor/{id}', [AdminController::class, 'rejectMentor'])->name('reject-mentor');
        Route::get('/all-mentors', [AdminController::class, 'allMentors'])->name('all-mentors');
        Route::get('/all-users', [AdminController::class, 'allUsers'])->name('all-users');
        Route::get('/all-sessions', [AdminController::class, 'allSessions'])->name('all-sessions');
        Route::get('/manage-categories', [AdminController::class, 'manageCategories'])->name('manage-categories');
        Route::post('/store-category', [AdminController::class, 'storeCategory'])->name('store-category');
        Route::get('/manage-jurusans', [AdminController::class, 'manageJurusans'])->name('manage-jurusans');
        Route::post('/store-jurusan', [AdminController::class, 'storeJurusan'])->name('store-jurusan');
    });
});