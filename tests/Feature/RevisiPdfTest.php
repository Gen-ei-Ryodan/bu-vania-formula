<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\View;
use Tests\TestCase;

class RevisiPdfTest extends TestCase
{
    private function makeMockProduction(array $overrides = []): object
    {
        $defaults = [
            'id' => 1,
            'name' => 'Test Production',
            'location' => 'Lokasi A',
            'cage' => 'Kandang A',
            'target_weight_kg' => 500,
            'production_type' => 'biasa',
            'mix_date' => null,
            'start_date' => null,
            'duration_days' => null,
            'is_forever' => false,
            'notes' => null,
            'treatment_day' => null,
            'treatment_time' => null,
            'treatment_duration_days' => null,
            'items' => collect(),
            'groups' => collect(),
            'tabs' => collect(),
        ];
        $p = (object) array_merge($defaults, $overrides);
        $p->concept = (object) ['name' => 'Test Concept'];
        return $p;
    }

    public function test_revisi_5_production_pdf_view_has_kandang()
    {
        $p = $this->makeMockProduction(['cage' => 'Kandang A']);
        $html = View::make('dashboard.productions.pdf', ['production' => $p, 'totalCards' => 2])->render();
        $this->assertStringContainsString('Kandang A', $html);
        $this->assertStringNotContainsString('>Batch<', $html);
    }

    public function test_revisi_5_treatment_pdf_view_has_kandang()
    {
        $p = $this->makeMockProduction(['cage' => 'Kandang B', 'production_type' => 'treatment']);
        $html = View::make('dashboard.treatments.pdf', ['production' => $p, 'totalCards' => 2])->render();
        $this->assertStringContainsString('Kandang B', $html);
        $this->assertStringNotContainsString('>Batch<', $html);
    }

    public function test_revisi_4_pdf_css_has_kw()
    {
        $p = $this->makeMockProduction();
        $html = View::make('dashboard.productions.pdf', ['production' => $p, 'totalCards' => 2])->render();
        $this->assertStringContainsString('.kw {', $html);
    }
}
