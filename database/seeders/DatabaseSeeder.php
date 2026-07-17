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

        // 2. Masukkan Data Bahan Baku dan Barang Lainnya yang Dapat Dipesan dari Pusat
        $bahanBakus = [
            // Bahan Pokok & Lemak
            ['nama_bahan' => 'Tepung Terigu Premix', 'kategori' => 'Bahan Pokok & Lemak', 'stok_pusat' => 2500, 'satuan' => 'Kg'],
            ['nama_bahan' => 'minyak padat', 'kategori' => 'Bahan Pokok & Lemak', 'stok_pusat' => 1200, 'satuan' => 'Kg'],
            ['nama_bahan' => 'B.O.S', 'kategori' => 'Bahan Pokok & Lemak', 'stok_pusat' => 600, 'satuan' => 'Kg'],
            ['nama_bahan' => 'mother\'s choice', 'kategori' => 'Bahan Pokok & Lemak', 'stok_pusat' => 500, 'satuan' => 'Kg'],

            // Kemasan (Dus)
            ['nama_bahan' => 'dus 1/2 dozen', 'kategori' => 'Kemasan', 'stok_pusat' => 5000, 'satuan' => 'Dus'],
            ['nama_bahan' => 'dus 1 dozen', 'kategori' => 'Kemasan', 'stok_pusat' => 5000, 'satuan' => 'Dus'],
            ['nama_bahan' => 'dus 2 pcs', 'kategori' => 'Kemasan', 'stok_pusat' => 8000, 'satuan' => 'Dus'],

            // Glaze
            ['nama_bahan' => 'glaze dark choco', 'kategori' => 'Glaze', 'stok_pusat' => 450, 'satuan' => 'Pail'],
            ['nama_bahan' => 'glaze white', 'kategori' => 'Glaze', 'stok_pusat' => 350, 'satuan' => 'Pail'],
            ['nama_bahan' => 'glaze tiramisu', 'kategori' => 'Glaze', 'stok_pusat' => 300, 'satuan' => 'Pail'],
            ['nama_bahan' => 'glaze cappuccino', 'kategori' => 'Glaze', 'stok_pusat' => 250, 'satuan' => 'Pail'],
            ['nama_bahan' => 'glaze chocomaltine', 'kategori' => 'Glaze', 'stok_pusat' => 400, 'satuan' => 'Pail'],
            ['nama_bahan' => 'glaze creamcheese', 'kategori' => 'Glaze', 'stok_pusat' => 250, 'satuan' => 'Pail'],
            ['nama_bahan' => 'glaze greentea', 'kategori' => 'Glaze', 'stok_pusat' => 300, 'satuan' => 'Pail'],
            ['nama_bahan' => 'glaze grape', 'kategori' => 'Glaze', 'stok_pusat' => 200, 'satuan' => 'Pail'],
            ['nama_bahan' => 'glaze taro', 'kategori' => 'Glaze', 'stok_pusat' => 250, 'satuan' => 'Pail'],
            ['nama_bahan' => 'glaze banana', 'kategori' => 'Glaze', 'stok_pusat' => 200, 'satuan' => 'Pail'],
            ['nama_bahan' => 'glaze mango', 'kategori' => 'Glaze', 'stok_pusat' => 200, 'satuan' => 'Pail'],
            ['nama_bahan' => 'glaze avocado', 'kategori' => 'Glaze', 'stok_pusat' => 200, 'satuan' => 'Pail'],
            ['nama_bahan' => 'glaze strawberry', 'kategori' => 'Glaze', 'stok_pusat' => 300, 'satuan' => 'Pail'],
            ['nama_bahan' => 'glaze blueberry', 'kategori' => 'Glaze', 'stok_pusat' => 250, 'satuan' => 'Pail'],
            ['nama_bahan' => 'glaze raspberry', 'kategori' => 'Glaze', 'stok_pusat' => 200, 'satuan' => 'Pail'],
            ['nama_bahan' => 'glaze hazelnut', 'kategori' => 'Glaze', 'stok_pusat' => 250, 'satuan' => 'Pail'],
            ['nama_bahan' => 'glaze lemon', 'kategori' => 'Glaze', 'stok_pusat' => 200, 'satuan' => 'Pail'],

            // Topping
            ['nama_bahan' => 'crumble oreo', 'kategori' => 'Topping', 'stok_pusat' => 350, 'satuan' => 'Pack'],
            ['nama_bahan' => 'crumble redvelvet', 'kategori' => 'Topping', 'stok_pusat' => 300, 'satuan' => 'Pack'],
            ['nama_bahan' => 'crumble greentea', 'kategori' => 'Topping', 'stok_pusat' => 250, 'satuan' => 'Pack'],
            ['nama_bahan' => 'crumble caramel', 'kategori' => 'Topping', 'stok_pusat' => 200, 'satuan' => 'Pack'],
            ['nama_bahan' => 'crispy ball dark choco', 'kategori' => 'Topping', 'stok_pusat' => 350, 'satuan' => 'Pack'],
            ['nama_bahan' => 'crispy ball white', 'kategori' => 'Topping', 'stok_pusat' => 300, 'satuan' => 'Pack'],
            ['nama_bahan' => 'kacang almond', 'kategori' => 'Topping', 'stok_pusat' => 180, 'satuan' => 'Kg'],
            ['nama_bahan' => 'kacang tumbuk', 'kategori' => 'Topping', 'stok_pusat' => 250, 'satuan' => 'Kg'],
            ['nama_bahan' => 'meses coklat', 'kategori' => 'Topping', 'stok_pusat' => 400, 'satuan' => 'Kg'],
            ['nama_bahan' => 'keju', 'kategori' => 'Topping', 'stok_pusat' => 350, 'satuan' => 'Block'],
            ['nama_bahan' => 'choco stone', 'kategori' => 'Topping', 'stok_pusat' => 200, 'satuan' => 'Pack'],
            ['nama_bahan' => 'soft cream', 'kategori' => 'Topping', 'stok_pusat' => 400, 'satuan' => 'Kg'],

            // Seragam
            ['nama_bahan' => 'baju seragam: S', 'kategori' => 'Seragam', 'stok_pusat' => 60, 'satuan' => 'Pcs'],
            ['nama_bahan' => 'baju seragam: M', 'kategori' => 'Seragam', 'stok_pusat' => 100, 'satuan' => 'Pcs'],
            ['nama_bahan' => 'baju seragam: L', 'kategori' => 'Seragam', 'stok_pusat' => 120, 'satuan' => 'Pcs'],
            ['nama_bahan' => 'baju seragam: XL', 'kategori' => 'Seragam', 'stok_pusat' => 80, 'satuan' => 'Pcs'],
            ['nama_bahan' => 'baju seragam: XXL', 'kategori' => 'Seragam', 'stok_pusat' => 40, 'satuan' => 'Pcs'],
        ];

        foreach ($bahanBakus as $bahan) {
            DB::table('bahan_bakus')->insert([
                'nama_bahan' => $bahan['nama_bahan'],
                'kategori' => $bahan['kategori'],
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

        // 4. Masukkan Data Akun Pengguna untuk 3 Role (Admin Pusat, Kasir Cabang, Owner Cabang)
        $users = [
            [
                'name' => 'Bapak Hendra (Admin Pusat Logistik)',
                'email' => 'admin.pusat@donatmenak.com',
                'password' => bcrypt('password'),
                'role' => 'admin_pusat',
                'cabang_id' => 1,
            ],
            [
                'name' => 'Bapak Owner (Owner Cabang Donat Menak)',
                'email' => 'owner.cabang@donatmenak.com',
                'password' => bcrypt('password'),
                'role' => 'owner_cabang',
                'cabang_id' => 2,
            ],
            [
                'name' => 'Ahmad (Kasir Cabang Cibiru)',
                'email' => 'kasir.cibiru@donatmenak.com',
                'password' => bcrypt('password'),
                'role' => 'kasir_cabang',
                'cabang_id' => 2,
            ],
            [
                'name' => 'Siti (Kasir Cabang Sarijadi)',
                'email' => 'kasir.sarijadi@donatmenak.com',
                'password' => bcrypt('password'),
                'role' => 'kasir_cabang',
                'cabang_id' => 3,
            ],
            [
                'name' => 'Dudi (Kasir Cabang Lembang)',
                'email' => 'kasir.lembang@donatmenak.com',
                'password' => bcrypt('password'),
                'role' => 'kasir_cabang',
                'cabang_id' => 4,
            ],
            [
                'name' => 'Maya (Kasir Cabang Buah Batu)',
                'email' => 'kasir.buahbatu@donatmenak.com',
                'password' => bcrypt('password'),
                'role' => 'kasir_cabang',
                'cabang_id' => 5,
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert(array_merge($user, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // 5. Masukkan Data Dummy Permintaan Belanja Cabang
        $permintaanBelanjas = [
            [
                'cabang_id' => 2,
                'nama_bahan' => 'Tepung Terigu Premix',
                'jumlah' => 120,
                'satuan' => 'Kg',
                'keterangan' => 'Stok menjelang weekend wisuda kampus',
                'status' => 'Menunggu Persetujuan'
            ],
            [
                'cabang_id' => 4,
                'nama_bahan' => 'Glaze Chocolate Premium',
                'jumlah' => 45,
                'satuan' => 'Kg',
                'keterangan' => 'Persiapan libur panjang wisatawan Lembang',
                'status' => 'Menunggu Persetujuan'
            ],
            [
                'cabang_id' => 3,
                'nama_bahan' => 'Box Kemasan Isi 6',
                'jumlah' => 500,
                'satuan' => 'Pcs',
                'keterangan' => 'Tambahan box kemasan reguler',
                'status' => 'Diproses'
            ],
            [
                'cabang_id' => 5,
                'nama_bahan' => 'Mentega Khusus',
                'jumlah' => 30,
                'satuan' => 'Kg',
                'keterangan' => 'Pesanan rutin mingguan',
                'status' => 'Selesai'
            ],
        ];

        foreach ($permintaanBelanjas as $req) {
            DB::table('permintaan_belanjas')->insert(array_merge($req, [
                'created_at' => now()->subHours(rand(1, 10)),
                'updated_at' => now(),
            ]));
        }

        // 6. Masukkan Data Dummy Rekap Keuangan Cabang
        for ($cId = 2; $cId <= 5; $cId++) {
            for ($d = 14; $d >= 0; $d--) {
                $tgl = Carbon::now()->subDays($d)->format('Y-m-d');
                DB::table('rekap_keuangan_cabangs')->insert([
                    'cabang_id' => $cId,
                    'tanggal' => $tgl,
                    'pemasukan_cash' => rand(1500000, 2800000),
                    'pemasukan_cashless' => rand(2200000, 4500000),
                    'pengeluaran_nominal' => rand(300000, 750000),
                    'pengeluaran_keterangan' => 'Biaya operasional & kebersihan harian cabang',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 7. Masukkan Data Stok Sisa Seluruh Bahan Baku di Setiap Toko Cabang
        for ($cId = 2; $cId <= 5; $cId++) {
            foreach ($bahanBakus as $bahan) {
                // Buat variasi stok cabang yang masuk akal
                $ratio = 0.05 + ($cId * 0.01);
                $stokAwal = round(max($bahan['stok_pusat'] * $ratio, 5), 1);
                if ($bahan['nama_bahan'] === 'Tepung Terigu Premix') {
                    $stokAwal = rand(35, 65);
                }
                DB::table('stok_cabangs')->insert([
                    'cabang_id' => $cId,
                    'nama_bahan' => $bahan['nama_bahan'],
                    'stok' => $stokAwal,
                    'satuan' => $bahan['satuan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}