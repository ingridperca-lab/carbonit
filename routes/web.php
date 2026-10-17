<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\SurveyController;
use App\Http\Middleware\AdminDomain;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

Route::domain('{adminHost}')->where(['adminHost' => implode('|', array_map(fn ($h) => preg_quote($h, '/'), config('admin.hosts')))])->middleware(AdminDomain::class)->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'loginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.submit');

    Route::middleware(EnsureAdmin::class)->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/data', [AdminController::class, 'data'])->name('data');
        Route::get('/raspunsuri', [AdminController::class, 'responses'])->name('responses');
        Route::delete('/raspunsuri/{response}', [AdminController::class, 'destroy'])->name('responses.destroy');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    });
});

Route::view('/', 'chestionar');
Route::post('/api/submit', [SurveyController::class, 'submit']);
Route::get('/api/stats', [SurveyController::class, 'stats']);
