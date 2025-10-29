<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Leader\LeaderController;
use App\Http\Controllers\Leader\LeaderSuggestionController;
use App\Http\Controllers\Leader\LeaderMemberController;
use App\Http\Controllers\Member\SuggestionController;

Route::get('/stats/member', [LeaderController::class, 'memberStats'])->name('member.stats');
Route::get('/stats/suggestion', [LeaderController::class, 'stats'])->name('suggestion.stats');
Route::get('/stats/not-submit', [LeaderController::class, 'notSubmit'])->name('notSubmit.stats');
Route::get('/stats/not-sign', [LeaderController::class, 'notSign'])->name('notSign.stats');


Route::get('/leader-suggestions-data', [LeaderSuggestionController::class, 'getSuggestions'])->name('leader.suggestions.data');
Route::get('/leader-suggestions-data-month', [LeaderSuggestionController::class, 'getSuggestionsMonth'])->name('leader.suggestions.data.month');
Route::get('/leader/suggestion/not-submit/data', [LeaderSuggestionController::class, 'notSubmitData'])->name('leader.suggestion.notSubmit.data');
Route::get('/leader/suggestion/not-sign/data', [LeaderSuggestionController::class, 'notSignData'])->name('leader.suggestion.notSign.data');
Route::get('/suggestions-data', [SuggestionController::class, 'getSuggestions'])->name('suggestions.data');


Route::get('/checkMember', [LeaderMemberController::class, 'checkMember'])->name('checkMember');
Route::get('/checkMember/{nik}', [LeaderMemberController::class, 'checkMemberByNik'])->name('checkMember.byNik');
