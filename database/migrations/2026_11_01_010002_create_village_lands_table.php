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

            // Identitas pemilik tanah
            $table->string('owner_name', 150);
            $table->decimal('jml', 15, 2)->default(0);

            // Status Hak Tanah (M2)
            // Sudah Bersertifikat
            $table->decimal('hm', 15, 2)->default(0);
            $table->decimal('hgb', 15, 2)->default(0);
            $table->decimal('hp', 15, 2)->default(0);
            $table->decimal('hgu', 15, 2)->default(0);
            $table->decimal('hpl', 15, 2)->default(0);

            // Belum Bersertifikat
            $table->decimal('ma', 15, 2)->default(0);
            $table->decimal('vi', 15, 2)->default(0);
            $table->decimal('tn', 15, 2)->default(0);

            // Penggunaan Tanah (M2)
            // Non Pertanian
            $table->decimal('perumahan', 15, 2)->default(0);
            $table->decimal('dagang_jasa', 15, 2)->default(0);
            $table->decimal('perkantoran', 15, 2)->default(0);
            $table->decimal('industri', 15, 2)->default(0);
            $table->decimal('fasilitas_umum', 15, 2)->default(0);

            // Pertanian
            $table->decimal('sawah', 15, 2)->default(0);
            $table->decimal('tegalan', 15, 2)->default(0);
            $table->decimal('perkebunan', 15, 2)->default(0);
            $table->decimal('peternakan_perikanan', 15, 2)->default(0);
            $table->decimal('hutan_belukar', 15, 2)->default(0);
            $table->decimal('hutan_lebat', 15, 2)->default(0);

            // Kategori lainnya
            $table->decimal('mutasi_tanah', 15, 2)->default(0);
            $table->decimal('tanah_kosong', 15, 2)->default(0);
            $table->decimal('lainnya', 15, 2)->default(0);

            // Keterangan
            $table->text('catatan')->nullable();

            // Audit
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

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
