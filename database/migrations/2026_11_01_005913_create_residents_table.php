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
            $table->id();            // Identitas Penduduk
            $table->string('nama_lengkap', 150);
            $table->string('jenis_kelamin', 2);
            $table->string('nik', 16)->unique();
            $table->string('kk', 16);
            // Data Kelahiran
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tgl_lahir')->nullable();
            // Data Pribadi
            $table->string('status_perkawinan', 30)->nullable();
            $table->string('agama', 30)->nullable();
            $table->string('pendidikan', 50)->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->boolean('dapat_membaca')->default(false);
            // Kewarganegaraan dan Keluarga
            $table->string('kewarganegaraan', 10)->default('WNI');
            $table->string('kebangsaan', 100)->default('Indonesia');
            $table->string('status_dalam_keluarga', 30)->nullable();
            // Alamat
            $table->string('rt', 10)->nullable();
            $table->string('rw', 10)->nullable();
            $table->foreignId('dusun_id')->nullable()->constrained('dusuns')->nullOnDelete();
            // Keterangan
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
