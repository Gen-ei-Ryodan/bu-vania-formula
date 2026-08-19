<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('price', 16, 2)->nullable()->after('default_unit_id');
            $table->decimal('price_unit_value', 16, 6)->nullable()->after('price');
            $table->foreignId('price_unit_id')->nullable()->after('price_unit_value')->constrained('units')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['price_unit_id']);
            $table->dropColumn(['price', 'price_unit_value', 'price_unit_id']);
        });
    }
};
