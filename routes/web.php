<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Reference\CategoryController;
use App\Http\Controllers\AuthController;
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
    Route::get('contact', [PagesController::class, 'contact'])->name('contact');
    Route::get('post', [PagesController::class, 'post'])->name('post');
});

Route::name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login.show');
    Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Reference
    Route::name('reference.')->group(function () {
        // Category
        Route::resource('category', CategoryController::class, [
            'except' => ['show']
        ]);
    });
});
