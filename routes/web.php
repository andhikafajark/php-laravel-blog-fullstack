<?php

use App\Http\Controllers\Admin\Blog\PostController;
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
    Route::get('post/{post:slug}', [PagesController::class, 'post'])->name('post');

    // Post
    Route::name('post.')->group(function () {
        Route::get('post/{post:slug}/comment', [PostController::class, 'getAllComment'])->name('comment.get-all');
        Route::post('post/{post:slug}/comment', [PostController::class, 'storeComment'])->name('comment.store');
        Route::put('post/{post:slug}/comment/{comment:uuid}', [PostController::class, 'updateComment'])->name('comment.update');
        Route::delete('post/{post:slug}/comment/{comment:uuid}', [PostController::class, 'destroyComment'])->name('comment.destroy');
        Route::post('post/{post:slug}/comment/{comment:uuid}/report', [PostController::class, 'reportComment'])->name('comment.report');
    });
});

Route::name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login.show');
    Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Blog
    Route::name('blog.')->group(function () {
        // Post
        Route::resource('post', PostController::class, [
            'except' => ['show']
        ]);
    });

    // Reference
    Route::name('reference.')->group(function () {
        // Category
        Route::resource('category', CategoryController::class, [
            'except' => ['show']
        ]);
    });
});
