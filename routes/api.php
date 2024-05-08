<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MutashabihsGroupController;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group( function () {
    
    Route::get('mutashabihs_groups', [MutashabihsGroupController::class,'get']);
    Route::get('mutashabihs_groups/{id}', [MutashabihsGroupController::class,'get']);
    Route::post('mutashabihs_groups', [MutashabihsGroupController::class,'create']);
    Route::DELETE('mutashabihs_groups/{id}', [MutashabihsGroupController::class,'delete']);
});

