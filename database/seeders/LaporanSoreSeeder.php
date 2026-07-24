<?php

namespace Database\Seeders;

use App\Models\Cage;
use App\Models\Concept;
use App\Models\Item;
use App\Models\LaporanSore;
use App\Models\LaporanSoreDetail;
use App\Models\LaporanSoreDetailItem;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaporanSoreSeeder extends Seeder
{
    public function run(): void
    {
        if (LaporanSore::exists()) {
            return;
        }

        // === LOCATIONS ===
        $location = Location::firstOrCreate(['name' => 'Surabaya']);
        $location2 = Location::firstOrCreate(['name' => 'Malang']);

        // === CAGES ===
        $cages = [];
        foreach (['Kandang MC1', 'Kandang MC2', 'Kandang MC3', 'Kandang MC4', 'Kandang MC5'] as $cn) {
            $cages[$cn] = Cage::firstOrCreate(['name' => $cn, 'location_id' => $location->id]);
        }
        foreach (['Kandang ML1', 'Kandang ML2', 'Kandang ML3'] as $cn) {
            $cages[$cn] = Cage::firstOrCreate(['name' => $cn, 'location_id' => $location2->id]);
        }

        // === CONCEPTS ===
        $konsepA = Concept::firstOrCreate(['name' => 'Konsep A']);
        $konsepB = Concept::firstOrCreate(['name' => 'Konsep B']);
        $konsepC = Concept::firstOrCreate(['name' => 'Konsep C']);

        // === ITEMS (dummy for laporan sore) ===
        $unitKg = \App\Models\Unit::where('name', 'kg')->first();
        $itemB = Item::firstOrCreate(['name' => 'Item B'], ['default_unit_id' => $unitKg->id]);
        $itemC = Item::firstOrCreate(['name' => 'Item C'], ['default_unit_id' => $unitKg->id]);
        $itemD = Item::firstOrCreate(['name' => 'Item D'], ['default_unit_id' => $unitKg->id]);

        $userId = DB::table('users')->first()?->id ?? 1;
        $tanggal = now()->subDay();

        // === LAPORAN SORE 1 (Surabaya) ===
        $laporan1 = LaporanSore::create([
            'location_id' => $location->id,
            'tanggal' => $tanggal,
            'user_id' => $userId,
        ]);

        $this->addDetail($laporan1, 'sisa_kemarin', $cages['Kandang MC3'], 'Tali Hijau', $konsepA, [$itemB->id, $itemC->id], 80, 'Zak');
        $this->addDetail($laporan1, 'sisa_kemarin', $cages['Kandang MC3'], 'Tali Hijau', $konsepB, [], 20, 'Zak');
        $this->addDetail($laporan1, 'sisa_kemarin', $cages['Kandang MC1'], 'Tali Biru', $konsepA, [$itemD->id], 45, 'Zak');

        $this->addDetail($laporan1, 'campuran_hari_ini', $cages['Kandang MC3'], 'Tali Hijau', $konsepA, [$itemB->id, $itemC->id], 60, 'Zak');
        $this->addDetail($laporan1, 'campuran_hari_ini', $cages['Kandang MC3'], 'Tali Hijau', $konsepB, [], 15, 'Zak');
        $this->addDetail($laporan1, 'campuran_hari_ini', $cages['Kandang MC5'], 'Tali Kuning', $konsepC, [$itemC->id], 30, 'Zak');

        $this->addDetail($laporan1, 'kirim_hari_ini', $cages['Kandang MC3'], 'Tali Hijau', $konsepA, [$itemB->id, $itemC->id], 50, 'Zak');
        $this->addDetail($laporan1, 'kirim_hari_ini', $cages['Kandang MC3'], 'Tali Hijau', $konsepB, [], 15, 'Zak');

        $this->addDetail($laporan1, 'stock', $cages['Kandang MC3'], 'Tali Hijau', $konsepA, [$itemB->id, $itemC->id], 120, 'Zak');
        $this->addDetail($laporan1, 'stock', $cages['Kandang MC3'], 'Tali Hijau', $konsepB, [], 40, 'Zak');
        $this->addDetail($laporan1, 'stock', $cages['Kandang MC1'], 'Tali Biru', $konsepA, [$itemD->id], 55, 'Zak');

        // === LAPORAN SORE 2 (Malang) ===
        $laporan2 = LaporanSore::create([
            'location_id' => $location2->id,
            'tanggal' => $tanggal->copy()->subDays(1),
            'user_id' => $userId,
        ]);

        $this->addDetail($laporan2, 'sisa_kemarin', $cages['Kandang ML1'], 'Tali Merah', $konsepA, [$itemB->id], 40, 'Zak');
        $this->addDetail($laporan2, 'sisa_kemarin', $cages['Kandang ML2'], 'Tali Putih', $konsepC, [$itemC->id, $itemD->id], 25, 'Zak');

        $this->addDetail($laporan2, 'campuran_hari_ini', $cages['Kandang ML1'], 'Tali Merah', $konsepA, [$itemB->id], 35, 'Zak');
        $this->addDetail($laporan2, 'campuran_hari_ini', $cages['Kandang ML2'], 'Tali Putih', $konsepC, [$itemC->id], 20, 'Zak');

        $this->addDetail($laporan2, 'kirim_hari_ini', $cages['Kandang ML1'], 'Tali Merah', $konsepA, [$itemB->id], 30, 'Zak');

        $this->addDetail($laporan2, 'stock', $cages['Kandang ML1'], 'Tali Merah', $konsepA, [$itemB->id], 45, 'Zak');
        $this->addDetail($laporan2, 'stock', $cages['Kandang ML2'], 'Tali Putih', $konsepC, [$itemC->id, $itemD->id], 35, 'Zak');
    }

    private function addDetail($laporan, $section, $cage, $namaTali, $konsep, array $itemIds, $jumlah, $satuan): void
    {
        $detail = LaporanSoreDetail::create([
            'laporan_sore_id' => $laporan->id,
            'section' => $section,
            'cage_id' => $cage->id,
            'nama_tali' => $namaTali,
            'konsep_id' => $konsep->id,
            'jumlah' => $jumlah,
            'satuan' => $satuan,
        ]);

        foreach ($itemIds as $itemId) {
            LaporanSoreDetailItem::create([
                'laporan_sore_detail_id' => $detail->id,
                'item_id' => $itemId,
            ]);
        }
    }
}
