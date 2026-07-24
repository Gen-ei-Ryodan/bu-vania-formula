<?php

namespace Tests\Browser;

use App\Models\Production;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExcelExportTest extends DuskTestCase
{
    public function test_production_excel_export(): void
    {
        $production = Production::where('production_type', 'biasa')->firstOrFail();

        $this->browse(function (Browser $browser) use ($production) {
            $browser->visit('/login')
                ->type('email', 'test@example.com')
                ->type('password', 'password')
                ->press('Masuk')
                ->waitForLocation('/dashboard')
                ->visit('/productions/' . $production->id)
                ->waitForText($production->name)
                ->pause(2000)
                ->storeConsoleLog('production-show-console')
                ->assertPresent('a[href*="excel"]')
                ->click('a[href*="excel"]')
                ->pause(3000);
        });
    }

    public function test_treatment_excel_export(): void
    {
        $production = Production::where('production_type', 'treatment')->firstOrFail();

        $this->browse(function (Browser $browser) use ($production) {
            $browser->visit('/login')
                ->type('email', 'test@example.com')
                ->type('password', 'password')
                ->press('Masuk')
                ->waitForLocation('/dashboard')
                ->visit('/treatments/' . $production->id)
                ->waitForText($production->name)
                ->pause(2000)
                ->storeConsoleLog('treatment-show-console')
                ->assertPresent('a[href*="excel"]')
                ->click('a[href*="excel"]')
                ->pause(3000);
        });
    }
}
