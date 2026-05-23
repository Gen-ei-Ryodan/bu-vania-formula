<?php

use App\Http\Controllers\Dashboard\ConceptController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ItemController;
use App\Http\Controllers\Dashboard\ProductionController;
use App\Http\Controllers\Dashboard\TreatmentProductionController;
use App\Http\Controllers\Dashboard\UnitController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('/units', UnitController::class)->except(['show']);
Route::resource('/items', ItemController::class)->except(['show']);
Route::resource('/concepts', ConceptController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
Route::resource('/productions', ProductionController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);

Route::get('/productions/{production}/pdf', [ProductionController::class, 'pdf'])->name('productions.pdf');
Route::post('/productions/{production}/groups', [ProductionController::class, 'storeGroup'])->name('productions.groups.store');
Route::delete('/groups/{group}', [ProductionController::class, 'destroyGroup'])->name('groups.destroy');
Route::post('/groups/{group}/items', [ProductionController::class, 'storeGroupItem'])->name('groups.items.store');
Route::delete('/group-items/{groupItem}', [ProductionController::class, 'destroyGroupItem'])->name('groups.items.destroy');
Route::post('/productions/{production}/tabs', [ProductionController::class, 'storeTab'])->name('productions.tabs.store');
Route::delete('/tabs/{tab}', [ProductionController::class, 'destroyTab'])->name('tabs.destroy');
Route::post('/tabs/{tab}/items', [ProductionController::class, 'storeTabItem'])->name('tabs.items.store');
Route::delete('/tab-items/{tabItem}', [ProductionController::class, 'destroyTabItem'])->name('tabs.items.destroy');

Route::resource('/treatments', TreatmentProductionController::class, ['parameters' => ['treatments' => 'production']])->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
Route::get('/treatments/{production}/pdf', [TreatmentProductionController::class, 'pdf'])->name('treatments.pdf');
Route::post('/treatments/{production}/groups', [TreatmentProductionController::class, 'storeGroup'])->name('treatments.groups.store');
Route::delete('/treatments/groups/{group}', [TreatmentProductionController::class, 'destroyGroup'])->name('treatments.groups.destroy');
Route::post('/treatments/groups/{group}/items', [TreatmentProductionController::class, 'storeGroupItem'])->name('treatments.groups.items.store');
Route::delete('/treatments/group-items/{groupItem}', [TreatmentProductionController::class, 'destroyGroupItem'])->name('treatments.groups.items.destroy');
Route::post('/treatments/{production}/tabs', [TreatmentProductionController::class, 'storeTab'])->name('treatments.tabs.store');
Route::delete('/treatments/tabs/{tab}', [TreatmentProductionController::class, 'destroyTab'])->name('treatments.tabs.destroy');
Route::post('/treatments/tabs/{tab}/items', [TreatmentProductionController::class, 'storeTabItem'])->name('treatments.tabs.items.store');
Route::delete('/treatments/tab-items/{tabItem}', [TreatmentProductionController::class, 'destroyTabItem'])->name('treatments.tabs.items.destroy');
