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
        Schema::create('respondens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_layanan_opd')->constrained('layanan_opd');
            $table->foreignId('id_pendidikan')->constrained('master_pendidikan');
            $table->foreignId('id_pekerjaan')->constrained('master_pekerjaan');
            $table->string('name')->default('anonim');
            $table->double('umur');
            $table->enum('gender',['laki-laki','perempuan']);
            $table->boolean('is_active')->default(1);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respondens');
    }
};
