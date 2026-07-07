<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Masukkan Data Dummy Cabang Donat Menak (Area Bandung)
        $cabangs = [
            ['nama_cabang' => 'Donat Menak Pusat (Dapur)', 'alamat' => 'Jl. Lodaya No.10, Bandung', 'latitude' => -6.9314, 'longitude' => 107.6231],
            ['nama_cabang' => 'Donat Menak Cibiru', 'alamat' => 'Jl. Raya Cibiru No.45, Bandung', 'latitude' => -6.9382, 'longitude' => 107.7164],
            ['nama_cabang' => 'Donat Menak Sarijadi', 'alamat' => 'Jl. Sarijadi Blok 8, Bandung', 'latitude' => -6.8778, 'longitude' => 107.5819],
            ['nama_cabang' => 'Donat Menak Lembang', 'alamat' => 'Jl. Raya Lembang No.12, Bandung', 'latitude' => -6.8172, 'longitude' => 107.6144],
            ['nama_cabang' => 'Donat Menak Buah Batu', 'alamat' => 'Jl. Buah Batu No.150, Bandung', 'latitude' => -6.9472, 'longitude' => 107.6253],
        ];

        foreach ($cabangs as $cabang) {
            DB::table('cabangs')->insert([
                'nama_cabang' => $cabang['nama_cabang'],
                'alamat' => $cabang['alamat'],
                'latitude' => $cabang['latitude'],
                'longitude' => $cabang['longitude'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Masukkan Data Dummy Bahan Baku Utama di Pusat
        $bahanBakus = [
            ['nama_bahan' => 'Tepung Terigu Premix', 'stok_pusat' => 1500, 'satuan' => 'Kg'],
            ['nama_bahan' => 'Glaze Chocolate Premium', 'stok_pusat' => 500, 'satuan' => 'Kg'],
            ['nama_bahan' => 'Glaze Tiramisu', 'stok_pusat' => 300, 'satuan' => 'Kg'],
            ['nama_bahan' => 'Mentega Khusus', 'stok_pusat' => 400, 'satuan' => 'Kg'],
            ['nama_bahan' => 'Box Kemasan Isi 6', 'stok_pusat' => 10000, 'satuan' => 'Pcs'],
        ];

        foreach ($bahanBakus as $bahan) {
            DB::table('bahan_bakus')->insert([
                'nama_bahan' => $bahan['nama_bahan'],
                'stok_pusat' => $bahan['stok_pusat'],
                'satuan' => $bahan['satuan'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Masukkan Data Dummy Penjualan Harian (30 Hari Terakhir untuk Melatih AI)
        // Kita lewati cabang ID 1 (karena ID 1 berfungsi sebagai Dapur Pusat)
        for ($cabangId = 2; $cabangId <= 5; $cabangId++) {
            $sisaStokBahan = 100; // Stok awal bahan di cabang (dalam Kg)

            for ($i = 30; $i >= 0; $i--) {
                $tanggal = Carbon::now()->subDays($i)->format('Y-m-d');
                
                // Membuat variasi jumlah terjual (Misal: Lembang lebih ramai di weekend)
                $hari = Carbon::parse($tanggal)->format('l');
                $baseTerjual = ($hari == 'Saturday' || $hari == 'Sunday') ? 400 : 200;
                $totalTerjual = $baseTerjual + rand(-30, 50); // Tambahkan efek acak fluktuasi pasar

                // Logika pengurangan stok bahan di cabang: 1 donat butuh sekitar 0.05 Kg tepung/bahan
                $bahanTerpakai = round($totalTerjual * 0.05);
                $sisaStokBahan -= $bahanTerpakai;

                // Simulasi jika stok cabang tipis, otomatis bertambah karena dikirim dari pusat
                if ($sisaStokBahan < 20) {
                    $sisaStokBahan += 80; // Dikirim pasokan tambahan
                }

                DB::table('penjualans')->insert([
                    'cabang_id' => $cabangId,
                    'tanggal' => $tanggal,
                    'total_donat_terjual' => $totalTerjual,
                    'sisa_stok_bahan' => $sisaStokBahan,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}