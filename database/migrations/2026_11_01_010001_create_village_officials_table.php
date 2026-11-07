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
            $table->string('nama', 150);
            $table->string('niap', 16)->unique();
            $table->string('nip', 16)->nullable()->unique();
            $table->string('jenis_kelamin', 2);
            $table->string('tempat_lahir', 100);
            $table->date('tgl_lahir');
            $table->string('pendidikan', 100);
            $table->string('pangkat_golongan', 100)->nullable();
            $table->foreignId('village_position_id')->constrained('village_positions')->restrictOnDelete();
            $table->string('nomor_pengangkatan', 100);
            $table->date('tgl_pengangkatan');
            $table->string('nomor_pemberhentian', 100)->nullable();
            $table->date('tgl_pemberhentian')->nullable();
            $table->string('status', 30)->default('active');
            $table->string('telpon', 30)->unique();
            $table->text('alamat');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
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
