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
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('time_zone', 50)->default('Asia/Jakarta');
            $table->string('kode_wilayah', 100);
            $table->string('nama_desa', 100);
            $table->string('kecamatan', 100);
            $table->string('kabupaten', 100);
            $table->string('provinsi', 100);
            $table->string('kode_pos', 10);
            $table->text('alamat_kantor');
            $table->string('telepon', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('nama_kades', 150)->nullable();
            $table->string('nama_sekdes', 150)->nullable();
            $table->integer('total_rw')->default(0);
            $table->integer('total_rt')->default(0);
            $table->string('jam_operasional', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
