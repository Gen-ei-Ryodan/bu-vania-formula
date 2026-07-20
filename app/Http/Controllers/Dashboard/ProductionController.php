<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cage;
use App\Models\Concept;
use App\Models\Item;
use App\Models\Location;
use App\Models\Production;
use App\Models\ProductionGroup;
use App\Models\ProductionGroupItem;
use App\Models\ProductionTab;
use App\Models\ProductionTabItem;
use App\Models\Unit;
use App\Services\ProductionSnapshotService;
use App\Services\ProductionTabService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ProductionController extends Controller
{
    public function index(Request $request)
    {
        $query = Production::query()->biasa()->with('concept')->orderByDesc('id');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('concept_id')) {
            $query->where('concept_id', $request->concept_id);
        }
        if ($request->filled('start_date_from')) {
            $query->whereDate('start_date', '>=', $request->start_date_from);
        }
        if ($request->filled('start_date_to')) {
            $query->whereDate('start_date', '<=', $request->start_date_to);
        }
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
        if ($request->filled('cage')) {
            $query->where('cage', 'like', '%' . $request->cage . '%');
        }
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        return view('dashboard.productions.index', [
            'productions' => $query->get(),
            'concepts' => Concept::query()->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        $concepts = Concept::query()->with('items.item')->orderBy('name')->get();
        $conceptsData = [];
        foreach ($concepts as $c) {
            $items = [];
            foreach ($c->items as $i) {
                $items[] = [
                    'item' => $i->item?->name,
                    'weight_kg' => $i->weight_kg,
                    'percentage' => $i->percentage,
                ];
            }
            $conceptsData[$c->id] = [
                'name' => $c->name,
                'base_weight_kg' => $c->base_weight_kg,
                'items' => $items,
            ];
        }
        return view('dashboard.productions.create', [
            'concepts' => $concepts,
            'conceptsData' => $conceptsData,
            'units' => Unit::query()->orderBy('name')->get(),
            'locations' => Location::with('cages')->orderBy('name')->get(),
            'recentProductions' => Production::query()->biasa()->with('concept')->orderByDesc('id')->limit(50)->get(),
        ]);
    }

    public function store(Request $request, ProductionSnapshotService $service)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'cage' => ['nullable', 'string', 'max:255'],
            'treatment_day' => ['nullable', 'integer', 'min:1'],
            'treatment_time' => ['nullable', 'in:pagi,siang,malam,full'],
            'concept_id' => ['required', 'integer', 'exists:concepts,id'],
            'target_weight_value' => ['required', 'numeric', 'min:0.0001'],
            'target_weight_unit_id' => ['required', 'integer', 'exists:units,id'],
            'start_date' => ['nullable', 'date'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'is_forever' => ['nullable', 'boolean'],
            'mix_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $unit = Unit::query()->findOrFail((int) $validated['target_weight_unit_id']);
        $targetWeightKg = (float) $validated['target_weight_value'] * (float) $unit->conversion_to_kg;

        $production = DB::transaction(function () use ($validated, $targetWeightKg, $request, $service) {
            $production = Production::query()->create([
                'name' => $validated['name'],
                'location' => $validated['location'] ?? null,
                'cage' => $validated['cage'] ?? null,
                'treatment_day' => $validated['treatment_day'] ?? null,
                'treatment_time' => $validated['treatment_time'] ?? null,
                'concept_id' => (int) $validated['concept_id'],
                'target_weight_kg' => $targetWeightKg,
                'start_date' => $validated['start_date'] ?? null,
                'duration_days' => $validated['duration_days'] ?? null,
                'is_forever' => $request->boolean('is_forever'),
                'mix_date' => $validated['mix_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'production_type' => 'biasa',
                'is_active' => $request->has('is_active'),
            ]);

            $production->load('concept.items');
            $service->generate($production);

            return $production;
        });

        return redirect()->route('productions.show', $production)->with('ok', 'Produksi dibuat.');
    }

    public function show(Production $production)
    {
        $production->load([
            'concept',
            'items.item',
            'groups.items.item',
            'groups.items.inputUnit',
            'tabs.items.item',
            'tabs.items.inputUnit',
        ]);

        $tabUsed = $production->tabs->sum('input_weight_kg');
        $tabAvailable = (float) $production->target_weight_kg - (float) $tabUsed;

        return view('dashboard.productions.show', [
            'production' => $production,
            'items' => Item::query()->orderBy('name')->get(),
            'units' => Unit::query()->orderBy('name')->get(),
            'tabAvailableKg' => $tabAvailable,
        ]);
    }

    public function edit(Production $production)
    {
        $concepts = Concept::query()->with('items.item')->orderBy('name')->get();
        $conceptsData = [];
        foreach ($concepts as $c) {
            $items = [];
            foreach ($c->items as $i) {
                $items[] = [
                    'item' => $i->item?->name,
                    'weight_kg' => $i->weight_kg,
                    'percentage' => $i->percentage,
                ];
            }
            $conceptsData[$c->id] = [
                'name' => $c->name,
                'base_weight_kg' => $c->base_weight_kg,
                'items' => $items,
            ];
        }
        return view('dashboard.productions.edit', [
            'production' => $production,
            'concepts' => $concepts,
            'conceptsData' => $conceptsData,
            'units' => Unit::query()->orderBy('name')->get(),
            'locations' => Location::with('cages')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Production $production, ProductionSnapshotService $service)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'cage' => ['nullable', 'string', 'max:255'],
            'treatment_day' => ['nullable', 'integer', 'min:1'],
            'treatment_time' => ['nullable', 'in:pagi,siang,malam,full'],
            'concept_id' => ['required', 'integer', 'exists:concepts,id'],
            'target_weight_value' => ['required', 'numeric', 'min:0.0001'],
            'target_weight_unit_id' => ['required', 'integer', 'exists:units,id'],
            'start_date' => ['nullable', 'date'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'is_forever' => ['nullable', 'boolean'],
            'mix_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $unit = Unit::query()->findOrFail((int) $validated['target_weight_unit_id']);
        $targetWeightKg = (float) $validated['target_weight_value'] * (float) $unit->conversion_to_kg;

        DB::transaction(function () use ($production, $validated, $targetWeightKg, $request, $service) {
            foreach ($production->groups as $group) {
                $group->items()->delete();
                $group->delete();
            }
            foreach ($production->tabs as $tab) {
                $tab->items()->delete();
                $tab->delete();
            }

            $production->update([
                'name' => $validated['name'],
                'location' => $validated['location'] ?? null,
                'cage' => $validated['cage'] ?? null,
                'treatment_day' => $validated['treatment_day'] ?? null,
                'treatment_time' => $validated['treatment_time'] ?? null,
                'concept_id' => (int) $validated['concept_id'],
                'target_weight_kg' => $targetWeightKg,
                'start_date' => $validated['start_date'] ?? null,
                'duration_days' => $validated['duration_days'] ?? null,
                'is_forever' => $request->boolean('is_forever'),
                'mix_date' => $validated['mix_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'production_type' => 'biasa',
                'is_active' => $request->has('is_active'),
            ]);

            $production->load('concept.items');
            $service->regenerate($production);
        });

        return redirect()->route('productions.show', $production)->with('ok', 'Produksi diupdate.');
    }

    public function storeGroup(Request $request, Production $production)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        ProductionGroup::query()->create([
            'production_id' => $production->id,
            'name' => $validated['name'],
        ]);

        return redirect()->route('productions.show', $production)->with('ok', 'Golongan ditambah.');
    }

    public function storeGroupItem(Request $request, ProductionGroup $group)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'weight_value' => ['required', 'numeric', 'min:0.0001'],
            'weight_unit_id' => ['required', 'integer', 'exists:units,id'],
        ]);

        $unit = Unit::query()->findOrFail((int) $validated['weight_unit_id']);
        $weightKg = (float) $validated['weight_value'] * (float) $unit->conversion_to_kg;

        ProductionGroupItem::query()->create([
            'group_id' => $group->id,
            'item_id' => (int) $validated['item_id'],
            'weight_kg' => $weightKg,
            'is_dosis' => $request->boolean('is_dosis'),
            'weight_input_value' => (float) $validated['weight_value'],
            'weight_input_unit_id' => (int) $validated['weight_unit_id'],
        ]);

        return redirect()->route('productions.show', $group->production_id)->with('ok', 'Item golongan ditambah.');
    }

    public function storeTab(Request $request, Production $production, ProductionTabService $service)
    {
        if (! $production->items()->exists()) {
            throw ValidationException::withMessages([
                'production' => ['Production belum di-generate snapshot.'],
            ]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'input_weight_value' => ['required', 'numeric', 'min:0.0001'],
            'input_weight_unit_id' => ['required', 'integer', 'exists:units,id'],
        ]);

        $unit = Unit::query()->findOrFail((int) $validated['input_weight_unit_id']);
        $inputKg = (float) $validated['input_weight_value'] * (float) $unit->conversion_to_kg;

        $service->createTab($production, $validated['name'], $inputKg);

        return redirect()->to(route('productions.show', $production) . '#tab')->with('ok', 'TAB dibuat.');
    }

    public function storeTabItem(Request $request, ProductionTab $tab)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'weight_value' => ['required', 'numeric', 'min:0.0001'],
            'weight_unit_id' => ['required', 'integer', 'exists:units,id'],
        ]);

        $unit = Unit::query()->findOrFail((int) $validated['weight_unit_id']);
        $weightKg = (float) $validated['weight_value'] * (float) $unit->conversion_to_kg;

        ProductionTabItem::query()->create([
            'tab_id' => $tab->id,
            'item_id' => (int) $validated['item_id'],
            'weight_kg' => $weightKg,
            'is_dosis' => $request->boolean('is_dosis'),
            'weight_input_value' => (float) $validated['weight_value'],
            'weight_input_unit_id' => (int) $validated['weight_unit_id'],
        ]);

        return redirect()->to(route('productions.show', $tab->production_id) . '#tab')->with('ok', 'Item TAB ditambah.');
    }

    public function updateGroupItem(Request $request, ProductionGroupItem $groupItem)
    {
        $validated = $request->validate([
            'weight_value' => ['required', 'numeric', 'min:0.0001'],
            'weight_unit_id' => ['required', 'integer', 'exists:units,id'],
        ]);

        $unit = Unit::query()->findOrFail((int) $validated['weight_unit_id']);
        $weightKg = (float) $validated['weight_value'] * (float) $unit->conversion_to_kg;

        $groupItem->update([
            'weight_kg' => $weightKg,
            'weight_input_value' => (float) $validated['weight_value'],
            'weight_input_unit_id' => (int) $validated['weight_unit_id'],
        ]);

        return back()->with('ok', 'Berat item diupdate.');
    }

    public function updateTabItem(Request $request, ProductionTabItem $tabItem)
    {
        $validated = $request->validate([
            'weight_value' => ['required', 'numeric', 'min:0.0001'],
            'weight_unit_id' => ['required', 'integer', 'exists:units,id'],
        ]);

        $unit = Unit::query()->findOrFail((int) $validated['weight_unit_id']);
        $weightKg = (float) $validated['weight_value'] * (float) $unit->conversion_to_kg;

        $tabItem->update([
            'weight_kg' => $weightKg,
            'weight_input_value' => (float) $validated['weight_value'],
            'weight_input_unit_id' => (int) $validated['weight_unit_id'],
        ]);

        return back()->with('ok', 'Berat item diupdate.');
    }

    public function destroy(Production $production)
    {
        $production->delete();

        return redirect()->route('productions.index')->with('ok', 'Produksi dihapus.');
    }

    public function destroyGroup(ProductionGroup $group)
    {
        $productionId = $group->production_id;
        $group->delete();

        return redirect()->route('productions.show', $productionId)->with('ok', 'Golongan dihapus.');
    }

    public function destroyGroupItem(ProductionGroupItem $groupItem)
    {
        $productionId = $groupItem->group?->production_id;
        $groupItem->delete();

        return redirect()->route('productions.show', $productionId)->with('ok', 'Item golongan dihapus.');
    }

    public function destroyTab(ProductionTab $tab)
    {
        $productionId = $tab->production_id;
        $tab->delete();

        return redirect()->to(route('productions.show', $productionId) . '#tab')->with('ok', 'TAB dihapus.');
    }

    public function destroyTabItem(ProductionTabItem $tabItem)
    {
        $productionId = $tabItem->tab?->production_id;
        $tabItem->delete();

        return redirect()->to(route('productions.show', $productionId) . '#tab')->with('ok', 'Item TAB dihapus.');
    }

    public function pdf(Request $request, Production $production)
    {
        $production->load([
            'concept',
            'concept.items.item',
            'items.item',
            'groups.items.item',
            'groups.items.inputUnit',
            'tabs.items.item',
            'tabs.items.inputUnit',
        ]);

        if (! class_exists(Pdf::class)) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'PDF generator belum terpasang.');
        }

        $cards = (int) $request->query('cards', 9);
        if (! in_array($cards, [4, 6, 9])) {
            $cards = 9;
        }

        $pdf = Pdf::loadView('dashboard.productions.pdf', [
            'production' => $production,
            'totalCards' => $cards,
        ]);

        return $pdf->download('production-'.$production->id.'.pdf');
    }

    public function duplicate(Request $request)
    {
        $source = Production::query()->findOrFail($request->source_id);
        if ($source->production_type !== 'biasa') {
            abort(404);
        }

        // Deep copy using a transaction
        return DB::transaction(function () use ($source) {
            $source->load(['items', 'groups.items', 'tabs.items']);

            // Create new production
            $copy = $source->replicate();
            $copy->is_active = true;
            $copy->save();

            // Copy snapshot items
            foreach ($source->items as $si) {
                $copy->items()->create([
                    'item_id' => $si->item_id,
                    'weight_kg' => $si->weight_kg,
                    'percentage' => $si->percentage,
                    'source' => $si->source,
                ]);
            }

            // Copy groups
            foreach ($source->groups as $group) {
                $newGroup = $copy->groups()->create(['name' => $group->name]);
                foreach ($group->items as $gi) {
                    $newGroup->items()->create([
                        'item_id' => $gi->item_id,
                        'weight_kg' => $gi->weight_kg,
                        'weight_input_value' => $gi->weight_input_value,
                        'input_unit_id' => $gi->input_unit_id,
                        'is_dosis' => $gi->is_dosis,
                    ]);
                }
            }

            // Copy tabs
            foreach ($source->tabs as $tab) {
                $newTab = $copy->tabs()->create([
                    'name' => $tab->name,
                    'input_weight_kg' => $tab->input_weight_kg,
                    'remaining_weight_kg' => $tab->remaining_weight_kg,
                ]);
                foreach ($tab->items as $ti) {
                    $newTab->items()->create([
                        'item_id' => $ti->item_id,
                        'weight_kg' => $ti->weight_kg,
                        'weight_input_value' => $ti->weight_input_value,
                        'input_unit_id' => $ti->input_unit_id,
                        'is_dosis' => $ti->is_dosis,
                    ]);
                }
            }

            return redirect()->route('productions.edit', $copy)
                ->with('ok', 'Disalin dari #' . $source->id . '. Silakan sesuaikan.');
        });
    }
}
