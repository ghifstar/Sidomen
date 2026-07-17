<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SatuanLogistikController;

// Dasbor Utama Smart Logistics & AI Inventaris Donat Menak
Route::get('/', [SatuanLogistikController::class, 'index'])->name('dashboard');

// Route API internal untuk Optimasi Rute Distribusi (TSP)
Route::post('/api/optimasi-rute', [SatuanLogistikController::class, 'optimasiRute'])->name('api.optimasi.rute');

// Route API internal untuk Simulasi Interaktif ROP
Route::post('/api/simulasi-rop', [SatuanLogistikController::class, 'simulasiRop'])->name('api.simulasi.rop');

// Route API untuk Role Petugas Cabang (Input Penjualan & Sisa Stok)
Route::post('/api/input-penjualan', [SatuanLogistikController::class, 'inputPenjualan'])->name('api.input.penjualan');

// Route API untuk Role Petugas & Owner Cabang (Update Sisa Stok Bahan Baku Cabang)
Route::post('/api/update-stok-cabang', [SatuanLogistikController::class, 'updateStokCabang'])->name('api.update.stok.cabang');


// Route API untuk Role Petugas Pusat (Update Stok Dapur Lodaya)
Route::post('/api/update-stok-pusat', [SatuanLogistikController::class, 'updateStokPusat'])->name('api.update.stok.pusat');

// Route API untuk Role Petugas Cabang (Input Rekap Keuangan Harian)
Route::post('/api/input-rekap-keuangan', [SatuanLogistikController::class, 'inputRekapKeuangan'])->name('api.input.rekap.keuangan');

// Route API untuk Role Owner Cabang (Input Permintaan Belanja ke Pusat)
Route::post('/api/input-permintaan-belanja', [SatuanLogistikController::class, 'inputPermintaanBelanja'])->name('api.input.permintaan.belanja');

// Route API untuk Role Admin Pusat (Proses Permintaan Belanja Cabang)
Route::post('/api/proses-permintaan-belanja', [SatuanLogistikController::class, 'prosesPermintaanBelanja'])->name('api.proses.permintaan.belanja');

// Route uji coba asli untuk menembak API Python berdasarkan ID Cabang
Route::get('/test-rop/{cabangId}', [SatuanLogistikController::class, 'prediksiRop'])->name('test.rop');