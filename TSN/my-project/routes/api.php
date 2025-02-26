<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


    Route::get('/game/{id}', \App\Http\Controllers\Game\Details::class)->name('game.details');
    Route::patch('/game/{id}', \App\Http\Controllers\Game\Move::class)->name('game.move');

    //Route::post('/game', \App\Http\Controllers\Game\Create::class)->name('game.create');
    //Route::get('/game/join', \App\Http\Controllers\Game\Select::class)->name('game.select');
    //Route::patch('/game/join/{id}', \App\Http\Controllers\Game\Join::class)->name('game.join');

    //Route::post('/game/revenge/{id}', \App\Http\Controllers\Game\Revenge::class)->name('game.revenge');

