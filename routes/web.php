<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandleController;

// Set candles index as homepage
Route::get('/', [CandleController::class, 'index'])->name('candles.index');
