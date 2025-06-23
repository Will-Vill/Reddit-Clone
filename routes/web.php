<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Request;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return redirect()->route('index');
});

//Rotte per la pagina di login, register e logout

Route::get('/login', [LoginController::class, 'get_login'])->name('login');
Route::post('/login', [LoginController::class, 'post_login']);
Route::get('/register', [LoginController::class, 'get_register'])->name('register');
Route::post('/register', [LoginController::class, 'post_register']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/check_username', [ApiController::class, 'check_username'])->name('check.username');
Route::get('/check_email', [ApiController::class, 'check_email'])->name('check.email');
Route::get('/index', [HomeController::class, 'index'])->name('index');

// Rotta per reddit e gemini api
Route::post('/reddit', [ApiController::class, 'fetchRedditPost']);
Route::post('/gemini', [ApiController::class, 'fetchGemini']);

// Rotta per post_voto
Route::post('/post_voto', [PostController::class, 'postVoto']);

// Rotta per postIniziali
Route::get('/post_iniziali', [PostController::class, 'postInizialiDB']);

// Rotta per postSingolo
Route::get('/post_singolo/reddit/{reddit_id}', [PostController::class, 'recuperaPostSingolo']);

// Rotta per postUtenti
Route::get('/post_utenti', [PostController::class, 'postUtenti']);

// Rotta per postCommenti
Route::post('/post_commenti', [PostController::class, 'postCommenti']);