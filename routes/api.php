<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameKeyController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\IGDBController;

Route::apiResource('games', GameController::class);
Route::apiResource('gamekeys', GameKeyController::class);
Route::apiResource('genres', GenreController::class);
Route::apiResource('purchases', PurchaseController::class);
Route::apiResource('reviews', ReviewController::class);
Route::apiResource('sales', SaleController::class);
Route::apiResource('supports', SupportController::class);
Route::apiResource('users', UserController::class)->except(['store'])->middleware('auth:sanctum');
Route::post('/users', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);

Route::post('/upload', [FileUploadController::class, 'upload']);
Route::get('/uploads/{file}', [FileUploadController::class, 'getFile']);

Route::get('/igdb/games', [IGDBController::class, 'getPopularGames']);
Route::get('/igdb/search-games', [IGDBController::class, 'searchGames']);
Route::post('/igdb/sync-game', [IGDBController::class, 'syncGame']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');