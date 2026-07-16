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
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('antrian_id')->nullable()->constrained('antrians')->onDelete('set null');
            $table->foreignId('dinas_id')->constrained('dinas')->onDelete('cascade');
            $table->foreignId('layanan_id')->constrained('layanans')->onDelete('cascade');
            $table->string('nomor_lacak')->unique();
            $table->string('nama_pemohon');
            $table->string('nomor_hp')->nullable();
            $table->enum('status_sekarang', ['submitted', 'in_process', 'hold', 'ready', 'completed'])->default('submitted');
            $table->text('catatan_terakhir')->nullable();
            $table->dateTime('tanggal_selesai_estimasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trackings');
    }
};
