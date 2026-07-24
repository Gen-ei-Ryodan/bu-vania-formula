<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RevisiTest extends DuskTestCase
{
    public function test_revisi_1_format_angka_helper(): void
    {
        $this->assertSame('10', formatWeight(10.0));
        $this->assertSame('10', formatWeight(10.00));
        $this->assertSame('10', formatWeight(10));
        $this->assertSame('10,50', formatWeight(10.50));
        $this->assertSame('0', formatWeight(0.00));
        $this->assertSame('-', formatWeight(null));
        $this->assertSame('10000,50', formatWeight(10000.50));
        $this->assertSame('5000', formatWeight(5000));
    }

    public function test_revisi_2_excel_button_on_production_index(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/productions')
                ->waitForText('Produksi')
                ->assertSee('Excel')
                ->assertPresent('a[href*="excel"]');
        });
    }

    public function test_revisi_2_excel_button_on_treatment_index(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/treatments')
                ->waitForText('Pengobatan')
                ->assertSee('Excel')
                ->assertPresent('a[href*="excel"]');
        });
    }

    public function test_revisi_3_laporan_sore_all_sections_shown(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/laporan-sore')
                ->waitForText('Laporan Sore');

            $firstLaporanLink = $browser->element('.data tbody a');
            $this->assertNotNull($firstLaporanLink, 'No laporan-sore data rows found');
            $href = $firstLaporanLink->getAttribute('href');
            $browser->visit($href)
                ->waitForText('Sisa Kemarin')
                ->assertSee('Sisa Kemarin')
                ->assertSee('Campuran Hari Ini')
                ->assertSee('Kirim Hari Ini')
                ->assertSee('Stock');
        });
    }

    public function test_revisi_5_kandang_in_production_show(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/productions');

            $firstDetailLink = $browser->element('.data tbody a');
            $this->assertNotNull($firstDetailLink, 'No production data rows found');
            $href = $firstDetailLink->getAttribute('href');
            $browser->visit($href)
                ->waitForText('Detail Produksi')
                ->assertSee('Kandang');
        });
    }

    public function test_revisi_5_kandang_in_treatment_show(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/treatments');

            $firstDetailLink = $browser->element('.data tbody a');
            $this->assertNotNull($firstDetailLink, 'No treatment data rows found');
            $href = $firstDetailLink->getAttribute('href');
            $browser->visit($href)
                ->waitForText('Detail Pengobatan')
                ->assertSee('Kandang');
        });
    }
}
