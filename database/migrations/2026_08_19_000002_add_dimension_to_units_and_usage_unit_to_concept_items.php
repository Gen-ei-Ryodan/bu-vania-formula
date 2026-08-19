<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->string('dimension', 20)->default('mass')->after('name');
        });

        Schema::table('concept_items', function (Blueprint $table) {
            $table->foreignId('weight_unit_id')->nullable()->after('weight_kg')->constrained('units')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('concept_items', function (Blueprint $table) {
            $table->dropForeign(['weight_unit_id']);
            $table->dropColumn('weight_unit_id');
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('dimension');
        });
    }
};
