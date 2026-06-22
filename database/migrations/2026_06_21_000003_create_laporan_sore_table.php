<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_sore', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained('locations')->restrictOnDelete();
            $table->date('tanggal');
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('laporan_sore_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_sore_id')->constrained('laporan_sore')->cascadeOnDelete();
            $table->string('section'); // sisa_kemarin, campuran_hari_ini, kirim_hari_ini, stock
            $table->foreignId('cage_id')->nullable()->constrained('cages')->nullOnDelete();
            $table->string('nama_tali')->nullable();
            $table->foreignId('konsep_id')->constrained('concepts')->restrictOnDelete();
            $table->decimal('jumlah', 14, 2);
            $table->string('satuan');
            $table->timestamps();
        });

        Schema::create('laporan_sore_detail_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_sore_detail_id')->constrained('laporan_sore_details')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_sore_detail_items');
        Schema::dropIfExists('laporan_sore_details');
        Schema::dropIfExists('laporan_sore');
    }
};
