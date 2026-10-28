<?php

use App\Http\Controllers\Admin\AdminPoliController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\VerifikasiPembayaranController;
use App\Http\Controllers\AntrianLiveController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Dokter\PeriksaPasienController;
use App\Http\Controllers\Dokter\RiwayatPasienController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Pasien\DaftarPoliController;
use App\Http\Controllers\Pasien\PembayaranController;
use App\Http\Controllers\Pasien\RiwayatPoliController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Shared Authenticated Routes
Route::middleware('auth')->group(function () {
    // Profile & Ganti Password
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    // Real-time Queue Status API
    Route::get('/api/antrian-status', [AntrianLiveController::class, 'status'])->name('api.antrian.status');
});

// Role Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Exports (Defined before resource routes to avoid route collisions)
    Route::get('dokter/export', [DokterController::class, 'export'])->name('dokter.export');
    Route::get('pasien/export', [PasienController::class, 'export'])->name('pasien.export');
    Route::get('obat/export', [ObatController::class, 'export'])->name('obat.export');

    Route::resource('polis', AdminPoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);

    // Verifikasi Pembayaran
    Route::get('verifikasi-pembayaran', [VerifikasiPembayaranController::class, 'index'])->name('verifikasi-pembayaran.index');
    Route::post('verifikasi-pembayaran/{periksa}/konfirmasi', [VerifikasiPembayaranController::class, 'konfirmasi'])->name('verifikasi-pembayaran.konfirmasi');
});

// Role Dokter
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');

    // Jadwal Periksa Export & Resource
    Route::get('jadwal-periksa/export', [JadwalPeriksaController::class, 'export'])->name('jadwal-periksa.export');
    Route::resource('jadwal-periksa', JadwalPeriksaController::class);

    // Periksa Pasien
    Route::get('periksa-pasien', [PeriksaPasienController::class, 'index'])->name('periksa-pasien.index');
    Route::post('periksa-pasien/call/{jadwal}', [PeriksaPasienController::class, 'callNumber'])->name('periksa-pasien.call');
    Route::get('periksa-pasien/{daftarPoli}/create', [PeriksaPasienController::class, 'create'])->name('periksa-pasien.create');
    Route::post('periksa-pasien/{daftarPoli}', [PeriksaPasienController::class, 'store'])->name('periksa-pasien.store');

    // Riwayat Pasien Export & Views
    Route::get('riwayat-pasien/export', [RiwayatPasienController::class, 'export'])->name('riwayat-pasien.export');
    Route::get('riwayat-pasien', [RiwayatPasienController::class, 'index'])->name('riwayat-pasien.index');
    Route::get('riwayat-pasien/{periksa}', [RiwayatPasienController::class, 'detail'])->name('riwayat-pasien.detail');
});

// Role Pasien
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    // Dashboard & Daftar Poli
    Route::get('/dashboard', [DaftarPoliController::class, 'index'])->name('pasien.dashboard');
    Route::post('/daftar-poli', [DaftarPoliController::class, 'store'])->name('pasien.daftar-poli.store');

    // Riwayat Pendaftaran
    Route::get('/riwayat', [RiwayatPoliController::class, 'index'])->name('pasien.riwayat.index');
    Route::get('/riwayat/{id}', [RiwayatPoliController::class, 'detail'])->name('pasien.riwayat.detail');

    // Pembayaran & Upload Bukti
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pasien.pembayaran.index');
    Route::post('/pembayaran/{periksa}/upload', [PembayaranController::class, 'uploadBukti'])->name('pasien.pembayaran.upload');
});