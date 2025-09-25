<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formulirs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nik', 16);
            $table->enum('kelompok_jabatan', ['Tenaga Teknis', 'Tenaga Guru', 'Tenaga Kesehatan']);
            $table->string('skck')->nullable();
            $table->string('suket_sehat')->nullable();
            $table->json('ijazah')->nullable(); // Untuk multiple files
            $table->json('transkrip_nilai')->nullable(); // Untuk multiple files
            $table->string('surat_pernyataan')->nullable();
            $table->string('pas_foto')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('email');
            $table->string('no_whatsapp');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formulirs');
    }
};
