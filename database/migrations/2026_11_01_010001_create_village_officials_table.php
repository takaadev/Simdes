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

        Schema::create('village_officials', function (Blueprint $table) {
            $table->id();
            $table->string('register_number', 30)->unique();
            $table->string('name', 150);
            $table->string('nik', 16)->nullable()->unique();
            $table->string('gender', 2);
            $table->string('birth_place', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->foreignId('village_position_id')->nullable()->constrained('village_positions')->nullOnDelete();
            $table->string('appointment_number', 100)->nullable();
            $table->date('appointment_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('education', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('status', 30)->default('active');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('auth')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('name');
            $table->index('village_position_id');
            $table->index('status');
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_officials');
    }
};
