<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ReplyController as AdminReplyController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

// 掲示板トップ
Route::get('/', [PostController::class, 'index'])->name('posts.index');

// ログイン後のダッシュボードは掲示板トップにリダイレクト
Route::get('/dashboard', function () {
    return redirect()->route('posts.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // プロフィール（Breeze 標準）
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 掲示板: 投稿作成・保存・返信・返信編集/削除
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/replies', [PostController::class, 'replyStore'])->name('posts.reply');

    Route::get('/replies/{reply}/edit', [ReplyController::class, 'edit'])->name('replies.edit');
    Route::put('/replies/{reply}', [ReplyController::class, 'update'])->name('replies.update');
    Route::delete('/replies/{reply}', [ReplyController::class, 'destroy'])->name('replies.destroy');

    // 管理画面
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('can:admin')
        ->group(function () {
            // ダッシュボード
            Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

            // 投稿管理
            Route::get('/posts', [AdminPostController::class, 'index'])->name('posts.index');
            Route::get('/posts/{post}/edit', [AdminPostController::class, 'edit'])->name('posts.edit');
            Route::put('/posts/{post}', [AdminPostController::class, 'update'])->name('posts.update');
            Route::patch('/posts/{post}/toggle-hidden', [AdminPostController::class, 'toggleHidden'])->name('posts.toggle-hidden');
            Route::delete('/posts/{post}', [AdminPostController::class, 'destroy'])->name('posts.destroy');

            // ユーザー管理
            Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
            Route::patch('/users/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('users.toggle-admin');

            // 返信管理
            Route::get('/replies', [AdminReplyController::class, 'index'])->name('replies.index');
            Route::delete('/replies/{reply}', [AdminReplyController::class, 'destroy'])->name('replies.destroy');
        });
});

Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

require __DIR__.'/auth.php';
