<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    public function index()
    {
        return view('dashboard.units.index', [
            'units' => Unit::query()->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('dashboard.units.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:units,name'],
            'conversion_to_kg' => ['required', 'numeric', 'min:0.0001'],
        ]);

        Unit::query()->create($validated);

        return redirect()->route('units.index')->with('ok', 'Unit dibuat.');
    }

    public function edit(Unit $unit)
    {
        return view('dashboard.units.edit', [
            'unit' => $unit,
        ]);
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('units', 'name')->ignore($unit->id)],
            'conversion_to_kg' => ['required', 'numeric', 'min:0.0001'],
        ]);

        $unit->update($validated);

        return redirect()->route('units.index')->with('ok', 'Unit diupdate.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('units.index')->with('ok', 'Unit dihapus.');
    }
}
