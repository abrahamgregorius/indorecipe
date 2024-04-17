<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RecipeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/category')->group(function() {
    Route::get('/', [CategoryController::class, 'all_names']);
    Route::post('/create', [CategoryController::class, 'store']);
    Route::get('/{short}', [CategoryController::class, 'show']);
    Route::post('/{short}/update', [CategoryController::class, 'update']);
    Route::post('/{short}/delete', [CategoryController::class , 'destroy']);
});

Route::prefix('/recipe')->group(function() {
    Route::get('/', [RecipeController::class, 'index']);
    Route::get('/{short}', [RecipeController::class, 'show']);
    Route::post('/', [RecipeController::class, 'store']);
});
Route::get('/best-recipes', [RecipeController::class, 'top']);
