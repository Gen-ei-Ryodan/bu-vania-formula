<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('concept_items', function (Blueprint $table) {
            $table->unsignedBigInteger('weight_gram')->nullable()->after('percentage');
        });
    }

    public function down(): void
    {
        Schema::table('concept_items', function (Blueprint $table) {
            $table->dropColumn('weight_gram');
        });
    }
};
