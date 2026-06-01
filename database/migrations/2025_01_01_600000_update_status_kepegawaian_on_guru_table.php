<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update data lama jika ada
        DB::table('guru')->where('status_kepegawaian', 'honorer')->update(['status_kepegawaian' => 'tidak_tetap']);
        DB::table('guru')->where('status_kepegawaian', 'magang')->update(['status_kepegawaian' => 'karyawan']);

        // Ubah default column
        Schema::table('guru', function (Blueprint $table) {
            $table->string('status_kepegawaian')->default('tidak_tetap')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->string('status_kepegawaian')->default('honorer')->change();
        });

        DB::table('guru')->where('status_kepegawaian', 'tidak_tetap')->update(['status_kepegawaian' => 'honorer']);
        DB::table('guru')->where('status_kepegawaian', 'karyawan')->update(['status_kepegawaian' => 'magang']);
    }
};
