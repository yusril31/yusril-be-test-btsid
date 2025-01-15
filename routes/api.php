<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ChecklistItemController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([

    'middleware' => 'api',

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

    Route::get('/checklist', [ChecklistController::class, 'getChecklist']);
    Route::post('/checklist', [ChecklistController::class, 'createChecklist']);
    Route::post('/checklist/{id}', [ChecklistController::class, 'deleteChecklist']);
    
    Route::get('/checklist/{id}/item', [ChecklistItemController::class, 'detailChecklist']);
    Route::post('/checklist/{id}/item', [ChecklistItemController::class, 'createChecklistItem']);
    Route::get('/checklist/{id}/item/{itemId}', [ChecklistItemController::class, 'getChecklistItem']);
    Route::put('/checklist/{id}/item/{itemId}', [ChecklistItemController::class, 'updateStatusChecklistItem']);
    Route::delete('/checklist/{id}/item/{itemId}', [ChecklistItemController::class, 'deleteChecklistItem']);
    Route::put('/checklist/{id}/item/rename/{itemId}', [ChecklistItemController::class, 'renameChecklistItem']);

});
