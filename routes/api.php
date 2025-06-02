<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

Route::apiResource('users', UserController::class)->only(['index', 'show']);


Route::get('/test', [TestController::class, 'index']);
