<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\SuggestionController;

Route::get('/suggestions-data', [SuggestionController::class, 'getSuggestions'])->name('suggestions.data');