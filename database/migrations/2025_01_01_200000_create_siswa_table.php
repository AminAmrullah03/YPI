<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lembaga_id')->constrained('lembaga')->cascadeOnDelete();
            $table->string('nis')->nullable();
            $table->string('nik')->nullable();
            $table->string('nama');
            $table->string('jenis_kelamin', 1); // L / P
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('telepon_wali')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->string('kelas')->nullable();
            $table->string('status')->default('aktif'); // aktif, tidak_aktif, lulus, pindah
            $table->string('foto')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
