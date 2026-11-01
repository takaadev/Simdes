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
        Schema::create('resident_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->nullable()->constrained('residents')->nullOnDelete();
            $table->string('mutation_number', 50)->nullable();
            $table->string('mutation_type', 30);
            $table->date('mutation_date');
            $table->text('origin_address')->nullable();
            $table->foreignId('origin_dusun_id')->nullable()->constrained('dusuns')->nullOnDelete();
            $table->text('destination_address')->nullable();
            $table->foreignId('destination_dusun_id')->nullable()->constrained('dusuns')->nullOnDelete();
            $table->text('reason')->nullable();
            $table->string('document_number', 100)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['mutation_type', 'mutation_date']);
            $table->index('mutation_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resident_mutations');
    }
};
