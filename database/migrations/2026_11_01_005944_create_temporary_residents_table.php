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
        Schema::create('temporary_residents', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number', 30)->unique();
            $table->string('full_name', 150);
            $table->string('gender', 2);
            $table->string('nationality', 100)->nullable();
            $table->string('ethnicity', 100)->nullable();
            $table->string('birth_place', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('occupation', 100)->nullable();
            $table->string('citizenship', 10)->nullable();
            $table->text('origin_address')->nullable();
            $table->text('visit_purpose')->nullable();
            $table->string('destination_name', 150)->nullable();
            $table->text('destination_address')->nullable();
            $table->date('arrival_date');
            $table->date('departure_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['arrival_date', 'departure_date']);
            $table->index('full_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_residents');
    }
};
