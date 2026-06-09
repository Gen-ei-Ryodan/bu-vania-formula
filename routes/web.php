<?php

use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ConceptController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ItemController;
use App\Http\Controllers\Dashboard\LocationController;
use App\Http\Controllers\Dashboard\ProductionController;
use App\Http\Controllers\Dashboard\ReportController;
use App\Http\Controllers\Dashboard\TreatmentProductionController;
use App\Http\Controllers\Dashboard\UnitController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('/units', UnitController::class)->except(['show']);
Route::resource('/categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('/items', ItemController::class)->except(['show']);
Route::resource('/concepts', ConceptController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
Route::resource('/locations', LocationController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
Route::post('/locations/{location}/cages', [LocationController::class, 'storeCage'])->name('locations.cages.store');
Route::put('/cages/{cage}', [LocationController::class, 'updateCage'])->name('cages.update');
Route::delete('/cages/{cage}', [LocationController::class, 'destroyCage'])->name('cages.destroy');
Route::resource('/productions', ProductionController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
Route::post('/productions/duplicate', [ProductionController::class, 'duplicate'])->name('productions.duplicate');

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
Route::post('/treatments/duplicate', [TreatmentProductionController::class, 'duplicate'])->name('treatments.duplicate');
Route::get('/treatments/{production}/pdf', [TreatmentProductionController::class, 'pdf'])->name('treatments.pdf');
Route::post('/treatments/{production}/groups', [TreatmentProductionController::class, 'storeGroup'])->name('treatments.groups.store');
Route::delete('/treatments/groups/{group}', [TreatmentProductionController::class, 'destroyGroup'])->name('treatments.groups.destroy');
Route::post('/treatments/groups/{group}/items', [TreatmentProductionController::class, 'storeGroupItem'])->name('treatments.groups.items.store');
Route::delete('/treatments/group-items/{groupItem}', [TreatmentProductionController::class, 'destroyGroupItem'])->name('treatments.groups.items.destroy');
Route::post('/treatments/{production}/tabs', [TreatmentProductionController::class, 'storeTab'])->name('treatments.tabs.store');
Route::delete('/treatments/tabs/{tab}', [TreatmentProductionController::class, 'destroyTab'])->name('treatments.tabs.destroy');
Route::post('/treatments/tabs/{tab}/items', [TreatmentProductionController::class, 'storeTabItem'])->name('treatments.tabs.items.store');
Route::delete('/treatments/tab-items/{tabItem}', [TreatmentProductionController::class, 'destroyTabItem'])->name('treatments.tabs.items.destroy');

// Update group item & tab item weight (edit berat)
Route::put('/group-items/{groupItem}', [ProductionController::class, 'updateGroupItem'])->name('groups.items.update');
Route::put('/tab-items/{tabItem}', [ProductionController::class, 'updateTabItem'])->name('tabs.items.update');
Route::put('/treatments/group-items/{groupItem}', [TreatmentProductionController::class, 'updateGroupItem'])->name('treatments.groups.items.update');
Route::put('/treatments/tab-items/{tabItem}', [TreatmentProductionController::class, 'updateTabItem'])->name('treatments.tabs.items.update');

// API: get cages by location for form dropdown
Route::get('/api/locations/{location}/cages', function (\App\Models\Location $location) {
    return $location->cages()->orderBy('name')->get(['id', 'name']);
})->name('api.locations.cages');

// API: get production data for pre-fill
Route::get('/api/productions/{production}/data', function (\App\Models\Production $production) {
    return response()->json([
        'name' => $production->name,
        'location' => $production->location,
        'cage' => $production->cage,
        'concept_id' => $production->concept_id,
        'target_weight_kg' => $production->target_weight_kg,
        'start_date' => $production->start_date?->format('Y-m-d'),
        'duration_days' => $production->duration_days,
        'is_forever' => $production->is_forever,
        'mix_date' => $production->mix_date?->format('Y-m-d'),
        'notes' => $production->notes,
        'treatment_day' => $production->treatment_day,
        'treatment_time' => $production->treatment_time,
        'treatment_duration_days' => $production->treatment_duration_days,
        'production_type' => $production->production_type,
    ]);
})->name('api.productions.data');

// Reports
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
