<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('production_group_items', function (Blueprint $table) {
            $table->boolean('is_dosis')->default(false)->after('weight_kg');
        });
        Schema::table('production_tab_items', function (Blueprint $table) {
            $table->boolean('is_dosis')->default(false)->after('weight_kg');
        });
    }

    public function down(): void
    {
        Schema::table('production_group_items', function (Blueprint $table) {
            $table->dropColumn('is_dosis');
        });
        Schema::table('production_tab_items', function (Blueprint $table) {
            $table->dropColumn('is_dosis');
        });
    }
};
