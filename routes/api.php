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
    Route::get('mutashabih-groups', [MutashabihsGroupController::class,'list']);
    Route::get('mutashabih-groups/{group}', [MutashabihsGroupController::class,'get']);
    Route::post('mutashabih-groups', [MutashabihsGroupController::class,'create']);
    Route::put('mutashabih-groups/{group}', [MutashabihsGroupController::class,'update']);
    Route::DELETE('mutashabih-groups/{group}', [MutashabihsGroupController::class,'delete']);

    Route::get('mutashabih-groups/mutashabih/{mutashabih}', [MutashabihController::class,'get']);
    Route::get('mutashabih-groups/{group}/mutashabih', [MutashabihController::class,'getAll']);
    Route::post('mutashabih-groups/{group}/mutashabih',[MutashabihController::class,'create']);
    Route::DELETE('mutashabih-groups/{group_id}/mutashabih/{id}', [MutashabihController::class,'delete']);
});



