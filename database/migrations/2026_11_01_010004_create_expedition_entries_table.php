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
        Schema::create('expedition_entries', function (Blueprint $table) {
            $table->id();
            $table->date('sent_date');
            $table->string('letter_number', 100)->nullable();
            $table->date('letter_date')->nullable();
            $table->text('summary');
            $table->string('recipient', 150);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('sent_date');
            $table->index('letter_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedition_entries');
    }
};
