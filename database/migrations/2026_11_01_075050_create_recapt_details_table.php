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
        Schema::create('recapt_details', function (Blueprint $table) {
            $table->id();
            $table->string('lingkungan', 150); //seharunya perdusun nama tapi sekrang belum membuat fitur dusun jadi kita buat perRT, karena Tiaro ini hanya satu dusun dan RW nya 00O
            $table->foreignId('recapt_id')->constrained('recapts')->cascadeOnDelete();
            // Awal Bulan
            $table->unsignedInteger('awb_wni_l')->default(0);
            $table->unsignedInteger('awb_wni_p')->default(0);
            $table->unsignedInteger('awb_wna_l')->default(0);
            $table->unsignedInteger('awb_wna_p')->default(0);
            $table->unsignedInteger('awb_jml_kk')->default(0);
            $table->unsignedInteger('awb_jml_anggota_kk')->default(0);
            $table->unsignedInteger('awb_jml_penduduk')->default(0);
            // Penambahan Bulan ini
            $table->unsignedInteger('tbl_wni_l')->default(0); //Lahir
            $table->unsignedInteger('tbl_wni_p')->default(0);
            $table->unsignedInteger('tbl_wna_l')->default(0);
            $table->unsignedInteger('tbl_wna_p')->default(0);
            $table->unsignedInteger('tbd_wni_l')->default(0); //Datang
            $table->unsignedInteger('tbd_wni_p')->default(0);
            $table->unsignedInteger('tbd_wna_l')->default(0);
            $table->unsignedInteger('tbd_wna_p')->default(0);
            // Pengurangan Bulan ini
            $table->unsignedInteger('pbm_wni_l')->default(0); //Meninggal
            $table->unsignedInteger('pbm_wni_p')->default(0);
            $table->unsignedInteger('pbm_wna_l')->default(0);
            $table->unsignedInteger('pbm_wna_p')->default(0);
            $table->unsignedInteger('pbp_wni_l')->default(0); //Pindah
            $table->unsignedInteger('pbp_wni_p')->default(0);
            $table->unsignedInteger('pbp_wna_l')->default(0);
            $table->unsignedInteger('pbp_wna_p')->default(0);
            // Ahir Bulan
            $table->unsignedInteger('ahb_wni_l')->default(0);
            $table->unsignedInteger('ahb_wni_p')->default(0);
            $table->unsignedInteger('ahb_wna_l')->default(0);
            $table->unsignedInteger('ahb_wna_p')->default(0);
            $table->unsignedInteger('ahb_jml_kk')->default(0);
            $table->unsignedInteger('ahb_jml_anggota_kk')->default(0);
            $table->unsignedInteger('ahb_jml_penduduk')->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->index('recapt_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recapt_details');
    }
};
