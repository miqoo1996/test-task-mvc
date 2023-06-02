<?php

use App\Controllers\HomeController;
use App\Core\Routers\Route;

Route::get('/', [HomeController::class, 'index']);
Route::post('/', [HomeController::class, 'saveText']);