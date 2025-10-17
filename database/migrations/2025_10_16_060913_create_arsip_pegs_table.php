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
        Schema::create('arsip_pegs', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 18)->unique(); // NIP pegawai, harus unik untuk setiap baris
            $table->string('drh_path')->nullable(); // Path ke file DRH
            $table->string('skcpns_path')->nullable(); // Path ke file SKCPNS
            $table->string('skpns_path')->nullable(); // Path ke file SKPNS
            $table->string('spmt_path')->nullable(); // Path ke file SPMT
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_pegs');
    }
};
