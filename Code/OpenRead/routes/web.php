<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\ReaderController;
use App\Http\Controllers\User\WriterController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Auth\ChangePasswordController;

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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/genre/{genre_id?}', [HomeController::class, 'genre'])->name('genre');
Route::get('/search', [HomeController::class, 'search'])->name('search');

Auth::routes(['verify' => false]);

Route::get('/password/change', [ChangePasswordController::class, 'index']);
Route::post('/password/change', [ChangePasswordController::class, 'change'])->name('change-password');

Route::get('/profile', [ProfileController::class, 'index'])->name('show-profile');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('show-edit-profile');
Route::get('/profile/image/{name?}', [ProfileController::class, 'preview'])->name('preview-image-profile');
Route::post('/profile/image', [ProfileController::class, 'upload'])->name('upload-image-profile');
Route::post('/profile/edit', [ProfileController::class, 'save'])->name('save-edit-profile');

Route::get('/stories/write', [WriterController::class, 'index'])->name('write-menu');
Route::get('/stories/create', [WriterController::class, 'create'])->name('show-create-story');
//Route::get('/stories/edit', [WriterController::class, 'edit']);
Route::post('/stories/save', [WriterController::class, 'save'])->name('save-story');

Route::get('/stories/{story_id?}', [ReaderController::class, 'index']);
Route::get('/chapters/{chapter_id?}', [ReaderController::class, 'chapter']);
Route::post('/comment', [ReaderController::class, 'postComment'])->name('save-comment');