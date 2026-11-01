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


        Schema::create('village_treasury_lands', function (Blueprint $table) {
            $table->id();
            $table->string('land_origin', 100);
            $table->string('certificate_number', 100)->nullable();
            $table->decimal('land_area', 15, 2);
            $table->string('land_class', 30)->nullable();
            $table->string('acquisition', 100);
            $table->date('acquisition_date')->nullable();
            $table->string('land_type', 50);
            $table->boolean('has_marker')->default(false);
            $table->boolean('has_plate')->default(false);
            $table->text('land_location');
            $table->string('land_use', 150)->nullable();
            $table->text('mutation')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('land_origin');
            $table->index('land_type');
            $table->index('acquisition_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_treasury_lands');
    }
};
