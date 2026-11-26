<?php

use App\Http\Controllers\NameGeneratorController;
use Illuminate\Support\Facades\Route;



Route::get('/', [NameGeneratorController::class, 'index'])->name('generator.index');
Route::post('/generate', [NameGeneratorController::class, 'generate'])->name('generator.generate');