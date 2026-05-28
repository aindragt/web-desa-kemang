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
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_referensi', 20)->unique();
            $table->enum('jenis_surat', ['domisili', 'usaha', 'tidak_mampu', 'pengantar']);
            
            // Biodata Pemohon
            $table->string('nama_lengkap');
            $table->string('nik', 16);
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('agama');
            $table->string('pekerjaan');
            $table->text('alamat');
            $table->string('no_hp', 20);
            $table->text('keperluan');
            
            // Khusus Surat Keterangan Usaha (SKU)
            $table->string('nama_usaha')->nullable();
            $table->string('jenis_usaha')->nullable();
            
            // Keterangan Lain
            $table->text('keterangan_tambahan')->nullable();
            
            // Status dan Alur Approval
            $table->enum('status', ['menunggu', 'diproses', 'menunggu_persetujuan', 'disetujui', 'ditolak', 'selesai'])->default('menunggu');
            $table->text('catatan_admin')->nullable(); // Alasan penolakan / revisi
            
            // Timestamps Audit
            $table->timestamp('diproses_at')->nullable();
            $table->timestamp('disetujui_at')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};
