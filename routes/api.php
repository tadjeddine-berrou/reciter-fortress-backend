<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MutashabihsGroupController;
use App\Http\Controllers\MutashabihController;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group( function () {
    Route::get('mutashabih-groups', [MutashabihsGroupController::class,'get']);
    Route::get('mutashabih-groups/{id}', [MutashabihsGroupController::class,'get']);
    Route::post('mutashabih-groups', [MutashabihsGroupController::class,'create']);
    Route::put('mutashabih-groups/{id}', [MutashabihsGroupController::class,'update']);
    Route::DELETE('mutashabih-groups/{id}', [MutashabihsGroupController::class,'delete']);

    Route::get('mutashabih-groups/{group_id}/mutashabih', [MutashabihController::class,'get']);
    Route::get('mutashabih-groups/{group_id}/mutashabih/{id}', [MutashabihController::class,'get']);
    Route::post('mutashabih-groups/{id}/mutashabih',[MutashabihController::class,'create']);
    Route::DELETE('mutashabih-groups/{group_id}/mutashabih/{id}', [MutashabihController::class,'delete']);
});



