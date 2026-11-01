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
        Schema::create('recapts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('period_type', 20);
            $table->date('period_start');
            $table->date('period_end');
            $table->date('snapshot_date');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['period_type', 'period_start', 'period_end'], 'recapt_id');
            $table->index('snapshot_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recapts');
    }
};
