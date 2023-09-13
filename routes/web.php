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
use App\Http\Controllers\User\ScanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;
use Wave\Facades\Wave;

// Authentication routes
Auth::routes();

Route::get('/logs', [\App\Http\Controllers\LogController::class, 'showLogs']);
Route::get('/clear-logs', [\App\Http\Controllers\LogController::class, 'clearLogs'])->name('clear-logs');

//User Routes
Route::group(['middleware' => 'auth'], function () {
    Route::resource('domains', DomainController::class);
    Route::get('scans', [ScanController::class, 'index'])->name('scans.index');
    Route::get('scans/create', [ScanController::class, 'create'])->name('scans.create');
    Route::post('scans/store', [ScanController::class, 'store'])->name('scans.store');
    Route::get('scans/report', [ScanController::class, 'getReport'])->name('scans.report');

});


// Voyager Admin routes
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

// Wave routes
Wave::routes();


