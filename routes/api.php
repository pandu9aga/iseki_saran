<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Leader\LeaderController;
use App\Http\Controllers\Leader\LeaderSuggestionController;
use App\Http\Controllers\Member\SuggestionController;

Route::get('/stats/member', [LeaderController::class, 'memberStats'])->name('member.stats');
Route::get('/stats/suggestion', [LeaderController::class, 'stats'])->name('suggestion.stats');


Route::get('/leader-suggestions-data', [LeaderSuggestionController::class, 'getSuggestions'])->name('leader.suggestions.data');
Route::get('/suggestions-data', [SuggestionController::class, 'getSuggestions'])->name('suggestions.data');