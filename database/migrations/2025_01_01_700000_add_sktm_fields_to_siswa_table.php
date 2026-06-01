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
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('status_sktm')->default('none')->after('status'); // none, pending, approved, rejected
            $table->string('dokumen_sktm')->nullable()->after('status_sktm'); // path to uploaded sktm file
            $table->text('keterangan_sktm')->nullable()->after('dokumen_sktm'); // rejection reason note
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn(['status_sktm', 'dokumen_sktm', 'keterangan_sktm']);
        });
    }
};
