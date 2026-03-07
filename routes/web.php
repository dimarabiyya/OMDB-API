<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

Route::get('/', [MovieController::class, 'index'])->name('home');

// Route AJAX untuk search dan detail
Route::get('/movie/search', [MovieController::class, 'search'])->name('movie.search');
Route::get('/movie/detail', [MovieController::class, 'detail'])->name('movie.detail');
