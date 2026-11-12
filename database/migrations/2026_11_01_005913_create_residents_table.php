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
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 150);
            $table->string('jenis_kelamin', 2);
            $table->string('nik', 16)->unique();
            $table->string('kk', 16);
            $table->string('tempat_lahir', 100);
            $table->date('tgl_lahir');
            $table->string('status_perkawinan', 30);
            $table->string('agama', 30);
            $table->string('pendidikan', 50);
            $table->string('pekerjaan', 100);
            $table->boolean('dapat_membaca')->default(false);
            $table->string('kewarganegaraan', 10);
            $table->string('kebangsaan', 100);
            $table->string('status_dalam_keluarga', 30);
            $table->string('rt', 10);
            $table->string('rw', 10);
            $table->foreignId('dusun_id')->constrained('dusuns')->restrictOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('kk');
            $table->index(['dusun_id', 'rt', 'rw']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
