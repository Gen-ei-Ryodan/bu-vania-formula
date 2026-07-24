<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cage;
use App\Models\Concept;
use App\Models\Item;
use App\Models\LaporanSore;
use App\Models\LaporanSoreDetail;
use App\Models\LaporanSoreDetailItem;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanSoreController extends Controller
{
    public function index()
    {
        $laporans = LaporanSore::with(['location', 'user'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();

        return view('dashboard.laporan-sore.index', compact('laporans'));
    }

    public function create()
    {
        $locations = Location::with('cages')->orderBy('name')->get();
        $konseps = Concept::with('items.item')->orderBy('name')->get();
        $items = Item::orderBy('name')->get();

        return view('dashboard.laporan-sore.create', compact('locations', 'konseps', 'items'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'sections' => json_decode($request->input('sections_data', '[]'), true) ?? [],
        ]);

        $validated = $request->validate([
            'location_id' => ['required', 'integer', 'exists:locations,id'],
            'tanggal' => ['required', 'date'],
            'sections' => ['required', 'array', 'min:1'],
            'sections.*.type' => ['required', 'string', 'in:sisa_kemarin,campuran_hari_ini,kirim_hari_ini,stock'],
            'sections.*.rows' => ['required', 'array', 'min:1'],
            'sections.*.rows.*.cage_id' => ['nullable', 'integer', 'exists:cages,id'],
            'sections.*.rows.*.nama_tali' => ['nullable', 'string', 'max:255'],
            'sections.*.rows.*.details' => ['required', 'array', 'min:1'],
            'sections.*.rows.*.details.*.konsep_id' => ['required', 'integer', 'exists:concepts,id'],
            'sections.*.rows.*.details.*.item_ids' => ['nullable', 'array'],
            'sections.*.rows.*.details.*.item_ids.*' => ['integer', 'exists:items,id'],
            'sections.*.rows.*.details.*.jumlah' => ['required', 'numeric', 'min:0'],
            'sections.*.rows.*.details.*.satuan' => ['required', 'string', 'max:50'],
        ]);

        $laporan = DB::transaction(function () use ($validated) {
            $laporan = LaporanSore::query()->create([
                'location_id' => $validated['location_id'],
                'tanggal' => $validated['tanggal'],
                'user_id' => Auth::id(),
            ]);

            foreach ($validated['sections'] as $section) {
                foreach ($section['rows'] as $row) {
                    foreach ($row['details'] as $detail) {
                        $detailModel = LaporanSoreDetail::query()->create([
                            'laporan_sore_id' => $laporan->id,
                            'section' => $section['type'],
                            'cage_id' => $row['cage_id'] ?? null,
                            'nama_tali' => $row['nama_tali'] ?? null,
                            'konsep_id' => $detail['konsep_id'],
                            'jumlah' => $detail['jumlah'],
                            'satuan' => $detail['satuan'],
                        ]);

                        if (!empty($detail['item_ids'])) {
                            $now = now();
                            $pivotRows = collect($detail['item_ids'])->map(fn ($itemId) => [
                                'laporan_sore_detail_id' => $detailModel->id,
                                'item_id' => $itemId,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ])->all();

                            LaporanSoreDetailItem::query()->insert($pivotRows);
                        }
                    }
                }
            }

            return $laporan;
        });

        return redirect()->route('laporan-sore.show', $laporan)
            ->with('ok', 'Laporan Sore berhasil dibuat.');
    }

    public function show(LaporanSore $laporanSore)
    {
        $laporanSore->load(['location', 'user', 'details.cage', 'details.konsep', 'details.items.item']);
        $sections = [
            'sisa_kemarin' => 'Sisa Kemarin',
            'campuran_hari_ini' => 'Campuran Hari Ini',
            'kirim_hari_ini' => 'Kirim Hari Ini',
            'stock' => 'Stock',
        ];

        return view('dashboard.laporan-sore.show', compact('laporanSore', 'sections'));
    }

    public function destroy(LaporanSore $laporanSore)
    {
        $laporanSore->delete();

        return redirect()->route('laporan-sore.index')->with('ok', 'Laporan Sore dihapus.');
    }
}
