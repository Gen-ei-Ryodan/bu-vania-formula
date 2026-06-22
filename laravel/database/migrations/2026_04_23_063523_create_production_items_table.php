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
        Schema::create('production_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_id')->index();
            $table->unsignedBigInteger('item_id')->index();
            $table->unsignedBigInteger('weight_gram');
            $table->string('source')->default('concept');
            $table->unique(['production_id', 'item_id', 'source']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_items');
    }
};
