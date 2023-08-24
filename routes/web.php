<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\User\DomainController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;
use Wave\Facades\Wave;

// Authentication routes
Auth::routes();

//User Routes
Route::group(['middleware' => 'auth'], function () {
    Route::resource('domains', DomainController::class);
});


// Voyager Admin routes
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

// Wave routes
Wave::routes();


