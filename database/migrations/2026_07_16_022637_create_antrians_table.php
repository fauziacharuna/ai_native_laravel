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
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dinas_id')->constrained('dinas')->onDelete('cascade');
            $table->foreignId('layanan_id')->nullable()->constrained('layanans')->onDelete('set null');
            $table->string('nomor_antrian');
            $table->integer('nomor_urut');
            $table->date('tanggal');
            $table->string('nama_pemohon');
            $table->string('nomor_hp')->nullable();
            $table->enum('tipe_pendaftaran', ['online', 'offline'])->default('offline');
            $table->enum('status', ['waiting', 'calling', 'serving', 'skipped', 'completed'])->default('waiting');
            $table->string('loket_nomor')->nullable();
            $table->timestamp('waktu_ambil')->useCurrent();
            $table->timestamp('waktu_panggil')->nullable();
            $table->timestamp('waktu_mulai_layanan')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};
