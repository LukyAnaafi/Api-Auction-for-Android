<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuctionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login',[\App\Http\Controllers\Api\AuthController::class,'login']);
Route::post('/register',[\App\Http\Controllers\Api\AuthController::class,'register']);


Route::get('/itemAuction',[\App\Http\Controllers\Api\AuctionController::class,'index']);



Route::group(['middleware' => 'auth:sanctum'],function(){
    Route::get('/user',[AuthController::class]);
    // Route::post('/insertlelang',[AuctionController::class, 'insertData'])->middleware("admin");
    
    Route::post('/insertlelang',[AuctionController::class, 'insertData'])->middleware("admin");
    
});

//Route::post('/insertlelang',[AuctionController::class, 'insertData']);

Route::put('/update-user/{id}',[\App\Http\Controllers\Api\AuthController::class,'update']);
Route::post('/bid',[\App\Http\Controllers\Api\AuctionController::class,'bids']);
Route::get('/auction-detail/{id_auction}',[\App\Http\Controllers\Api\AuctionController::class,'detailAuction']);
Route::get('/history',[\App\Http\Controllers\Api\AuctionController::class,'history_auction']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
