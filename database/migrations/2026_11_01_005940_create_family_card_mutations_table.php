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
        Schema::create('family_card_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('residents')->cascadeOnDelete();
            $table->foreignId('kk_asal')->constrained('family_cards')->cascadeOnDelete();
            $table->foreignId('kk_tujuan')->constrained('family_cards')->cascadeOnDelete();
            $table->string('hubungan', 50);
            $table->date('tgl_mutasi')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->index(['resident_id', 'tgl_mutasi']);
            $table->index(['kk_asal', 'kk_tujuan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_card_members');
    }
};
