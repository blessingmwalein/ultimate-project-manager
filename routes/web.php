<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/invites/accept', [\App\Http\Controllers\Companies\InviteController::class, 'accept'])->name('invites.accept');
