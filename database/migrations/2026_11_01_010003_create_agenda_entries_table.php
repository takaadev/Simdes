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
        Schema::create('agenda_entries', function (Blueprint $table) {
            $table->id();
            $table->string('tipe_agenda', 20);
            $table->string('kategori_agenda', 100)->nullable();
            $table->date('tanggal_terima')->nullable();
            $table->date('tanggal_kirim')->nullable();
            $table->string('nomor_surat', 100)->nullable();
            $table->date('tgl_surat')->nullable();
            $table->string('pengirim', 150)->nullable();
            $table->string('penerima', 150)->nullable();
            $table->text('isi_surat');
            $table->text('disposisi')->nullable();
            $table->date('tgl_disposisi')->nullable();
            $table->string('dokumen', 255)->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['tipe_agenda', 'tanggal_terima']);
            $table->index(['tipe_agenda', 'tanggal_kirim']);
            $table->index('nomor_surat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda_entries');
    }
};
