<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class SatuanLogistikController extends Controller
{
    public function prediksiRop($cabangId)
    {
        // 1. Ambil data historis penjualan untuk cabang tertentu dari MySQL
        $riwayatPenjualan = DB::table('penjualans')
            ->where('cabang_id', $cabangId)
            ->select('total_donat_terjual', 'sisa_stok_bahan')
            ->orderBy('tanggal', 'desc')
            ->take(30) // Ambil data 30 hari terakhir
            ->get();

        if ($riwayatPenjualan->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data penjualan untuk cabang ini tidak ditemukan.'
            ], 404);
        }

        // 2. Siapkan parameter logistik (bisa disesuaikan nanti)
        $leadTime = 2;       // Butuh 2 hari pengiriman dari pusat ke cabang
        $safetyStock = 15;   // Batas aman cadangan bahan di cabang (15 Kg)

        // 3. Tembak API Python FastAPI menggunakan HTTP Client Laravel
        // Pastikan server uvicorn Python Anda (port 8000) masih menyala
        $response = Http::post('http://127.0.0.1:8000/api/hitung-rop', [
            'lead_time' => $leadTime,
            'safety_stock' => $safetyStock,
            'riwayat_penjualan' => $riwayatPenjualan
        ]);

        // 4. Ambil hasil kalkulasi dari Python
        $hasilDariPython = $response->json();

        // 5. Tampilkan hasilnya ke layar (sementara dalam format JSON)
        return response()->json([
            'nama_cabang' => DB::table('cabangs')->where('id', $cabangId)->value('nama_cabang'),
            'input_logistik' => [
                'lead_time_hari' => $leadTime,
                'safety_stock_kg' => $safetyStock
            ],
            'hasil_prediksi_ai' => $hasilDariPython['analisis'] ?? 'Gagal terhubung ke Python'
        ]);
    }
}