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
        Schema::create('surveis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tracking_id')->nullable()->constrained('trackings')->onDelete('set null');
            $table->foreignId('antrian_id')->nullable()->constrained('antrians')->onDelete('set null');
            $table->foreignId('dinas_id')->constrained('dinas')->onDelete('cascade');
            $table->foreignId('layanan_id')->constrained('layanans')->onDelete('cascade');
            $table->integer('skor_kepuasan'); // 1-5
            $table->text('ulasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveis');
    }
};
