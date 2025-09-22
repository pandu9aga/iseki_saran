<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LeaderMiddleware;
use App\Http\Middleware\MemberMiddleware;
use App\Http\Controllers\MainController;
use App\Http\Controllers\Member\LeaderController;
use App\Http\Controllers\Member\MemberController;

// Route::get('/', [MemberController::class, 'index'])->name('/');
Route::get('/', [MainController::class, 'index'])->name('/');
Route::get('/login', [MainController::class, 'index'])->name('login');
Route::post('/login/auth', [MainController::class, 'login'])->name('login.auth');
Route::post('/login/member', [MainController::class, 'login_member'])->name('login.member');
Route::get('/logout', [MainController::class, 'logout'])->name('logout');
Route::get('/logout_member', [MainController::class, 'logout_member'])->name('logout.member');

Route::middleware(LeaderMiddleware::class)->group(function () {
    Route::get('/dashboard', [LeaderController::class, 'index'])->name('dashboard');
});

Route::middleware(MemberMiddleware::class)->group(function () {
     Route::get('/home', [MemberController::class, 'index'])->name('home');
});