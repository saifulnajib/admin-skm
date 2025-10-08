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
        Schema::create('jawaban_survey', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_responden')->constrained('respondens');
            $table->foreignId('id_survey')->constrained('survey');
            $table->foreignId('id_pilihan_jawaban')->constrained('pilihan_jawaban');
            $table->double('bobot')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_survey');
    }
};
