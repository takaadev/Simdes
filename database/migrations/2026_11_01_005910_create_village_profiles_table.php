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
        Schema::create('village_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('village_name', 100);
            $table->string('district', 100);
            $table->string('regency', 100);
            $table->string('province', 100);
            $table->string('postal_code', 10);
            $table->text('office_address');
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->string('village_head_name', 150);
            $table->string('village_logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_profiles');
    }
};
