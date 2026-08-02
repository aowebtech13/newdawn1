<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;

Route::get('/', [FormController::class, 'show']);
Route::post('/submit', [FormController::class, 'submit']);
