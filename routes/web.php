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
    return Auth::check() ?  view('dashboard') :  view('auth.login');
});

Auth::routes(['register' => false]);

Route::get('/dashboard', [App\Http\Controllers\Controller::class, 'dashboard'])->name('dashboard')->middleware('auth', 'role:admin|user');

Route::get('/companies', [App\Http\Controllers\Controller::class, 'companies'])->name('companies')->middleware('auth', 'role:admin');
Route::post('/companies',[App\Http\Controllers\Controller::class, 'companies'])->name('companies')->middleware('auth', 'role:admin');
Route::put('/companies',[App\Http\Controllers\Controller::class, 'companies'])->name('companies')->middleware('auth', 'role:admin');

Route::get('/users', [App\Http\Controllers\Controller::class, 'users'])->name('users')->middleware('auth', 'role:admin');
Route::post('/users',[App\Http\Controllers\Controller::class, 'users'])->name('users')->middleware('auth', 'role:admin');
Route::put('/users',[App\Http\Controllers\Controller::class, 'users'])->name('users')->middleware('auth', 'role:admin');

Route::get('/debts', [App\Http\Controllers\Controller::class, 'debts'])->name('debts')->middleware('auth');
Route::post('/debts',[App\Http\Controllers\Controller::class, 'debts'])->name('debts')->middleware('auth');
Route::delete('/debts',[App\Http\Controllers\Controller::class, 'debts'])->name('debts')->middleware('auth');
//Route::put('/users',[App\Http\Controllers\Controller::class, 'users'])->name('users')->middleware('auth');

Route::get('/reverses', [App\Http\Controllers\Controller::class, 'reverses'])->name('reverses')->middleware('auth');

Route::get('/warnings', [App\Http\Controllers\Controller::class, 'warnings'])->name('warnings')->middleware('auth');

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/ajaxTable', [App\Http\Controllers\Controller::class, 'ajaxTable'])->name('ajax_table');
