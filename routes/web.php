<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Link\LinkController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{slug}', [LinkController::class, 'getLinkBySlug']);
