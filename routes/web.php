<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AuthController;

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Gunakan string jika Controller Anda tidak menggunakan Namespace di route
Route::get('login', 'AuthController@showLogin')->name('login');
Route::post('login', 'AuthController@login');
Route::post('logout', 'AuthController@logout')->name('logout');



Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'MovieController@index')->name('home');
    Route::get('/movie/detail', 'MovieController@detail')->name('movie.detail');
    Route::get('/movie/search', 'MovieController@search')->name('movie.search');
});

Route::group(['middleware' => 'auth'], function () {
    Route::post('/movie/favorite', 'MovieController@toggleFavorite')->name('movie.favorite');
    Route::get('/favorites', 'MovieController@listFavorites')->name('movie.favorites.list');
    
});
