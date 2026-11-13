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
       Schema::create('temporary_residents', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 150);
            $table->string('jenis_kelamin', 2); // Contoh: 'L' atau 'P'
            $table->string('nomor_identitas', 30)->nullable(); // Diperbesar untuk antisipasi nomor paspor WNA
            $table->string('tempat_lahir', 100);
            $table->date('tgl_lahir');
            $table->unsignedInteger('umur')->default(0);
            $table->string('pekerjaan', 100);
            $table->string('kewarganegaraan', 10); 
            $table->string('kebangsaan', 100)->nullable(); 
            $table->string('keturunan', 50)->nullable();
            $table->string('datang_dari', 150);
            $table->string('tujuan_kedatangan', 255);
            $table->text('alamat_tujuan'); 
            $table->date('tgl_datang');
            $table->date('tgl_pergi')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['tgl_datang', 'tgl_pergi']);
            $table->index('nama_lengkap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_residents');
    }
};
