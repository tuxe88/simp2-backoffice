<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/dashboard', [App\Http\Controllers\Controller::class, 'dashboard'])->name('dashboard')->middleware('auth');

Route::get('/companies', [App\Http\Controllers\Controller::class, 'companies'])->name('companies')->middleware('auth');
Route::post('/companies',[App\Http\Controllers\Controller::class, 'companies'])->name('companies')->middleware('auth');
Route::put('/companies',[App\Http\Controllers\Controller::class, 'companies'])->name('companies')->middleware('auth');

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
