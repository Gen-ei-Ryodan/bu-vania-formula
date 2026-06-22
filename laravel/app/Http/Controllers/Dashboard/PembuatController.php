<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Pembuat;
use Illuminate\Http\Request;

class PembuatController extends Controller
{
    public function index()
    {
        return view('dashboard.pembuats.index', [
            'pembuats' => Pembuat::query()->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('dashboard.pembuats.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Pembuat::query()->create($validated);

        return redirect()->route('pembuats.index')->with('ok', 'Pembuat dibuat.');
    }

    public function edit(Pembuat $pembuat)
    {
        return view('dashboard.pembuats.edit', compact('pembuat'));
    }

    public function update(Request $request, Pembuat $pembuat)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $pembuat->update($validated);

        return redirect()->route('pembuats.index')->with('ok', 'Pembuat diupdate.');
    }

    public function destroy(Pembuat $pembuat)
    {
        $hasRelations = $pembuat->concepts()->exists();

        if ($hasRelations) {
            return redirect()->route('pembuats.index')->with('error', 'Pembuat tidak bisa dihapus karena masih digunakan di konsep.');
        }

        $pembuat->delete();

        return redirect()->route('pembuats.index')->with('ok', 'Pembuat dihapus.');
    }
}
