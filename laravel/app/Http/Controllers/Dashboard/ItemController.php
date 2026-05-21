<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Unit;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return view('dashboard.items.index', [
            'items' => Item::query()->with('defaultUnit')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('dashboard.items.create', [
            'units' => Unit::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:bahan_pokok,vitamin,obat'],
            'default_unit_id' => ['required', 'integer', 'exists:units,id'],
        ]);

        Item::query()->create($validated);

        return redirect()->route('items.index')->with('ok', 'Item dibuat.');
    }

    public function edit(Item $item)
    {
        return view('dashboard.items.edit', [
            'item' => $item,
            'units' => Unit::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:bahan_pokok,vitamin,obat'],
            'default_unit_id' => ['required', 'integer', 'exists:units,id'],
        ]);

        $item->update($validated);

        return redirect()->route('items.index')->with('ok', 'Item diupdate.');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')->with('ok', 'Item dihapus.');
    }
}
