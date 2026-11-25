<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('announcements') || Schema::hasColumn('announcements', 'id_program')) {
            return;
        }

        $isBigInt = true;
        if (Schema::hasTable('program_management')) {
            $col = DB::selectOne("SHOW COLUMNS FROM program_management WHERE Field = 'id_program'");
            if ($col && isset($col->Type) && !str_contains(strtolower($col->Type), 'bigint')) {
                $isBigInt = false;
            }
        }

        Schema::table('announcements', function (Blueprint $table) use ($isBigInt) {
            if ($isBigInt) {
                $table->unsignedBigInteger('id_program')->nullable()->after('id_announcement');
            } else {
                $table->unsignedInteger('id_program')->nullable()->after('id_announcement');
            }

            if (Schema::hasTable('program_management')) {
                $table->foreign('id_program')
                    ->references('id_program')
                    ->on('program_management')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('announcements') && Schema::hasColumn('announcements', 'id_program')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->dropForeign(['id_program']);
                $table->dropColumn('id_program');
            });
        }
    }
};