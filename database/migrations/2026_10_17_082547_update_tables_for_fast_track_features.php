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
        // 1. Tambah kolom stok pada tabel obat
        Schema::table('obat', function (Blueprint $table) {
            $table->integer('stok')->default(50)->after('harga');
        });

        // 2. Tambah status dan antrian_sekarang pada jadwal_periksa
        Schema::table('jadwal_periksa', function (Blueprint $table) {
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif')->after('jam_selesai');
            $table->integer('antrian_sekarang')->default(0)->after('status');
        });

        // 3. Tambah status_periksa pada daftar_poli
        Schema::table('daftar_poli', function (Blueprint $table) {
            $table->enum('status_periksa', ['menunggu', 'sedang_diperiksa', 'selesai'])->default('menunggu')->after('no_antrian');
        });

        // 4. Tambah bukti dan status pembayaran pada periksa
        Schema::table('periksa', function (Blueprint $table) {
            $table->string('bukti_pembayaran')->nullable()->after('biaya_periksa');
            $table->enum('status_pembayaran', ['belum_bayar', 'menunggu_verifikasi', 'lunas'])->default('belum_bayar')->after('bukti_pembayaran');
            $table->dateTime('tgl_bayar')->nullable()->after('status_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periksa', function (Blueprint $table) {
            $table->dropColumn(['bukti_pembayaran', 'status_pembayaran', 'tgl_bayar']);
        });

        Schema::table('daftar_poli', function (Blueprint $table) {
            $table->dropColumn(['status_periksa']);
        });

        Schema::table('jadwal_periksa', function (Blueprint $table) {
            $table->dropColumn(['status', 'antrian_sekarang']);
        });

        Schema::table('obat', function (Blueprint $table) {
            $table->dropColumn(['stok']);
        });
    }
};
