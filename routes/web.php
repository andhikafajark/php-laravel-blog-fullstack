<?php

use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::name('pages.')->group(function () {
    Route::get('/', [PagesController::class, 'index'])->name('index');
    Route::get('blog', [PagesController::class, 'blog'])->name('blog');
    Route::get('contact', [PagesController::class, 'contact'])->name('contact');
    Route::get('post', [PagesController::class, 'post'])->name('post');
});
