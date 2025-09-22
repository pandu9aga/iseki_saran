<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LeaderMiddleware;
use App\Http\Middleware\MemberMiddleware;
use App\Http\Controllers\MainController;
use App\Http\Controllers\Member\LeaderController;
use App\Http\Controllers\Member\MemberController;
use App\Http\Controllers\Member\SuggestionController;

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

    Route::get('/suggestion', [SuggestionController::class, 'index'])->name('suggestion');
    Route::get('/suggestion/add', [SuggestionController::class, 'add'])->name('suggestion.add');
    Route::post('/suggestion/create', [SuggestionController::class, 'create'])->name('suggestion.create');
    Route::get('/suggestion/edit/{Id_Suggestion}', [SuggestionController::class, 'edit'])->name('suggestion.edit');
    Route::put('/suggestion/update/{Id_Suggestion}', [SuggestionController::class, 'update'])->name('suggestion.update');
    Route::delete('/suggestion/delete/{Id_Suggestion}', [SuggestionController::class, 'destroy'])->name('suggestion.destroy');
    Route::post('/suggestion/import', [SuggestionController::class, 'import'])->name('suggestion.import');
});