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
        Schema::create('village_assets', function (Blueprint $table) {
            $table->id();
            $table->string('register_number', 30)->unique();
            $table->string('asset_code', 50)->nullable();
            $table->string('asset_name', 150);
            $table->string('asset_category', 100);
            $table->date('acquisition_date')->nullable();
            $table->string('acquisition_source', 100)->nullable();
            $table->decimal('acquisition_value', 15, 2)->nullable();
            $table->decimal('quantity', 12, 2)->default(1);
            $table->string('unit', 30)->nullable();
            $table->string('condition', 30)->nullable();
            $table->text('location')->nullable();
            $table->string('ownership_status', 50)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('asset_category');
            $table->index('asset_name');
            $table->index('condition');
            $table->index('ownership_status');
            $table->index('acquisition_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_assets');
    }
};
