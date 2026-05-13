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
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ProductionController extends Controller
{
    public function index()
    {
        return view('dashboard.productions.index', [
            'productions' => Production::query()->with('concept')->orderByDesc('id')->get(),
        ]);
    }

    public function create()
    {
        return view('dashboard.productions.create', [
            'concepts' => Concept::query()->orderBy('name')->get(),
            'units' => Unit::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'seed_name' => ['required', 'string', 'max:255'],
            'concept_id' => ['required', 'integer', 'exists:concepts,id'],
            'target_weight_value' => ['required', 'numeric', 'min:0.0001'],
            'target_weight_unit_id' => ['required', 'integer', 'exists:units,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $unit = Unit::query()->findOrFail((int) $validated['target_weight_unit_id']);
        $targetWeightKg = (float) $validated['target_weight_value'] * (float) $unit->conversion_to_kg;

        $production = Production::query()->create([
            'name' => $validated['name'],
            'seed_name' => $validated['seed_name'],
            'concept_id' => (int) $validated['concept_id'],
            'target_weight_kg' => $targetWeightKg,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('productions.show', $production)->with('ok', 'Production dibuat.');
    }

    public function show(Production $production)
    {
        $production->load([
            'concept',
            'items.item',
            'groups.items.item',
            'tabs.items.item',
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

    public function generate(Production $production, ProductionSnapshotService $service)
    {
        $production->load('concept.items');

        $service->generate($production);

        return redirect()->route('productions.show', $production)->with('ok', 'Snapshot production_items dibuat.');
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

        return redirect()->route('productions.show', $production)->with('ok', 'TAB dibuat.');
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
        ]);

        return redirect()->route('productions.show', $tab->production_id)->with('ok', 'Item TAB ditambah.');
    }

    public function destroy(Production $production)
    {
        $production->delete();

        return redirect()->route('productions.index')->with('ok', 'Production dihapus.');
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

        return redirect()->route('productions.show', $productionId)->with('ok', 'TAB dihapus.');
    }

    public function destroyTabItem(ProductionTabItem $tabItem)
    {
        $productionId = $tabItem->tab?->production_id;
        $tabItem->delete();

        return redirect()->route('productions.show', $productionId)->with('ok', 'Item TAB dihapus.');
    }

    public function pdf(Production $production)
    {
        $production->load([
            'concept',
            'concept.items.item',
            'items.item',
            'groups.items.item',
            'tabs.items.item',
        ]);

        if (! class_exists(Pdf::class)) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'PDF generator belum terpasang.');
        }

        $pdf = Pdf::loadView('dashboard.productions.pdf', [
            'production' => $production,
        ]);

        return $pdf->download('production-'.$production->id.'.pdf');
    }
}
