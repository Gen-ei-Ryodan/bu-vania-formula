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
        Schema::create('production_tab_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tab_id')->index();
            $table->unsignedBigInteger('item_id')->index();
            $table->unsignedBigInteger('weight_gram');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_tab_items');
    }
};
