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
        Schema::create('village_decrees', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_keputusan', 30)->unique();
            $table->date('tgl_keputusan');
            $table->string('nama_keputusan', 255);
            $table->longText('uraian')->nullable();
            $table->string('nomor_dilaporkan', 100)->nullable()->unique();
            $table->date('tgl_dilaporkan')->nullable();
            $table->string('dokumen')->nullable();
            $table->string('status', 20)->default('draft');
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_decrees');
    }
};
