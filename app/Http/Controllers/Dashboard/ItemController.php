<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ItemController extends Controller
{
    public function index()
    {
        return view('dashboard.items.index', [
            'items' => Item::query()->with(['defaultUnit', 'priceUnit', 'category'])->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('dashboard.items.create', [
            'units' => Unit::query()->orderBy('name')->get(),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'default_unit_id' => ['required', 'integer', 'exists:units,id'],
            'price' => ['required', 'numeric', 'gt:0'],
            'price_unit_value' => ['required', 'numeric', 'gt:0'],
            'price_unit_id' => ['required', 'integer', 'exists:units,id'],
        ]);

        $this->ensureCompatibleUnits($validated);

        Item::query()->create($validated);

        return redirect()->route('items.index')->with('ok', 'Item dibuat.');
    }

    public function edit(Item $item)
    {
        return view('dashboard.items.edit', [
            'item' => $item,
            'units' => Unit::query()->orderBy('name')->get(),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'default_unit_id' => ['required', 'integer', 'exists:units,id'],
            'price' => ['required', 'numeric', 'gt:0'],
            'price_unit_value' => ['required', 'numeric', 'gt:0'],
            'price_unit_id' => ['required', 'integer', 'exists:units,id'],
        ]);

        $this->ensureCompatibleUnits($validated);

        $item->update($validated);

        return redirect()->route('items.index')->with('ok', 'Item diupdate.');
    }

    public function destroy(Item $item)
    {
        $hasRelations = $item->conceptItems()->exists()
            || $item->productionItems()->exists()
            || $item->productionGroupItems()->exists()
            || $item->productionTabItems()->exists();

        if ($hasRelations) {
            return redirect()->route('items.index')->with('error', 'Item tidak bisa dihapus karena masih digunakan di data lain (konsep/produksi).');
        }

        $item->delete();

        return redirect()->route('items.index')->with('ok', 'Item dihapus.');
    }

    private function ensureCompatibleUnits(array $validated): void
    {
        $defaultUnit = Unit::query()->findOrFail($validated['default_unit_id']);
        $priceUnit = Unit::query()->findOrFail($validated['price_unit_id']);

        if (! $defaultUnit->isCompatibleWith($priceUnit)) {
            throw ValidationException::withMessages([
                'price_unit_id' => ['Satuan harga harus satu dimensi dengan unit default item.'],
            ]);
        }
    }
}
