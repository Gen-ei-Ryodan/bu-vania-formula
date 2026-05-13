<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('production_tabs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->constrained('productions')->cascadeOnDelete();
            $table->string('name');
            $table->unsignedBigInteger('input_weight_gram');
            $table->unsignedBigInteger('remaining_weight_gram');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_tabs');
    }
};
