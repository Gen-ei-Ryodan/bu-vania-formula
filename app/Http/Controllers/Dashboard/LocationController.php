<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cage;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        return view('dashboard.locations.index', [
            'locations' => Location::withCount('cages')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('dashboard.locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Location::create($validated);

        return redirect()->route('locations.index')->with('ok', 'Lokasi dibuat.');
    }

    public function edit(Location $location)
    {
        $location->load('cages');
        return view('dashboard.locations.edit', [
            'location' => $location,
        ]);
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $location->update($validated);

        return redirect()->route('locations.index')->with('ok', 'Lokasi diupdate.');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('locations.index')->with('ok', 'Lokasi dihapus.');
    }

    public function storeCage(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $location->cages()->create($validated);

        return redirect()->route('locations.edit', $location)->with('ok', 'Kandang ditambah.');
    }

    public function updateCage(Request $request, Cage $cage)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $cage->update($validated);

        return redirect()->route('locations.edit', $cage->location_id)->with('ok', 'Kandang diupdate.');
    }

    public function destroyCage(Cage $cage)
    {
        $locationId = $cage->location_id;
        $cage->delete();

        return redirect()->route('locations.edit', $locationId)->with('ok', 'Kandang dihapus.');
    }
}
