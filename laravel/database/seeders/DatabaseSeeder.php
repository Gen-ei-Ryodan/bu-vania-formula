<?php

namespace Database\Seeders;

use App\Models\Concept;
use App\Models\ConceptItem;
use App\Models\Item;
use App\Models\Production;
use App\Models\ProductionGroup;
use App\Models\ProductionGroupItem;
use App\Models\ProductionItem;
use App\Models\ProductionTab;
use App\Models\ProductionTabItem;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // === UNITS ===
        Unit::query()->upsert([
            ['name' => 'kg', 'conversion_to_kg' => 1],
            ['name' => 'gram', 'conversion_to_kg' => 0.001],
            ['name' => 'ton', 'conversion_to_kg' => 1000],
            ['name' => 'sak', 'conversion_to_kg' => 50],
        ], ['name'], ['conversion_to_kg']);

        $unitKg = Unit::where('name', 'kg')->first();
        $unitGram = Unit::where('name', 'gram')->first();

        if (! User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        // === ITEMS ===
        Item::query()->upsert([
            ['name' => 'Jagung', 'category' => 'bahan_pokok', 'default_unit_id' => $unitKg->id],
            ['name' => 'Beras', 'category' => 'bahan_pokok', 'default_unit_id' => $unitKg->id],
            ['name' => 'Kedelai', 'category' => 'bahan_pokok', 'default_unit_id' => $unitKg->id],
            ['name' => 'Tepung Ikan', 'category' => 'bahan_pokok', 'default_unit_id' => $unitKg->id],
            ['name' => 'Vitamin C', 'category' => 'vitamin', 'default_unit_id' => $unitGram->id],
            ['name' => 'Amoksilin', 'category' => 'obat', 'default_unit_id' => $unitGram->id],
        ], ['name'], ['category', 'default_unit_id']);

        $jagung = Item::where('name', 'Jagung')->first();
        $beras = Item::where('name', 'Beras')->first();
        $kedelai = Item::where('name', 'Kedelai')->first();
        $tepungIkan = Item::where('name', 'Tepung Ikan')->first();
        $vitaminC = Item::where('name', 'Vitamin C')->first();
        $amoksilin = Item::where('name', 'Amoksilin')->first();

        // === CONCEPTS ===
        if (! Concept::where('name', 'Resep 1 (1 ton)')->exists()) {
            $concept = Concept::query()->create([
                'name' => 'Resep 1 (1 ton)',
                'base_weight_kg' => 1000,
            ]);

            ConceptItem::query()->insert([
                ['concept_id' => $concept->id, 'item_id' => $jagung->id, 'percentage' => 50, 'weight_kg' => 500],
                ['concept_id' => $concept->id, 'item_id' => $beras->id, 'percentage' => 30, 'weight_kg' => 300],
                ['concept_id' => $concept->id, 'item_id' => $kedelai->id, 'percentage' => 15, 'weight_kg' => 150],
                ['concept_id' => $concept->id, 'item_id' => $tepungIkan->id, 'percentage' => 5, 'weight_kg' => 50],
            ]);
        }

        if (! Concept::where('name', 'Resep 2 (500 kg)')->exists()) {
            $concept2 = Concept::query()->create([
                'name' => 'Resep 2 (500 kg)',
                'base_weight_kg' => 500,
            ]);

            ConceptItem::query()->insert([
                ['concept_id' => $concept2->id, 'item_id' => $jagung->id, 'percentage' => 60, 'weight_kg' => 300],
                ['concept_id' => $concept2->id, 'item_id' => $beras->id, 'percentage' => 40, 'weight_kg' => 200],
            ]);
        }

        $concept1 = Concept::where('name', 'Resep 1 (1 ton)')->first();
        $concept2 = Concept::where('name', 'Resep 2 (500 kg)')->first();

        // === PRODUCTIONS ===
        if (! Production::where('name', 'Produksi April 2025')->exists()) {
            $production = Production::query()->create([
                'name' => 'Produksi April 2025',
                'location' => 'Lokasi A',
                'cage' => 'Kandang 1',
                'concept_id' => $concept1->id,
                'target_weight_kg' => 500,
                'start_date' => Carbon::parse('2025-04-01'),
                'mix_date' => Carbon::parse('2025-04-01'),
                'duration_days' => 30,
                'is_forever' => false,
                'notes' => 'Produksi reguler bulan April.',
            ]);

            // Snapshot (auto-scaling dari Resep 1, target=500kg)
            ProductionItem::query()->insert([
                ['production_id' => $production->id, 'item_id' => $jagung->id, 'weight_kg' => 250, 'source' => $concept1->name],
                ['production_id' => $production->id, 'item_id' => $beras->id, 'weight_kg' => 150, 'source' => $concept1->name],
                ['production_id' => $production->id, 'item_id' => $kedelai->id, 'weight_kg' => 75, 'source' => $concept1->name],
                ['production_id' => $production->id, 'item_id' => $tepungIkan->id, 'weight_kg' => 25, 'source' => $concept1->name],
            ]);

            // Golongan
            $group = ProductionGroup::query()->create([
                'production_id' => $production->id,
                'name' => 'Golongan A',
            ]);

            ProductionGroupItem::query()->create([
                'group_id' => $group->id,
                'item_id' => $vitaminC->id,
                'weight_kg' => 0.5,
                'is_dosis' => true,
                'weight_input_value' => 500,
                'weight_input_unit_id' => $unitGram->id,
            ]);

            ProductionGroupItem::query()->create([
                'group_id' => $group->id,
                'item_id' => $amoksilin->id,
                'weight_kg' => 1,
                'is_dosis' => true,
                'weight_input_value' => 1000,
                'weight_input_unit_id' => $unitGram->id,
            ]);

            // Tab
            $tab = ProductionTab::query()->create([
                'production_id' => $production->id,
                'name' => 'Tab 1',
                'input_weight_kg' => 200,
                'remaining_weight_kg' => 200,
            ]);

            ProductionTabItem::query()->create([
                'tab_id' => $tab->id,
                'item_id' => $jagung->id,
                'weight_kg' => 100,
                'is_dosis' => false,
                'weight_input_value' => 100,
                'weight_input_unit_id' => $unitKg->id,
            ]);

            ProductionTabItem::query()->create([
                'tab_id' => $tab->id,
                'item_id' => $beras->id,
                'weight_kg' => 100,
                'is_dosis' => false,
                'weight_input_value' => 100,
                'weight_input_unit_id' => $unitKg->id,
            ]);
        }

        if (! Production::where('name', 'Produksi Pengobatan Mei')->exists()) {
            $production2 = Production::query()->create([
                'name' => 'Produksi Pengobatan Mei',
                'location' => 'Lokasi B',
                'cage' => 'Kandang 2',
                'concept_id' => $concept2->id,
                'target_weight_kg' => 300,
                'start_date' => Carbon::parse('2025-05-01'),
                'mix_date' => Carbon::parse('2025-05-01'),
                'duration_days' => 14,
                'is_forever' => false,
                'notes' => 'Produksi pengobatan dengan dosis khusus.',
            ]);

            ProductionItem::query()->insert([
                ['production_id' => $production2->id, 'item_id' => $jagung->id, 'weight_kg' => 180, 'source' => $concept2->name],
                ['production_id' => $production2->id, 'item_id' => $beras->id, 'weight_kg' => 120, 'source' => $concept2->name],
            ]);

            $group2 = ProductionGroup::query()->create([
                'production_id' => $production2->id,
                'name' => 'Obat',
            ]);

            ProductionGroupItem::query()->create([
                'group_id' => $group2->id,
                'item_id' => $amoksilin->id,
                'weight_kg' => 0.3,
                'is_dosis' => true,
                'weight_input_value' => 300,
                'weight_input_unit_id' => $unitGram->id,
            ]);
        }
    }
}
