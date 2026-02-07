<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;

// Route::get('/', function () {
//     return view('welcome');
// });



// Route untuk mengambil komentar berdasarkan ID buku
Route::get('/comments/{bookId}', [CommentController::class, 'index'])->name('comments.index');

// Route untuk submit komentar baru
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::resource('/', HomeController::class);

Route::post('/books/{id}/view', [BookController::class, 'incrementView']);
