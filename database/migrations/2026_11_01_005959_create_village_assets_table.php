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
        Schema::create('village_assets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_asset', 150);
            $table->integer('asal_beli')->default(0);
            $table->integer('asal_pemerintah')->default(0);
            $table->integer('asal_provinsi')->default(0);
            $table->integer('asal_kabupaten')->default(0);
            $table->integer('asal_sumbangan')->default(0);
            $table->integer('kondisi_awal_baik')->default(0);
            $table->integer('kondisi_awal_rusak')->default(0);
            $table->integer('hapus_rusak')->default(0);
            $table->integer('hapus_dijual')->default(0);
            $table->integer('hapus_disumbangkan')->default(0);
            $table->date('tgl_hapus')->nullable();
            $table->integer('kondisi_ahir_baik')->default(0);
            $table->integer('kondisi_ahir_rusak')->default(0);
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
        Schema::dropIfExists('village_assets');
    }
};
