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
            $table->string('register_number', 30)->unique();
            $table->string('decree_number', 100);
            $table->string('decree_title', 255);
            $table->date('stipulation_date');
            $table->text('subject')->nullable();
            $table->string('related_person', 150)->nullable();
            $table->string('document_file')->nullable();
            $table->string('status', 20)->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
            $table->index('stipulation_date');
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
