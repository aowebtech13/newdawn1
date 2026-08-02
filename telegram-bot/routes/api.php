<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;

Route::post('/submit', [FormController::class, 'apiSubmit']);
