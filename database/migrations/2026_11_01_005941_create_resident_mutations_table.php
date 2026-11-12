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
        Schema::create('resident_mutations', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 150);
            $table->string('tempat_lahir', 100);
            $table->date('tgl_lahir');
            $table->string('jenis_kelamin', 2);
            $table->string('kewarganegaraan', 10);
            $table->string('kebangsaan', 100);
            $table->string('tipe_mutasi', 5);
            $table->string('kategori_mutasi', 15);
            $table->date('tgl_mutasi');
            $table->string('tujuan_mutasi')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('resident_id')->nullable()->unique()->constrained('residents')->nullOnDelete();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['tipe_mutasi', 'tgl_mutasi']);
            $table->index('kategori_mutasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resident_mutations');
    }
};
