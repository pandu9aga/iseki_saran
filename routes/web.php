<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LeaderMiddleware;
use App\Http\Middleware\MemberMiddleware;
use App\Http\Controllers\MainController;
use App\Http\Controllers\Leader\LeaderController;
use App\Http\Controllers\Leader\LeaderSuggestionController;
use App\Http\Controllers\Leader\UserController;
use App\Http\Controllers\Leader\LeaderMemberController;
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

    Route::get('/user', [UserController::class, 'index'])->name('leader.users.index');
    Route::post('/user/store', [UserController::class, 'store'])->name('leader.users.store');
    Route::put('/user/update/{id}', [UserController::class, 'update'])->name('leader.users.update');
    Route::delete('/user/delete/{id}', [UserController::class, 'destroy'])->name('leader.users.destroy');

    Route::get('/leader/suggestion', [LeaderSuggestionController::class, 'index'])->name('leader.suggestion');
    Route::get('/leader/suggestion/filter', [LeaderSuggestionController::class, 'filter'])->name('leader.suggestion.filter');
    Route::get('/leader/suggestion/not-submit', [LeaderSuggestionController::class, 'notSubmit'])->name('leader.suggestion.notSubmit');
    Route::get('/leader/suggestion/not-submit/filter', [LeaderSuggestionController::class, 'notSubmitFilter'])->name('leader.suggestion.notSubmit.filter');
    Route::get('/leader/suggestion/not-sign', [LeaderSuggestionController::class, 'notSign'])->name('leader.suggestion.notSign');
    Route::get('/leader/suggestion/not-sign/filter', [LeaderSuggestionController::class, 'notSignFilter'])->name('leader.suggestion.notSign.filter');
    Route::delete('/leader/suggestion/delete/{Id_Suggestion}', [LeaderSuggestionController::class, 'destroy'])->name('leader.suggestion.destroy');
    Route::get('/leader/suggestion/{id}', [LeaderSuggestionController::class, 'show'])->name('leader.suggestion.show');
    Route::post('/leader/suggestion/{id}/update-field', [LeaderSuggestionController::class, 'updateField'])->name('leader.suggestion.updateField');
    Route::get('/leader/suggestion/{id}/export', [LeaderSuggestionController::class, 'export'])->name('leader.suggestion.export');
    Route::get('/suggestions/export-all', [LeaderSuggestionController::class, 'exportAll'])->name('leader.suggestion.exportAll');

    Route::get('/member', [LeaderMemberController::class, 'index'])->name('member');
});

Route::middleware(MemberMiddleware::class)->group(function () {
    Route::get('/home', [MemberController::class, 'index'])->name('home');

    Route::post('/suggestion', [SuggestionController::class, 'store'])->name('suggestion.store');
    Route::get('/suggestion/create', [SuggestionController::class, 'create'])->name('suggestion.create');
    Route::post('/suggestion/insert', [SuggestionController::class, 'insert'])->name('suggestion.insert');
    Route::get('/suggestion/{id}', [SuggestionController::class, 'show'])->name('suggestion.show');
    Route::post('/suggestion/{id}/update-field', [SuggestionController::class, 'updateField'])->name('suggestion.updateField');
    Route::delete('/suggestion/delete/{Id_Suggestion}', [SuggestionController::class, 'destroy'])->name('suggestion.destroy');
});