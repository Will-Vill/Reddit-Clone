<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Request;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiController;

Route::get('/index', function () {
    return view('index');
})->name('index');

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