<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Concept;
use App\Models\ConceptItem;
use App\Models\Item;
use App\Models\Unit;
use App\Models\User;
use App\Services\RecipePriceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterItemPriceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_item_with_structured_price(): void
    {
        $admin = $this->createAdmin();
        $kg = Unit::query()->create(['name' => 'kg', 'conversion_to_kg' => 1]);
        $category = Category::query()->create(['name' => 'Bahan Pokok']);

        $this->actingAs($admin)->post(route('items.store'), [
            'name' => 'ALZYME', 'category_id' => $category->id, 'default_unit_id' => $kg->id,
            'price' => 10000, 'price_unit_value' => 1, 'price_unit_id' => $kg->id,
        ])->assertRedirect(route('items.index'));

        $this->assertDatabaseHas('items', [
            'name' => 'ALZYME', 'price' => 10000, 'price_unit_value' => 1, 'price_unit_id' => $kg->id,
        ]);
    }

    public function test_item_price_fields_must_be_positive(): void
    {
        $admin = $this->createAdmin();
        $kg = Unit::query()->create(['name' => 'kg', 'conversion_to_kg' => 1]);
        $category = Category::query()->create(['name' => 'Bahan Pokok']);

        $this->actingAs($admin)->from(route('items.create'))->post(route('items.store'), [
            'name' => 'ALZYME', 'category_id' => $category->id, 'default_unit_id' => $kg->id,
            'price' => 0, 'price_unit_value' => 0, 'price_unit_id' => $kg->id,
        ])->assertSessionHasErrors(['price', 'price_unit_value']);
    }

    public function test_recipe_cost_converts_price_units_to_kilograms(): void
    {
        $kg = Unit::query()->create(['name' => 'kg', 'conversion_to_kg' => 1]);
        $gram = Unit::query()->create(['name' => 'gram', 'conversion_to_kg' => 0.001]);
        $category = Category::query()->create(['name' => 'Bahan Pokok']);
        $alzyme = Item::query()->create([
            'name' => 'ALZYME', 'category_id' => $category->id, 'default_unit_id' => $gram->id,
            'price' => 10000, 'price_unit_value' => 1, 'price_unit_id' => $kg->id,
        ]);
        $jagung = Item::query()->create([
            'name' => 'JAGUNG', 'category_id' => $category->id, 'default_unit_id' => $gram->id,
            'price' => 500, 'price_unit_value' => 100, 'price_unit_id' => $gram->id,
        ]);
        $concept = Concept::query()->create(['name' => 'Harga Test', 'base_weight_kg' => 0.7]);
        $alzymeRow = ConceptItem::query()->create(['concept_id' => $concept->id, 'item_id' => $alzyme->id, 'percentage' => 71.4286, 'weight_kg' => 0.5]);
        $jagungRow = ConceptItem::query()->create(['concept_id' => $concept->id, 'item_id' => $jagung->id, 'percentage' => 28.5714, 'weight_kg' => 0.2]);

        $service = app(RecipePriceService::class);

        $this->assertEquals(5000, $service->itemCost($alzyme, 0.5));
        $this->assertEquals(1000, $service->itemCost($jagung, 0.2));
        $this->assertEquals(5000, $alzymeRow->price);
        $this->assertEquals(1000, $jagungRow->price);
        $this->assertEquals(6000, $concept->fresh()->total_price);
    }

    public function test_api_exposes_item_and_recipe_prices(): void
    {
        $kg = Unit::query()->create(['name' => 'kg', 'conversion_to_kg' => 1]);
        $category = Category::query()->create(['name' => 'Bahan Pokok']);
        $item = Item::query()->create([
            'name' => 'API Item', 'category_id' => $category->id, 'default_unit_id' => $kg->id,
            'price' => 10000, 'price_unit_value' => 1, 'price_unit_id' => $kg->id,
        ]);
        $concept = Concept::query()->create(['name' => 'API Concept', 'base_weight_kg' => 1]);
        ConceptItem::query()->create([
            'concept_id' => $concept->id, 'item_id' => $item->id, 'percentage' => 50, 'weight_kg' => 0.5,
        ]);

        $this->getJson('/api/items/'.$item->id)
            ->assertOk()
            ->assertJsonPath('price', '10000.00')
            ->assertJsonPath('price_unit.name', 'kg');

        $this->getJson('/api/concepts/'.$concept->id.'/price')
            ->assertOk()
            ->assertJsonPath('items.0.price', 5000)
            ->assertJsonPath('total_price', 5000);
    }

    public function test_api_concept_response_includes_item_prices_and_total(): void
    {
        $kg = Unit::query()->create(['name' => 'kg', 'conversion_to_kg' => 1]);
        $gram = Unit::query()->create(['name' => 'gram', 'conversion_to_kg' => 0.001]);
        $category = Category::query()->create(['name' => 'Bahan Pokok']);
        $item = Item::query()->create([
            'name' => 'ALZYME', 'category_id' => $category->id, 'default_unit_id' => $gram->id,
            'price' => 10000, 'price_unit_value' => 1, 'price_unit_id' => $kg->id,
        ]);

        $response = $this->postJson('/api/concepts', [
            'name' => 'API Harga Test',
            'base_weight_kg' => 0.5,
            'items' => [['item_id' => $item->id, 'percentage' => 100]],
        ]);

        $response->assertCreated()
            ->assertJsonPath('items.0.weight_kg', 0.5)
            ->assertJsonPath('items.0.price', 5000)
            ->assertJsonPath('total_price', 5000);
    }

    public function test_api_can_read_recipe_price_breakdown(): void
    {
        $kg = Unit::query()->create(['name' => 'kg', 'conversion_to_kg' => 1]);
        $gram = Unit::query()->create(['name' => 'gram', 'conversion_to_kg' => 0.001]);
        $category = Category::query()->create(['name' => 'Bahan Pokok']);
        $item = Item::query()->create([
            'name' => 'JAGUNG', 'category_id' => $category->id, 'default_unit_id' => $gram->id,
            'price' => 500, 'price_unit_value' => 100, 'price_unit_id' => $gram->id,
        ]);
        $concept = Concept::query()->create(['name' => 'API Price Read', 'base_weight_kg' => 0.2]);
        ConceptItem::query()->create([
            'concept_id' => $concept->id, 'item_id' => $item->id, 'percentage' => 100, 'weight_kg' => 0.2,
        ]);

        $this->getJson(route('api.concepts.price', $concept))
            ->assertOk()
            ->assertJsonPath('items.0.price', 1000)
            ->assertJsonPath('total_price', 1000);
    }

    public function test_volume_price_conversion_is_supported_without_mixing_dimensions(): void
    {
        $liter = Unit::query()->create(['name' => 'liter', 'dimension' => 'volume', 'conversion_to_kg' => 1]);
        $milliliter = Unit::query()->create(['name' => 'milliliter', 'dimension' => 'volume', 'conversion_to_kg' => 0.001]);
        $item = Item::query()->create([
            'name' => 'Minyak', 'default_unit_id' => $milliliter->id,
            'price' => 500, 'price_unit_value' => 100, 'price_unit_id' => $milliliter->id,
        ]);

        $service = app(RecipePriceService::class);

        $this->assertSame(1000.0, $service->itemCost($item, 200, $milliliter));
        $this->expectException(\InvalidArgumentException::class);
        $service->itemCost($item, 1, Unit::query()->create([
            'name' => 'kg', 'dimension' => 'mass', 'conversion_to_kg' => 1,
        ]));
    }

    public function test_master_item_rejects_incompatible_price_unit(): void
    {
        $admin = $this->createAdmin();
        $kg = Unit::query()->create(['name' => 'kg', 'dimension' => 'mass', 'conversion_to_kg' => 1]);
        $liter = Unit::query()->create(['name' => 'liter', 'dimension' => 'volume', 'conversion_to_kg' => 1]);
        $category = Category::query()->create(['name' => 'Bahan Pokok']);

        $this->actingAs($admin)
            ->from(route('items.create'))
            ->post(route('items.store'), [
                'name' => 'Item Campur Dimensi', 'category_id' => $category->id,
                'default_unit_id' => $kg->id, 'price' => 1000,
                'price_unit_value' => 1, 'price_unit_id' => $liter->id,
            ])
            ->assertSessionHasErrors('price_unit_id');
    }

    private function createAdmin()
    {
        return User::factory()->create(['role' => 'admin']);
    }
}
