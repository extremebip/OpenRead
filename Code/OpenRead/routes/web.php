<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\User\ProfileController;

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

Route::get('/home', [HomeController::class, 'index'])->name('home');

Auth::routes(['verify' => false]);

Route::get('/password/change', [ChangePasswordController::class, 'index']);
Route::post('/password/change', [ChangePasswordController::class, 'change'])->name('change-password');

Route::get('/profile', [ProfileController::class, 'index'])->name('show-profile');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('show-edit-profile');
Route::post('/profile/edit', [ProfileController::class, 'save'])->name('save-edit-profile');