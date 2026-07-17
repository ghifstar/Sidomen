<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Exception;

class SatuanLogistikController extends Controller
{
    /**
     * Menampilkan Dasbor Utama Manajemen Logistik & AI Inventaris Donat Menak
     */
    public function index(\Illuminate\Http\Request $request)
    {
        // Parameter Role Aktif & Cabang Aktif: admin_pusat, kasir_cabang, owner_cabang
        $activeRole = $request->query('role', 'admin_pusat'); 
        $selectedCabangId = $request->query('cabang_id', 2); // Default cabang Cibiru (ID 2)
        $users = DB::table('users')->get();

        // 1. Ambil data Dapur Pusat (Cabang ID 1) dan Bahan Baku
        $dapurPusat = DB::table('cabangs')->where('id', 1)->first();
        $bahanBakus = DB::table('bahan_bakus')->get();

        // 2. Ambil data Cabang Ritel (Cabang ID > 1)
        $cabangs = DB::table('cabangs')->where('id', '>', 1)->get();

        // 2b. Ambil Permintaan Belanja Cabang
        $permintaanBelanjas = DB::table('permintaan_belanjas')
            ->join('cabangs', 'permintaan_belanjas.cabang_id', '=', 'cabangs.id')
            ->select('permintaan_belanjas.*', 'cabangs.nama_cabang')
            ->orderBy('permintaan_belanjas.id', 'desc')
            ->get();

        // 3. Status koneksi ke Python FastAPI Engine
        $aiStatus = false;
        try {
            $statusCheck = Http::timeout(2)->get('http://127.0.0.1:8000/api/status');
            if ($statusCheck->successful()) {
                $aiStatus = true;
            }
        } catch (Exception $e) {
            $aiStatus = false;
        }

        // 4. Proses analitik inventaris setiap cabang (panggil FastAPI jika aktif)
        $totalDonat30Hari = 0;
        $totalCabangKritis = 0;
        $totalCabangWaspada = 0;

        foreach ($cabangs as $cabang) {
            $riwayat = DB::table('penjualans')
                ->where('cabang_id', $cabang->id)
                ->select('total_donat_terjual', 'sisa_stok_bahan', 'tanggal')
                ->orderBy('tanggal', 'desc')
                ->take(30)
                ->get();

            $totalTerjualCabang = $riwayat->sum('total_donat_terjual');
            $totalDonat30Hari += $totalTerjualCabang;
            $sisaStokSaatIni = $riwayat->isNotEmpty() ? $riwayat->first()->sisa_stok_bahan : 0;

            $cabang->total_30_hari = $totalTerjualCabang;
            $cabang->sisa_stok_terkini = $sisaStokSaatIni;

            // Default parameter ROP
            $leadTime = 2; // 2 hari pengiriman dari Lodaya
            $safetyStock = 15; // 15 Kg batas aman

            if ($aiStatus && $riwayat->isNotEmpty()) {
                try {
                    $riwayatFormatted = $riwayat->map(function ($item) {
                        return [
                            'tanggal' => $item->tanggal,
                            'total_donat_terjual' => (int) $item->total_donat_terjual
                        ];
                    })->toArray();

                    $aiResp = Http::timeout(4)->post('http://127.0.0.1:8000/api/hitung-rop', [
                        'lead_time' => $leadTime,
                        'safety_stock' => $safetyStock,
                        'sisa_stok_saat_ini' => $sisaStokSaatIni,
                        'riwayat_penjualan' => $riwayatFormatted
                    ]);

                    if ($aiResp->successful()) {
                        $cabang->ai_data = $aiResp->json();
                        $statusCode = $cabang->ai_data['status_code'] ?? 'AMAN';
                        if ($statusCode === 'KRITIS') $totalCabangKritis++;
                        if ($statusCode === 'WASPADA') $totalCabangWaspada++;
                    } else {
                        $cabang->ai_data = $this->fallbackRopCalculation($riwayat, $leadTime, $safetyStock, $sisaStokSaatIni);
                    }
                } catch (Exception $e) {
                    $cabang->ai_data = $this->fallbackRopCalculation($riwayat, $leadTime, $safetyStock, $sisaStokSaatIni);
                }
            } else {
                $cabang->ai_data = $this->fallbackRopCalculation($riwayat, $leadTime, $safetyStock, $sisaStokSaatIni);
            }

            $cabang->stok_cabangs = DB::table('stok_cabangs')
                ->where('cabang_id', $cabang->id)
                ->get();

            $cabang->rekap_keuangan = DB::table('rekap_keuangan_cabangs')
                ->where('cabang_id', $cabang->id)
                ->where('tanggal', now()->format('Y-m-d'))
                ->first();

            $riwayatKeuangan = DB::table('rekap_keuangan_cabangs')
                ->where('cabang_id', $cabang->id)
                ->orderBy('tanggal', 'desc')
                ->take(30)
                ->get();

            $cabang->riwayat_keuangan = $riwayatKeuangan;
            $cabang->rekap_bulanan = [
                'total_cash' => $riwayatKeuangan->sum('pemasukan_cash'),
                'total_cashless' => $riwayatKeuangan->sum('pemasukan_cashless'),
                'total_pengeluaran' => $riwayatKeuangan->sum('pengeluaran_nominal'),
                'laba_bersih' => ($riwayatKeuangan->sum('pemasukan_cash') + $riwayatKeuangan->sum('pemasukan_cashless')) - $riwayatKeuangan->sum('pengeluaran_nominal'),
            ];
        }

        return view('welcome', compact(
            'dapurPusat',
            'bahanBakus',
            'cabangs',
            'permintaanBelanjas',
            'aiStatus',
            'totalDonat30Hari',
            'totalCabangKritis',
            'totalCabangWaspada',
            'activeRole',
            'selectedCabangId',
            'users'
        ));
    }

