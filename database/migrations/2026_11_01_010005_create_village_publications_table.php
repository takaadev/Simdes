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
            $table->string('regulation_type', 50);
            $table->string('regulation_number', 100);
            $table->date('stipulation_date');
            $table->text('subject');
            $table->date('promulgation_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('regulation_type');
            $table->index('stipulation_date');
            $table->index('promulgation_date');
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
