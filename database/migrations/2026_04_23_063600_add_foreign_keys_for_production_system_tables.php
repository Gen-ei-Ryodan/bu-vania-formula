<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('concept_items', function (Blueprint $table) {
            $table->foreign('concept_id')->references('id')->on('concepts')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('restrict');
        });

        Schema::table('production_items', function (Blueprint $table) {
            $table->foreign('production_id')->references('id')->on('productions')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('restrict');
        });

        Schema::table('production_group_items', function (Blueprint $table) {
            $table->foreign('group_id')->references('id')->on('production_groups')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('restrict');
        });

        Schema::table('production_tab_items', function (Blueprint $table) {
            $table->foreign('tab_id')->references('id')->on('production_tabs')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('concept_items', function (Blueprint $table) {
            $table->dropForeign(['concept_id']);
            $table->dropForeign(['item_id']);
        });

        Schema::table('production_items', function (Blueprint $table) {
            $table->dropForeign(['production_id']);
            $table->dropForeign(['item_id']);
        });

        Schema::table('production_group_items', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropForeign(['item_id']);
        });

        Schema::table('production_tab_items', function (Blueprint $table) {
            $table->dropForeign(['tab_id']);
            $table->dropForeign(['item_id']);
        });
    }
};
