<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules_management', function (Blueprint $table) {
            $table->increments('id_schedule');
            $table->foreignId('id_program')->constrained('program_management', 'id_program')->onDelete('cascade');
            $table->enum('status', ['Akan Datang', 'Sedang Berjalan', 'Selesai'])->default('Akan Datang');
            $table->string('stage_name', 50);
            $table->date('start_date');
            $table->date('end_date');
            $table->date('event_date');
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('schedules_management');
    }
};
