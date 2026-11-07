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

        Schema::create('village_treasury_lands', function (Blueprint $table) {
            $table->id();
            $table->string('asal_tanah', 100);
            $table->string('nomor_sertifikat', 100)->unique();
            $table->string('kelas_tanah', 30);
            $table->decimal('luas_tanah', 15, 2)->default(0);
            $table->decimal('lt_asli_desa', 15, 2)->default(0);
            $table->decimal('lt_pemerintah', 15, 2)->default(0);
            $table->decimal('lt_provinsi', 15, 2)->default(0);
            $table->decimal('lt_kabupaten', 15, 2)->default(0);
            $table->decimal('lt_lainnya', 15, 2)->default(0);
            $table->date('tgl_perolehan')->nullable();
            $table->decimal('lt_tipe_sawah', 15, 2)->default(0);
            $table->decimal('lt_tipe_tegal', 15, 2)->default(0);
            $table->decimal('lt_tipe_kebun', 15, 2)->default(0);
            $table->decimal('lt_tipe_tambak', 15, 2)->default(0);
            $table->decimal('lt_tipe_kering', 15, 2)->default(0);
            $table->boolean('patok_tanah')->default(false);
            $table->boolean('papan_nama')->default(false);
            $table->text('lokasi');
            $table->string('peruntukan', 255)->nullable();
            $table->text('mutasi')->nullable();
            $table->text('catatan')->nullable();
            $table->string('dokumen')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_treasury_lands');
    }
};