    /**
     * Kalkulasi fallback di PHP jika Python FastAPI offline
     */
    private function fallbackRopCalculation($riwayat, $leadTime, $safetyStock, $sisaStok)
    {
        if ($riwayat->isEmpty()) {
            return [
                'status_code' => 'AMAN',
                'analisis' => 'Data penjualan belum tersedia.',
                'kalkulasi' => [
                    'rata_rata_donat_harian' => 0,
                    'rata_rata_premix_harian_kg' => 0,
                    'reorder_point_kg' => $safetyStock,
                    'sisa_stok_saat_ini_kg' => $sisaStok,
                    'saran_order_kg' => 50
                ],
                'grafik_tren' => ['label_hari' => [], 'data_penjualan_donat' => [], 'data_premix_terpakai' => []]
            ];
        }

        $donatDemands = $riwayat->pluck('total_donat_terjual')->toArray();
        $avgDonat = count($donatDemands) > 0 ? array_sum($donatDemands) / count($donatDemands) : 0;
        $dAvgPremix = round($avgDonat * 0.05, 2);
        $rop = round(($dAvgPremix * $leadTime) + $safetyStock, 2);

        $statusCode = 'AMAN';
        if ($sisaStok <= $safetyStock) {
            $statusCode = 'KRITIS';
        } elseif ($sisaStok <= $rop * 1.15) {
            $statusCode = 'WASPADA';
        }

        return [
            'status_code' => $statusCode,
            'analisis' => "Kalkulasi Statis PHP (FastAPI Offline): Sisa stok $sisaStok Kg | ROP $rop Kg.",
            'kalkulasi' => [
                'rata_rata_donat_harian' => round($avgDonat, 1),
                'rata_rata_premix_harian_kg' => $dAvgPremix,
                'lead_time_hari' => $leadTime,
                'safety_stock_kg' => $safetyStock,
                'reorder_point_kg' => $rop,
                'sisa_stok_saat_ini_kg' => $sisaStok,
                'saran_order_kg' => round(max(($dAvgPremix * 14) + $safetyStock - $sisaStok, 50), 1)
            ],
            'grafik_tren' => [
                'label_hari' => collect(range(count($donatDemands)-1, 0))->map(fn($i) => "H-$i")->toArray(),
                'data_penjualan_donat' => array_reverse($donatDemands),
                'data_premix_terpakai' => array_reverse(array_map(fn($val) => round($val * 0.05, 2), $donatDemands))
            ]
        ];
    }

