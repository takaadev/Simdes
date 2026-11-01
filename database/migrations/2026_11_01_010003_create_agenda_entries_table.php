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
        Schema::create('agenda_entries', function (Blueprint $table) {
            $table->id();
            $table->string('register_number', 30);
            $table->string('agenda_type', 10);
            $table->string('agenda_category', 100);
            $table->string('letter_number', 100)->nullable();
            $table->date('letter_date')->nullable();
            $table->date('received_date')->nullable();
            $table->string('sender', 150)->nullable();
            $table->string('recipient', 150)->nullable();
            $table->text('subject');
            $table->text('disposition')->nullable();
            $table->date('disposition_date')->nullable();
            $table->string('document_file', 255)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['register_number', 'agenda_type']);
            $table->index(['agenda_type', 'received_date']);
            $table->index('letter_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_entries');
    }
};
