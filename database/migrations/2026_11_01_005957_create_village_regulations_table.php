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
            $table->string('register_number', 30)->unique();
            $table->string('regulation_number', 100);
            $table->string('regulation_type', 100);
            $table->string('regulation_title', 255);
            $table->date('stipulation_date');
            $table->date('promulgation_date')->nullable();
            $table->text('subject')->nullable();
            $table->string('document_file')->nullable();
            $table->string('status', 30)->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('regulation_type');
            $table->index('status');
            $table->index('stipulation_date');
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
