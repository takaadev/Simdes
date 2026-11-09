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
        Schema::create('village_publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regulation_id')->unique()->constrained('village_regulations')->cascadeOnDelete();
            $table->string('nomor_diundang', 100)->nullable()->unique();
            $table->date('tgl_diundang')->nullable();
            $table->string('nomor_berita', 100)->nullable()->unique();
            $table->date('tgl_berita')->nullable();
            $table->string('status', 15)->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
            $table->index('tgl_diundang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_publications');
    }
};
