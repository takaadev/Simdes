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
        Schema::create('village_regulations', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_peraturan', 100);
            $table->string('nomor_peraturan', 100)->unique();
            $table->date('tgl_ditetapkan');
            $table->string('nama_peraturan', 255)->unique();
            $table->longText('uraian')->nullable();
            $table->date('tgl_kesepakatan')->nullable();
            $table->string('nomor_dilaporkan', 100)->nullable()->unique();
            $table->date('tgl_dilaporkan')->nullable();
            $table->text('catatan')->nullable();
            $table->string('dokumen')->nullable();
            $table->string('status', 15)->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('jenis_peraturan');
            $table->index('status');
            $table->index('tgl_ditetapkan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_regulations');
    }
};
