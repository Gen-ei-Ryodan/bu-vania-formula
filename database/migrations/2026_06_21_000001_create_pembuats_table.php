<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembuats', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('concepts', function (Blueprint $table) {
            $table->foreignId('pembuat_id')->nullable()->after('name')->constrained('pembuats')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('concepts', function (Blueprint $table) {
            $table->dropForeign(['pembuat_id']);
            $table->dropColumn('pembuat_id');
        });

        Schema::dropIfExists('pembuats');
    }
};
