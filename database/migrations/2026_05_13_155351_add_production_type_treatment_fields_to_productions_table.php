<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->string('production_type')->default('biasa')->after('seed_name');
            $table->unsignedTinyInteger('treatment_day')->nullable()->after('production_type');
            $table->string('treatment_time')->nullable()->after('treatment_day');
            $table->date('mix_date')->nullable()->after('start_date');
            $table->dropColumn('end_date');
            $table->unsignedInteger('duration_days')->nullable()->after('start_date');
            $table->boolean('is_forever')->default(false)->after('duration_days');
        });
    }

    public function down(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->dropColumn('production_type');
            $table->dropColumn('treatment_day');
            $table->dropColumn('treatment_time');
            $table->dropColumn('mix_date');
            $table->dropColumn('duration_days');
            $table->dropColumn('is_forever');
            $table->date('end_date')->nullable()->after('start_date');
        });
    }
};
