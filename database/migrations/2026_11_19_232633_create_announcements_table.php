<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->increments('id_announcement');
            $table->string('title', 255);
            $table->text('content');
            $table->enum('category', ['Penting', 'Jadwal', 'Hasil Seleksi', 'Umum'])->default('Umum');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};