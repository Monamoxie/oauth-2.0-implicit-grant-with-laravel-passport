<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

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
// dd(Hash::make('123456'));
Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])
->get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])
->get('/dashboard/oauth/approve_request', [App\Http\Controllers\DashboardController::class, 'approveRequest'])
->name('approve_request');

Route::middleware(['auth:sanctum', 'verified'])
->get('/dashboard/oauth/callback', [App\Http\Controllers\DashboardController::class, 'requestCallback'])
->name('request_callback');

Route::middleware(['auth:sanctum', 'verified'])
->get('/dashboard/oauth/refresh', [App\Http\Controllers\DashboardController::class, 'refreshToken'])
->name('refresh_token');

