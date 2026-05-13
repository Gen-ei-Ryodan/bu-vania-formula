<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Unit::query()->upsert([
            ['name' => 'gram', 'conversion_to_gram' => 1],
            ['name' => 'kg', 'conversion_to_gram' => 1000],
            ['name' => 'ton', 'conversion_to_gram' => 1000000],
            ['name' => 'sak', 'conversion_to_gram' => 50000],
        ], ['name'], ['conversion_to_gram']);

        if (! User::query()->where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }
    }
}
