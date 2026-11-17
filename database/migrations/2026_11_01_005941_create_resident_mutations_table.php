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
            $table->string('nomor_identitas', 30)->nullable()->unique(); //jika wna dll
            $table->string('nik', 20)->nullable()->unique();
            $table->string('kk', 20)->nullable();
            $table->string('nama_lengkap', 150);
            $table->string('jenis_kelamin', 2);
            $table->string('tempat_lahir', 100);
            $table->date('tgl_lahir');
            $table->boolean('dapat_membaca')->default(false);
            $table->string('status_perkawinan', 30);
            $table->string('status_dalam_keluarga', 30);
            $table->string('agama', 30);
            $table->string('pendidikan', 50);
            $table->string('pekerjaan', 100);

            $table->string('kewarganegaraan', 10);
            $table->string('kebangsaan', 100);

            $table->string('gol_darah', 6)->nullable();
            $table->string('ayah', 150)->nullable();
            $table->string('ibu', 150)->nullable();

            $table->string('tipe_mutasi', 5);
            $table->string('kategori_mutasi', 15);
            $table->date('tgl_mutasi');
            $table->string('tujuan_mutasi')->nullable();

            $table->string('rt_tujuan', 10)->nullable();
            $table->string('rw_tujuan', 10)->nullable();
            $table->text('alamat')->nullable();
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
