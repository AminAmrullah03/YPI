<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lembaga', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jenis'); // TPQ, KB_TK, SD, SMP, SMA, MA
            $table->text('alamat')->nullable();
            $table->string('kepala')->nullable();
            $table->string('telepon')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tambah FK lembaga_id ke users setelah lembaga table dibuat
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('lembaga_id')
                ->references('id')
                ->on('lembaga')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['lembaga_id']);
        });
        Schema::dropIfExists('lembaga');
    }
};
