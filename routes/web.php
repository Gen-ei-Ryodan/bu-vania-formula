<?php

use App\Http\Controllers\Dashboard\ConceptController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ItemController;
use App\Http\Controllers\Dashboard\ProductionController;
use App\Http\Controllers\Dashboard\UnitController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('/units', UnitController::class)->except(['show']);
Route::resource('/items', ItemController::class)->except(['show']);
Route::resource('/concepts', ConceptController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
Route::resource('/productions', ProductionController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

Route::post('/productions/{production}/generate', [ProductionController::class, 'generate'])->name('productions.generate');
Route::get('/productions/{production}/pdf', [ProductionController::class, 'pdf'])->name('productions.pdf');
Route::post('/productions/{production}/groups', [ProductionController::class, 'storeGroup'])->name('productions.groups.store');
Route::delete('/groups/{group}', [ProductionController::class, 'destroyGroup'])->name('groups.destroy');
Route::post('/groups/{group}/items', [ProductionController::class, 'storeGroupItem'])->name('groups.items.store');
Route::delete('/group-items/{groupItem}', [ProductionController::class, 'destroyGroupItem'])->name('groups.items.destroy');
Route::post('/productions/{production}/tabs', [ProductionController::class, 'storeTab'])->name('productions.tabs.store');
Route::delete('/tabs/{tab}', [ProductionController::class, 'destroyTab'])->name('tabs.destroy');
Route::post('/tabs/{tab}/items', [ProductionController::class, 'storeTabItem'])->name('tabs.items.store');
Route::delete('/tab-items/{tabItem}', [ProductionController::class, 'destroyTabItem'])->name('tabs.items.destroy');