    /**
     * Proxy untuk kalkulasi TSP Optimasi Rute ke Python FastAPI
     */
    public function optimasiRute(Request $request)
    {
        $selectedCabangIds = $request->input('cabang_ids', []);

        if (empty($selectedCabangIds)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pilih minimal satu cabang untuk kalkulasi rute.'
            ], 400);
        }

        $pusat = DB::table('cabangs')->where('id', 1)->first();
        $selectedCabangs = DB::table('cabangs')->whereIn('id', $selectedCabangIds)->get();

        $tujuanList = [];
        foreach ($selectedCabangs as $c) {
            // Cek stok untuk menentukan status_inventaris di logistik
            $latestSales = DB::table('penjualans')
                ->where('cabang_id', $c->id)
                ->orderBy('tanggal', 'desc')
                ->first();

            $stok = $latestSales ? $latestSales->sisa_stok_bahan : 50;
            $statusInv = $stok <= 15 ? 'Kritis' : ($stok <= 30 ? 'Waspada' : 'Normal');
            $kebutuhanOrder = max((35 * 14) + 15 - $stok, 50); // Estimasi kebutuhan re-stock (Kg)

            $tujuanList[] = [
                'id' => $c->id,
                'nama_cabang' => $c->nama_cabang,
                'alamat' => $c->alamat,
                'latitude' => (float) $c->latitude,
                'longitude' => (float) $c->longitude,
                'permintaan_kg' => round($kebutuhanOrder, 1),
                'status_inventaris' => $statusInv
            ];
        }

        try {
            $response = Http::timeout(5)->post('http://127.0.0.1:8000/api/rute-distribusi', [
                'dapur_pusat' => [
                    'id' => $pusat->id,
                    'nama_cabang' => $pusat->nama_cabang,
                    'alamat' => $pusat->alamat,
                    'latitude' => (float) $pusat->latitude,
                    'longitude' => (float) $pusat->longitude,
                ],
                'cabang_tujuan' => $tujuanList
            ]);

            return response()->json($response->json());
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghubungi server Python FastAPI di port 8000: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Endpoint untuk simulasi interaktif slider ROP di dasbor
     */
    public function simulasiRop(Request $request)
    {
        $cabangId = $request->input('cabang_id');
        $leadTime = (float) $request->input('lead_time', 2);
        $safetyStock = (float) $request->input('safety_stock', 15);

        $riwayat = DB::table('penjualans')
            ->where('cabang_id', $cabangId)
            ->select('total_donat_terjual', 'sisa_stok_bahan', 'tanggal')
            ->orderBy('tanggal', 'desc')
            ->take(30)
            ->get();

        if ($riwayat->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Data cabang tidak ditemukan'], 404);
        }

        try {
            $response = Http::timeout(3)->post('http://127.0.0.1:8000/api/hitung-rop', [
                'lead_time' => $leadTime,
                'safety_stock' => $safetyStock,
                'riwayat_penjualan' => $riwayat
            ]);

            return response()->json($response->json());
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Python Server Offline'], 500);
        }
    }

    /**
     * Route uji coba API asli per Cabang ID
     */
    public function prediksiRop($cabangId)
    {
        $riwayatPenjualan = DB::table('penjualans')
            ->where('cabang_id', $cabangId)
            ->select('total_donat_terjual', 'sisa_stok_bahan')
            ->orderBy('tanggal', 'desc')
            ->take(30)
            ->get();

        if ($riwayatPenjualan->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data penjualan untuk cabang ini tidak ditemukan.'
            ], 404);
        }

        $leadTime = 2;
        $safetyStock = 15;

        try {
            $response = Http::post('http://127.0.0.1:8000/api/hitung-rop', [
                'lead_time' => $leadTime,
                'safety_stock' => $safetyStock,
                'riwayat_penjualan' => $riwayatPenjualan
            ]);

            $hasilDariPython = $response->json();

            return response()->json([
                'nama_cabang' => DB::table('cabangs')->where('id', $cabangId)->value('nama_cabang'),
                'input_logistik' => [
                    'lead_time_hari' => $leadTime,
                    'safety_stock_kg' => $safetyStock
                ],
                'hasil_prediksi_ai' => $hasilDariPython['analisis'] ?? 'Gagal memproses data AI',
                'detail_kalkulasi' => $hasilDariPython['kalkulasi'] ?? null
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal terhubung ke server Python di port 8000. Pastikan Uvicorn menyala.'
            ], 500);
        }
    }

    /**
     * Role: Petugas Cabang - Input Penjualan Harian & Sisa Stok Bahan Premix
     */
    public function inputPenjualan(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'cabang_id' => 'required|integer',
            'total_donat_terjual' => 'required|numeric|min:0',
            'sisa_stok_bahan' => 'required|numeric|min:0'
        ]);

        $tanggal = now()->format('Y-m-d');
        $cabangId = $validated['cabang_id'];
        $stokPremix = $validated['sisa_stok_bahan'];

        // Simpan / Update laporan hari ini di database penjualans
        $exists = DB::table('penjualans')
            ->where('cabang_id', $cabangId)
            ->where('tanggal', $tanggal)
            ->first();

        if ($exists) {
            DB::table('penjualans')
                ->where('id', $exists->id)
                ->update([
                    'total_donat_terjual' => $validated['total_donat_terjual'],
                    'sisa_stok_bahan' => $stokPremix,
                    'updated_at' => now()
                ]);
        } else {
            DB::table('penjualans')->insert([
                'cabang_id' => $cabangId,
                'tanggal' => $tanggal,
                'total_donat_terjual' => $validated['total_donat_terjual'],
                'sisa_stok_bahan' => $stokPremix,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Sinkronkan juga dengan tabel stok_cabangs untuk Tepung Terigu Premix
        $existsStok = DB::table('stok_cabangs')
            ->where('cabang_id', $cabangId)
            ->where('nama_bahan', 'Tepung Terigu Premix')
            ->first();

        if ($existsStok) {
            DB::table('stok_cabangs')
                ->where('id', $existsStok->id)
                ->update([
                    'stok' => $stokPremix,
                    'updated_at' => now()
                ]);
        } else {
            DB::table('stok_cabangs')->insert([
                'cabang_id' => $cabangId,
                'nama_bahan' => 'Tepung Terigu Premix',
                'stok' => $stokPremix,
                'satuan' => 'Kg',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan penjualan harian dan sisa stok premix berhasil disimpan.'
        ]);
    }

    /**
     * Role: Petugas Cabang & Owner Cabang - Update Sisa Stok Bahan Baku / Barang Cabang
     */
    public function updateStokCabang(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'cabang_id' => 'required|integer',
            'nama_bahan' => 'required|string',
            'stok' => 'required|numeric|min:0',
            'satuan' => 'nullable|string'
        ]);

        $cabangId = $validated['cabang_id'];
        $namaBahan = $validated['nama_bahan'];
        $stok = $validated['stok'];

        // Cari satuan jika tidak dikirim
        $satuan = $validated['satuan'] ?? 'Unit';
        if (empty($validated['satuan'])) {
            $bb = DB::table('bahan_bakus')->where('nama_bahan', $namaBahan)->first();
            if ($bb) $satuan = $bb->satuan;
        }

        // Update atau insert ke stok_cabangs
        $exists = DB::table('stok_cabangs')
            ->where('cabang_id', $cabangId)
            ->where('nama_bahan', $namaBahan)
            ->first();

        if ($exists) {
            DB::table('stok_cabangs')
                ->where('id', $exists->id)
                ->update([
                    'stok' => $stok,
                    'satuan' => $satuan,
                    'updated_at' => now()
                ]);
        } else {
            DB::table('stok_cabangs')->insert([
                'cabang_id' => $cabangId,
                'nama_bahan' => $namaBahan,
                'stok' => $stok,
                'satuan' => $satuan,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Jika yang diupdate adalah Tepung Terigu Premix, kita sinkronkan juga dengan penjualans hari ini agar ROP AI terupdate
        if (stripos($namaBahan, 'premix') !== false || stripos($namaBahan, 'tepung') !== false) {
            $tanggal = now()->format('Y-m-d');
            $penjualanToday = DB::table('penjualans')
                ->where('cabang_id', $cabangId)
                ->where('tanggal', $tanggal)
                ->first();

            if ($penjualanToday) {
                DB::table('penjualans')
                    ->where('id', $penjualanToday->id)
                    ->update([
                        'sisa_stok_bahan' => $stok,
                        'updated_at' => now()
                    ]);
            } else {
                DB::table('penjualans')->insert([
                    'cabang_id' => $cabangId,
                    'tanggal' => $tanggal,
                    'total_donat_terjual' => 0,
                    'sisa_stok_bahan' => $stok,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => "Sisa stok untuk [{$namaBahan}] di cabang berhasil diperbarui menjadi {$stok} {$satuan}."
        ]);
    }


    /**
     * Role: Petugas Pusat - Update / Tambah Stok Dapur Lodaya
     */
    public function updateStokPusat(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'bahan_id' => 'required|integer',
            'stok_tambahan' => 'required|numeric|min:1'
        ]);

        DB::table('bahan_bakus')
            ->where('id', $validated['bahan_id'])
            ->increment('stok_pusat', $validated['stok_tambahan'], ['updated_at' => now()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Stok pasokan Dapur Pusat Lodaya berhasil diperbarui.'
        ]);
    }

    /**
     * Role: Petugas Cabang - Input Rekap Keuangan Harian (Pemasukan Cash, Cashless & Pengeluaran)
     */
    public function inputRekapKeuangan(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'cabang_id' => 'required|integer',
            'pemasukan_cash' => 'required|numeric|min:0',
            'pemasukan_cashless' => 'required|numeric|min:0',
            'pengeluaran_nominal' => 'required|numeric|min:0',
            'pengeluaran_keterangan' => 'nullable|string'
        ]);

        $tanggal = now()->format('Y-m-d');

        $exists = DB::table('rekap_keuangan_cabangs')
            ->where('cabang_id', $validated['cabang_id'])
            ->where('tanggal', $tanggal)
            ->first();

        if ($exists) {
            DB::table('rekap_keuangan_cabangs')
                ->where('id', $exists->id)
                ->update([
                    'pemasukan_cash' => $validated['pemasukan_cash'],
                    'pemasukan_cashless' => $validated['pemasukan_cashless'],
                    'pengeluaran_nominal' => $validated['pengeluaran_nominal'],
                    'pengeluaran_keterangan' => $validated['pengeluaran_keterangan'],
                    'updated_at' => now()
                ]);
        } else {
            DB::table('rekap_keuangan_cabangs')->insert([
                'cabang_id' => $validated['cabang_id'],
                'tanggal' => $tanggal,
                'pemasukan_cash' => $validated['pemasukan_cash'],
                'pemasukan_cashless' => $validated['pemasukan_cashless'],
                'pengeluaran_nominal' => $validated['pengeluaran_nominal'],
                'pengeluaran_keterangan' => $validated['pengeluaran_keterangan'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Rekap keuangan harian (pemasukan & pengeluaran) berhasil disimpan.'
        ]);
    }

    /**
     * Role: Owner Cabang - Input Form Permintaan Belanja ke Pusat
     */
    public function inputPermintaanBelanja(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'cabang_id' => 'required|integer',
            'items' => 'nullable|array',
            'items.*.nama_bahan' => 'required_with:items|string',
            'items.*.jumlah' => 'required_with:items|numeric|min:0.1',
            'items.*.satuan' => 'required_with:items|string',
            'items.*.keterangan' => 'nullable|string',
            'nama_bahan' => 'required_without:items|string',
            'jumlah' => 'required_without:items|numeric|min:0.1',
            'satuan' => 'required_without:items|string',
            'keterangan' => 'nullable|string'
        ]);

        $cabangId = $validated['cabang_id'];
        $insertedCount = 0;

        if (!empty($validated['items']) && is_array($validated['items'])) {
            $dataInsert = [];
            foreach ($validated['items'] as $item) {
                $dataInsert[] = [
                    'cabang_id' => $cabangId,
                    'nama_bahan' => $item['nama_bahan'],
                    'jumlah' => $item['jumlah'],
                    'satuan' => $item['satuan'],
                    'keterangan' => !empty($item['keterangan']) ? $item['keterangan'] : '-',
                    'status' => 'Menunggu Persetujuan',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $insertedCount++;
            }
            DB::table('permintaan_belanjas')->insert($dataInsert);
        } else {
            DB::table('permintaan_belanjas')->insert([
                'cabang_id' => $cabangId,
                'nama_bahan' => $validated['nama_bahan'],
                'jumlah' => $validated['jumlah'],
                'satuan' => $validated['satuan'],
                'keterangan' => !empty($validated['keterangan']) ? $validated['keterangan'] : '-',
                'status' => 'Menunggu Persetujuan',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $insertedCount = 1;
        }

        return response()->json([
            'status' => 'success',
            'message' => $insertedCount > 1 
                ? "Berhasil mengirim {$insertedCount} item permintaan belanja sekaligus ke Admin Pusat!" 
                : "Form permintaan belanja berhasil dikirim ke Admin Pusat."
        ]);
    }

    /**
     * Role: Admin Pusat - Proses Permintaan Belanja Cabang
     */
    public function prosesPermintaanBelanja(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'status' => 'required|string'
        ]);

        DB::table('permintaan_belanjas')
            ->where('id', $validated['id'])
            ->update([
                'status' => $validated['status'],
                'updated_at' => now()
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status permintaan belanja cabang berhasil diperbarui menjadi ' . $validated['status'] . '.'
        ]);
    }
}