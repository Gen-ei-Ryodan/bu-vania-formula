<?php

use App\Http\Controllers\Api\ConceptController;
use App\Http\Controllers\Api\ProductionController;
use App\Http\Controllers\Api\ProductionGroupController;
use App\Http\Controllers\Api\ProductionGroupItemController;
use App\Http\Controllers\Api\ProductionTabController;
use App\Http\Controllers\Api\ProductionTabItemController;
use Illuminate\Support\Facades\Route;

Route::post('/concepts', [ConceptController::class, 'store']);

Route::post('/productions', [ProductionController::class, 'store']);
Route::post('/productions/{production}/generate', [ProductionController::class, 'generate']);

Route::post('/productions/{production}/groups', [ProductionGroupController::class, 'store']);
Route::post('/groups/{group}/items', [ProductionGroupItemController::class, 'store']);

Route::post('/productions/{production}/tabs', [ProductionTabController::class, 'store']);
Route::post('/tabs/{tab}/items', [ProductionTabItemController::class, 'store']);
