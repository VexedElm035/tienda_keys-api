<?php
use App\Models\Genre;
use App\Models\Game;
use App\Models\GameKey;



use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GameController;
use App\Http\Controllers\GameKeyController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/testroute/{id}',function($id){
    $genre = Genre::where('id',$id)->get();
    return [
        'message' => $genre,
    ];
});

Route::get('/testeo/{id}', function($id){
    $game = Game::where('id',$id)->get();
    foreach ($game as $v){
    echo "game:".$v->name.'<br>';
    }
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
