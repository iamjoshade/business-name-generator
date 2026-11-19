<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;



Route::get('/', [HomeController::class, 'index'])->name('generator.index');
Route::post('/generate', [HomeController::class, 'generate'])->name('generator.generate');

// Route::get('/', function () {
//     return view('welcome');
// });