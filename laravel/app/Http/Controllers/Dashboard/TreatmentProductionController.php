<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Models\Item;
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

class TreatmentProductionController extends Controller
{
    public function index()
    {
        return view('dashboard.treatments.index', [
            'productions' => Production::query()->treatment()->with('concept')->orderByDesc('id')->get(),
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
        return view('dashboard.treatments.create', [
            'concepts' => $concepts,
            'conceptsData' => $conceptsData,
            'units' => Unit::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, ProductionSnapshotService $service)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'cage' => ['nullable', 'string', 'max:255'],
            'treatment_day' => ['required', 'integer', 'min:1'],
            'treatment_time' => ['required', 'in:pagi,siang,malam,full'],
            'concept_id' => ['required', 'integer', 'exists:concepts,id'],
            'target_weight_value' => ['required', 'numeric', 'min:0.0001'],
            'target_weight_unit_id' => ['required', 'integer', 'exists:units,id'],
            'start_date' => ['nullable', 'date'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'is_forever' => ['nullable', 'boolean'],
            'mix_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $unit = Unit::query()->findOrFail((int) $validated['target_weight_unit_id']);
        $targetWeightKg = (float) $validated['target_weight_value'] * (float) $unit->conversion_to_kg;

        $production = DB::transaction(function () use ($validated, $targetWeightKg, $request, $service) {
            $production = Production::query()->create([
                'name' => $validated['name'],
                'location' => $validated['location'] ?? null,
                'cage' => $validated['cage'] ?? null,
                'treatment_day' => $validated['treatment_day'],
                'treatment_time' => $validated['treatment_time'],
                'concept_id' => (int) $validated['concept_id'],
                'target_weight_kg' => $targetWeightKg,
                'start_date' => $validated['start_date'] ?? null,
                'duration_days' => $validated['duration_days'] ?? null,
                'is_forever' => $request->boolean('is_forever'),
                'mix_date' => $validated['mix_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'production_type' => 'treatment',
            ]);

            $production->load('concept.items');
            $service->generate($production);

            return $production;
        });

        return redirect()->route('treatments.show', $production)->with('ok', 'Produksi Pengobatan dibuat.');
    }

    public function show(Production $production)
    {
        if ($production->production_type !== 'treatment') {
            abort(Response::HTTP_NOT_FOUND);
        }

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

        return view('dashboard.treatments.show', [
            'production' => $production,
            'items' => Item::query()->orderBy('name')->get(),
            'units' => Unit::query()->orderBy('name')->get(),
            'tabAvailableKg' => $tabAvailable,
        ]);
    }

    public function edit(Production $production)
    {
        if ($production->production_type !== 'treatment') {
            abort(Response::HTTP_NOT_FOUND);
        }

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
        return view('dashboard.treatments.edit', [
            'production' => $production,
            'concepts' => $concepts,
            'conceptsData' => $conceptsData,
            'units' => Unit::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Production $production, ProductionSnapshotService $service)
    {
        if ($production->production_type !== 'treatment') {
            abort(Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'cage' => ['nullable', 'string', 'max:255'],
            'treatment_day' => ['required', 'integer', 'min:1'],
            'treatment_time' => ['required', 'in:pagi,siang,malam,full'],
            'concept_id' => ['required', 'integer', 'exists:concepts,id'],
            'target_weight_value' => ['required', 'numeric', 'min:0.0001'],
            'target_weight_unit_id' => ['required', 'integer', 'exists:units,id'],
            'start_date' => ['nullable', 'date'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'is_forever' => ['nullable', 'boolean'],
            'mix_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
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
                'treatment_day' => $validated['treatment_day'],
                'treatment_time' => $validated['treatment_time'],
                'concept_id' => (int) $validated['concept_id'],
                'target_weight_kg' => $targetWeightKg,
                'start_date' => $validated['start_date'] ?? null,
                'duration_days' => $validated['duration_days'] ?? null,
                'is_forever' => $request->boolean('is_forever'),
                'mix_date' => $validated['mix_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'production_type' => 'treatment',
            ]);

            $production->load('concept.items');
            $service->regenerate($production);
        });

        return redirect()->route('treatments.show', $production)->with('ok', 'Produksi Pengobatan diupdate.');
    }

    public function storeGroup(Request $request, Production $production)
    {
        if ($production->production_type !== 'treatment') {
            abort(Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        ProductionGroup::query()->create([
            'production_id' => $production->id,
            'name' => $validated['name'],
        ]);

        return redirect()->route('treatments.show', $production)->with('ok', 'Golongan ditambah.');
    }

    public function storeGroupItem(Request $request, ProductionGroup $group)
    {
        $production = $group->production;
        if ($production->production_type !== 'treatment') {
            abort(Response::HTTP_NOT_FOUND);
        }

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

        return redirect()->route('treatments.show', $production)->with('ok', 'Item golongan ditambah.');
    }

    public function storeTab(Request $request, Production $production, ProductionTabService $service)
    {
        if ($production->production_type !== 'treatment') {
            abort(Response::HTTP_NOT_FOUND);
        }

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

        return redirect()->to(route('treatments.show', $production) . '#tab')->with('ok', 'TAB dibuat.');
    }

    public function storeTabItem(Request $request, ProductionTab $tab)
    {
        $production = $tab->production;
        if ($production->production_type !== 'treatment') {
            abort(Response::HTTP_NOT_FOUND);
        }

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

        return redirect()->to(route('treatments.show', $production) . '#tab')->with('ok', 'Item TAB ditambah.');
    }

    public function destroy(Production $production)
    {
        if ($production->production_type !== 'treatment') {
            abort(Response::HTTP_NOT_FOUND);
        }

        $production->delete();

        return redirect()->route('treatments.index')->with('ok', 'Produksi Pengobatan dihapus.');
    }

    public function destroyGroup(ProductionGroup $group)
    {
        $production = $group->production;
        if ($production->production_type !== 'treatment') {
            abort(Response::HTTP_NOT_FOUND);
        }

        $productionId = $group->production_id;
        $group->delete();

        return redirect()->route('treatments.show', $productionId)->with('ok', 'Golongan dihapus.');
    }

    public function destroyGroupItem(ProductionGroupItem $groupItem)
    {
        $production = $groupItem->group?->production;
        if ($production && $production->production_type !== 'treatment') {
            abort(Response::HTTP_NOT_FOUND);
        }

        $productionId = $groupItem->group?->production_id;
        $groupItem->delete();

        return redirect()->route('treatments.show', $productionId)->with('ok', 'Item golongan dihapus.');
    }

    public function destroyTab(ProductionTab $tab)
    {
        $production = $tab->production;
        if ($production->production_type !== 'treatment') {
            abort(Response::HTTP_NOT_FOUND);
        }

        $productionId = $tab->production_id;
        $tab->delete();

        return redirect()->to(route('treatments.show', $productionId) . '#tab')->with('ok', 'TAB dihapus.');
    }

    public function destroyTabItem(ProductionTabItem $tabItem)
    {
        $production = $tabItem->tab?->production;
        if ($production && $production->production_type !== 'treatment') {
            abort(Response::HTTP_NOT_FOUND);
        }

        $productionId = $tabItem->tab?->production_id;
        $tabItem->delete();

        return redirect()->to(route('treatments.show', $productionId) . '#tab')->with('ok', 'Item TAB dihapus.');
    }

    public function pdf(Production $production)
    {
        if ($production->production_type !== 'treatment') {
            abort(Response::HTTP_NOT_FOUND);
        }

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

        $pdf = Pdf::loadView('dashboard.treatments.pdf', [
            'production' => $production,
        ]);

        return $pdf->download('treatment-'.$production->id.'.pdf');
    }
}
