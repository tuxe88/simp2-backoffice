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

Route::get('/dashboard', [App\Http\Controllers\Controller::class, 'dashboard'])->name('dashboard')->middleware('auth', 'role:admin');

Route::get('/companies', [App\Http\Controllers\Controller::class, 'companies'])->name('companies')->middleware('auth');
Route::post('/companies',[App\Http\Controllers\Controller::class, 'companies'])->name('companies')->middleware('auth');
Route::put('/companies',[App\Http\Controllers\Controller::class, 'companies'])->name('companies')->middleware('auth');

Route::get('/users', [App\Http\Controllers\Controller::class, 'users'])->name('users')->middleware('auth');
Route::post('/users',[App\Http\Controllers\Controller::class, 'users'])->name('users')->middleware('auth');
Route::put('/users',[App\Http\Controllers\Controller::class, 'users'])->name('users')->middleware('auth');


Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
