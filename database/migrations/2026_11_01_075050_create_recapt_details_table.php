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
            $table->foreignId('recapt_id')->constrained('recapts')->cascadeOnDelete();
            $table->unsignedInteger('total_population')->default(0);
            $table->unsignedInteger('male_count')->default(0);
            $table->unsignedInteger('female_count')->default(0);
            $table->unsignedInteger('family_count')->default(0);
            $table->unsignedInteger('birth_count')->default(0);
            $table->unsignedInteger('death_count')->default(0);
            $table->unsignedInteger('incoming_count')->default(0);
            $table->unsignedInteger('outgoing_count')->default(0);
            $table->text('notes')->nullable();
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
