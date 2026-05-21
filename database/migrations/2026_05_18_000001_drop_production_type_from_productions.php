<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            if (Schema::hasColumn('productions', 'production_type')) {
                $table->dropColumn('production_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            if (!Schema::hasColumn('productions', 'production_type')) {
                $table->string('production_type')->default('biasa')->after('seed_name');
            }
        });
    }
};
