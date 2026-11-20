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
        Schema::create('program_management', function (Blueprint $table) {
            $table->id('id_program');
            $table->string('nama_program', 255);
            $table->string('penyelenggara', 255);
            $table->enum('bidang', ['Pendidikan', 'Essay', 'Bisnis', 'Teknologi']);
            $table->text('deskripsi')->nullable();
            $table->string('poster', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_management');
    }
};
