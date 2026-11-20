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
            $table->string('nomor_identitas', 30)->nullable();
            $table->string('tipe_mutasi', 5);
            $table->string('kategori_mutasi', 15);
            $table->date('tgl_mutasi');
            $table->string('tujuan_mutasi', 255)->nullable();
            $table->string('catatan', 255)->nullable();
            $table->foreignId('resident_id')->nullable()->constrained('residents')->nullOnDelete();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['tipe_mutasi', 'tgl_mutasi']);
            $table->index('kategori_mutasi');
            $table->index('nomor_identitas');
            $table->index(['resident_id', 'tgl_mutasi']);
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
