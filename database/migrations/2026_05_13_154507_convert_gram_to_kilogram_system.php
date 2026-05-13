<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->decimal('conversion_to_kg', 16, 6)->default(1)->after('name');
        });
        DB::statement('UPDATE units SET conversion_to_kg = conversion_to_gram / 1000.0');
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('conversion_to_gram');
        });

        Schema::table('concepts', function (Blueprint $table) {
            $table->decimal('base_weight_kg', 16, 4)->default(0)->after('name');
        });
        DB::statement('UPDATE concepts SET base_weight_kg = base_weight_gram / 1000.0');
        Schema::table('concepts', function (Blueprint $table) {
            $table->dropColumn('base_weight_gram');
        });

        Schema::table('concept_items', function (Blueprint $table) {
            $table->decimal('weight_kg', 16, 4)->nullable()->after('percentage');
        });
        DB::statement('UPDATE concept_items SET weight_kg = weight_gram / 1000.0');
        Schema::table('concept_items', function (Blueprint $table) {
            $table->dropColumn('weight_gram');
        });

        Schema::table('productions', function (Blueprint $table) {
            $table->decimal('target_weight_kg', 16, 4)->default(0)->after('concept_id');
        });
        DB::statement('UPDATE productions SET target_weight_kg = target_weight_gram / 1000.0');
        Schema::table('productions', function (Blueprint $table) {
            $table->dropColumn('target_weight_gram');
        });

        Schema::table('production_items', function (Blueprint $table) {
            $table->decimal('weight_kg', 16, 4)->default(0)->after('item_id');
        });
        DB::statement('UPDATE production_items SET weight_kg = weight_gram / 1000.0');
        Schema::table('production_items', function (Blueprint $table) {
            $table->dropColumn('weight_gram');
        });

        Schema::table('production_group_items', function (Blueprint $table) {
            $table->decimal('weight_kg', 16, 4)->default(0)->after('item_id');
        });
        DB::statement('UPDATE production_group_items SET weight_kg = weight_gram / 1000.0');
        Schema::table('production_group_items', function (Blueprint $table) {
            $table->dropColumn('weight_gram');
        });

        Schema::table('production_tabs', function (Blueprint $table) {
            $table->decimal('input_weight_kg', 16, 4)->default(0)->after('name');
            $table->decimal('remaining_weight_kg', 16, 4)->default(0)->after('input_weight_kg');
        });
        DB::statement('UPDATE production_tabs SET input_weight_kg = input_weight_gram / 1000.0, remaining_weight_kg = remaining_weight_gram / 1000.0');
        Schema::table('production_tabs', function (Blueprint $table) {
            $table->dropColumn('input_weight_gram');
            $table->dropColumn('remaining_weight_gram');
        });

        Schema::table('production_tab_items', function (Blueprint $table) {
            $table->decimal('weight_kg', 16, 4)->default(0)->after('item_id');
        });
        DB::statement('UPDATE production_tab_items SET weight_kg = weight_gram / 1000.0');
        Schema::table('production_tab_items', function (Blueprint $table) {
            $table->dropColumn('weight_gram');
        });
    }

    public function down(): void
    {
        Schema::table('production_tab_items', function (Blueprint $table) {
            $table->unsignedBigInteger('weight_gram')->default(0)->after('item_id');
        });
        DB::statement('UPDATE production_tab_items SET weight_gram = ROUND(weight_kg * 1000)');
        Schema::table('production_tab_items', function (Blueprint $table) {
            $table->dropColumn('weight_kg');
        });

        Schema::table('production_tabs', function (Blueprint $table) {
            $table->unsignedBigInteger('input_weight_gram')->default(0)->after('name');
            $table->unsignedBigInteger('remaining_weight_gram')->default(0)->after('input_weight_gram');
        });
        DB::statement('UPDATE production_tabs SET input_weight_gram = ROUND(input_weight_kg * 1000), remaining_weight_gram = ROUND(remaining_weight_kg * 1000)');
        Schema::table('production_tabs', function (Blueprint $table) {
            $table->dropColumn('input_weight_kg');
            $table->dropColumn('remaining_weight_kg');
        });

        Schema::table('production_group_items', function (Blueprint $table) {
            $table->unsignedBigInteger('weight_gram')->default(0)->after('item_id');
        });
        DB::statement('UPDATE production_group_items SET weight_gram = ROUND(weight_kg * 1000)');
        Schema::table('production_group_items', function (Blueprint $table) {
            $table->dropColumn('weight_kg');
        });

        Schema::table('production_items', function (Blueprint $table) {
            $table->unsignedBigInteger('weight_gram')->default(0)->after('item_id');
        });
        DB::statement('UPDATE production_items SET weight_gram = ROUND(weight_kg * 1000)');
        Schema::table('production_items', function (Blueprint $table) {
            $table->dropColumn('weight_kg');
        });

        Schema::table('productions', function (Blueprint $table) {
            $table->unsignedBigInteger('target_weight_gram')->default(0)->after('concept_id');
        });
        DB::statement('UPDATE productions SET target_weight_gram = ROUND(target_weight_kg * 1000)');
        Schema::table('productions', function (Blueprint $table) {
            $table->dropColumn('target_weight_kg');
        });

        Schema::table('concept_items', function (Blueprint $table) {
            $table->unsignedBigInteger('weight_gram')->nullable()->after('percentage');
        });
        DB::statement('UPDATE concept_items SET weight_gram = ROUND(weight_kg * 1000)');
        Schema::table('concept_items', function (Blueprint $table) {
            $table->dropColumn('weight_kg');
        });

        Schema::table('concepts', function (Blueprint $table) {
            $table->unsignedBigInteger('base_weight_gram')->default(0)->after('name');
        });
        DB::statement('UPDATE concepts SET base_weight_gram = ROUND(base_weight_kg * 1000)');
        Schema::table('concepts', function (Blueprint $table) {
            $table->dropColumn('base_weight_kg');
        });

        Schema::table('units', function (Blueprint $table) {
            $table->unsignedBigInteger('conversion_to_gram')->default(1)->after('name');
        });
        DB::statement('UPDATE units SET conversion_to_gram = ROUND(conversion_to_kg * 1000)');
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('conversion_to_kg');
        });
    }
};
