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

        Schema::create('village_lands', function (Blueprint $table) {
            $table->id();
            // Nama Perorangan / Badan Hukum
            $table->string('owner_name', 150);
            // Jumlah Luas Tanah (m²)
            $table->decimal('total_area', 15, 2)->default(0);
            // Status Hak Atas Tanah (m²)
            $table->decimal('hm', 15, 2)->default(0);
            $table->decimal('hgb', 15, 2)->default(0);
            $table->decimal('hp', 15, 2)->default(0);
            $table->decimal('hgu', 15, 2)->default(0);
            $table->decimal('hpl', 15, 2)->default(0);
            // Status Tanah Lainnya (m²)
            $table->decimal('ma', 15, 2)->default(0);
            $table->decimal('vi', 15, 2)->default(0);
            $table->decimal('tn', 15, 2)->default(0);
            // Peruntukan Tanah (m²)
            $table->decimal('housing', 15, 2)->default(0);
            $table->decimal('trade_services', 15, 2)->default(0);
            $table->decimal('office', 15, 2)->default(0);
            $table->decimal('industry', 15, 2)->default(0);
            $table->decimal('public_facilities', 15, 2)->default(0);
            // Penggunaan Tanah (m²)
            $table->decimal('paddy_field', 15, 2)->default(0);
            $table->decimal('dry_field', 15, 2)->default(0);
            $table->decimal('plantation', 15, 2)->default(0);
            $table->decimal('livestock_fishery', 15, 2)->default(0);
            $table->decimal('shrub_forest', 15, 2)->default(0);
            $table->decimal('protected_forest', 15, 2)->default(0);
            // Mutasi Tanah di Desa (m²)
            $table->decimal('land_mutation', 15, 2)->default(0);
            // Tanah Kosong (m²)
            $table->decimal('vacant_land', 15, 2)->default(0);
            // Lain-lain (m²)
            $table->decimal('other_area', 15, 2)->default(0);
            // Keterangan
            $table->text('notes')->nullable();
            // Informasi Pencatatan
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('owner_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_lands');
    }
};
