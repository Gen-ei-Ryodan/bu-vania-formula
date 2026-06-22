<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('production_group_items', function (Blueprint $table) {
            $table->decimal('weight_input_value', 16, 4)->nullable()->after('weight_kg');
            $table->unsignedBigInteger('weight_input_unit_id')->nullable()->after('weight_input_value');
            $table->foreign('weight_input_unit_id')->references('id')->on('units')->nullOnDelete();
        });

        Schema::table('production_tab_items', function (Blueprint $table) {
            $table->decimal('weight_input_value', 16, 4)->nullable()->after('weight_kg');
            $table->unsignedBigInteger('weight_input_unit_id')->nullable()->after('weight_input_value');
            $table->foreign('weight_input_unit_id')->references('id')->on('units')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('production_group_items', function (Blueprint $table) {
            $table->dropForeign(['weight_input_unit_id']);
            $table->dropColumn('weight_input_unit_id');
            $table->dropColumn('weight_input_value');
        });

        Schema::table('production_tab_items', function (Blueprint $table) {
            $table->dropForeign(['weight_input_unit_id']);
            $table->dropColumn('weight_input_unit_id');
            $table->dropColumn('weight_input_value');
        });
    }
};
