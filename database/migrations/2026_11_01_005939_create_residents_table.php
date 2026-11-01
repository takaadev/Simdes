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
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('name', 150);
            $table->string('gender', 2);
            $table->string('birth_place', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('blood_type', 3)->nullable();
            $table->string('religion', 30)->nullable();
            $table->string('education', 100)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->string('marital_status', 30)->nullable();
            $table->string('citizenship', 10)->default('WNI');
            $table->string('nationality', 100)->nullable();
            $table->string('father_name', 150)->nullable();
            $table->string('mother_name', 150)->nullable();
            $table->foreignId('dusun_id')->nullable()->constrained('dusuns')->nullOnDelete();
            $table->text('address')->nullable();
            $table->string('rt', 6)->nullable();
            $table->string('rw', 6)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->date('moved_in_date')->nullable();
            $table->date('moved_out_date')->nullable();
            $table->string('resident_status', 30)->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
