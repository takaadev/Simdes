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
        Schema::create('expedition_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agenda_entry_id')->nullable()->constrained('agenda_entries')->nullOnDelete();
            $table->date('tanggal_kirim');
            $table->string('nomor_surat', 100)->nullable();
            $table->date('tgl_surat')->nullable();
            $table->string('penerima', 150);
            $table->text('isi_surat');
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('tanggal_kirim');
            $table->index('nomor_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedition_entries');
    }
};
