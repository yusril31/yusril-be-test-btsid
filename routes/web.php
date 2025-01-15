<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChecklistController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [ChecklistController::class, 'test']);