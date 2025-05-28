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
use App\Http\Controllers\AdminController;
use App\Models\GameKey;
use App\Models\User;

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

Route::get('gamekeys-s', [GameKeyController::class, 'index2']);

Route::post('/upload', [FileUploadController::class, 'upload']);
Route::get('/uploads/{file}', [FileUploadController::class, 'getFile']);

Route::get('/igdb/games', [IGDBController::class, 'getPopularGames']);
Route::get('/igdb/search-games', [IGDBController::class, 'searchGames']);
Route::post('/igdb/sync-game', [IGDBController::class, 'syncGame']);

Route::get('/admin/earnings', [AdminController::class, 'getEarnings']);

// routes/api.php
Route::middleware(['auth:sanctum'])->group(function () {
    // Ruta para estadísticas del vendedor
    Route::get('/sellers/{seller}/stats', function (User $seller) {
        $totalEarnings = GameKey::where('seller_id', $seller->id)
            ->where('state', 'vendida')
            ->sum('price');
        
        // Asumiendo un 10% de comisión para la plataforma
        $sellerEarnings = $totalEarnings * 0.9;

        return response()->json([
            'total_earnings' => (float) $sellerEarnings,
            'total_sales' => (int) GameKey::where('seller_id', $seller->id)
                ->where('state', 'vendida')
                ->count(),
            'available_keys' => (int) GameKey::where('seller_id', $seller->id)
                ->where('state', 'disponible')
                ->count(),
            'sold_keys' => (int) GameKey::where('seller_id', $seller->id)
                ->where('state', 'vendida')
                ->count()
        ]);
    });
});



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');