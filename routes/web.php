<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyController;
use App\Http\Controllers\MailController;

Route::get('/', [MyController::class, 'index'])
->name('test');
Route::get('users', [MyController::class, 'users'])
->name('users');
Route::get('mail', [MailController::class, 'index'])
->name('mail');
Route::post('mail', [MailController::class, 'send']);