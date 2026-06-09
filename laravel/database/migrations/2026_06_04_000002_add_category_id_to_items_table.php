<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Map existing category enum to new category IDs
        $categoryMap = DB::table('categories')->pluck('id', 'name')->toArray();

        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('category');
        });

        // Migrate existing data
        foreach (DB::table('items')->get() as $item) {
            $mappedName = match ($item->category) {
                'bahan_pokok' => 'Bahan Pokok',
                'obat' => 'Obat',
                'vitamin' => 'Vitamin',
                default => null,
            };

            if ($mappedName && isset($categoryMap[$mappedName])) {
                DB::table('items')->where('id', $item->id)->update([
                    'category_id' => $categoryMap[$mappedName],
                ]);
            }
        }

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('category')->nullable()->after('name');
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
