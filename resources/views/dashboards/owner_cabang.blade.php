@extends('layouts.app')
@section('content')
    <!-- HEADER -->
    <div class="bg-gold-200 border-b-2 border-gold-400 px-6 py-4 shadow-md rounded-2xl mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-black text-cocoa-950 uppercase">👔 Dashboard Owner Cabang – Analitik Eksekutif, AI ROP & Permintaan Belanja</h2>
            <p class="text-xs font-semibold text-cocoa-800">Wewenang membaca laporan keuangan harian & rekap bulanan, status bahan baku, prediksi AI Reorder Point berdasarkan event/libur, dan mengajukan permintaan belanja ke pusat.</p>
        </div>
        <div class="flex items-center gap-2">
            <label class="text-xs font-black text-cocoa-900">Pilih Cabang:</label>
            <select onchange="window.location.href='?cabang_id=' + this.value"
                class="px-3 py-1.5 rounded-xl bg-white border-2 border-gold-500 text-cocoa-950 font-black text-xs focus:outline-none shadow-sm">
                @foreach($cabangs as $cab)
                    <option value="{{ $cab->id }}" {{ $selectedCabangId == $cab->id ? 'selected' : '' }}>
                        {{ $cab->nama_cabang }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

<!-- ================================================================================== -->
            <!-- DASHBOARD OWNER CABANG: LAPORAN KEUANGAN, BAHAN BAKU, AI ROP MUSIM & REQUEST BELANJA -->
            <!-- ================================================================================== -->
            @php
                $myCabang = $cabangs->where('id', $selectedCabangId)->first() ?? $cabangs->first();
                $rekapBulanan = $myCabang->rekap_bulanan ?? ['total_cash' => 0, 'total_cashless' => 0, 'total_pengeluaran' => 0, 'laba_bersih' => 0];
                $riwayatKeuangan = $myCabang->riwayat_keuangan ?? collect([]);
                $ai = $myCabang->ai_data ?? [];
                $calc = $ai['kalkulasi'] ?? [];
                $statusCode = $ai['status_code'] ?? 'AMAN';
                $myRequests = $permintaanBelanjas->where('cabang_id', $myCabang->id);
            @endphp

            <div class="space-y-8">
                <!-- 1. READ LAPORAN KEUANGAN HARIAN + REKAP BULANAN -->
                <div class="space-y-5">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                        <div>
                            <h3 class="text-xl font-display font-black text-cocoa-950 flex items-center gap-2">
                                <i class="fa-solid fa-chart-pie text-amber-600"></i>
                                <span>Laporan Keuangan & Rekap Eksekutif — {{ $myCabang->nama_cabang }}</span>
                            </h3>
                            <p class="text-xs font-semibold text-cocoa-800">Ringkasan performa finansial harian dan akumulasi rekap bulanan toko cabang</p>
                        </div>
                        <span class="px-3.5 py-1.5 rounded-full bg-cocoa-900 text-gold-300 text-xs font-black border border-gold-400">
                            <i class="fa-solid fa-calendar-days mr-1"></i> Periode Bulan Ini (30 Hari Terakhir)
                        </span>
                    </div>

                    <!-- 4 KPI CARDS REKAP BULANAN -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        <div class="yellow-card rounded-2xl p-5 border-l-4 border-amber-600 shadow-md">
                            <span class="text-xs font-black uppercase text-cocoa-800 block">Total Pemasukan Bulan Ini</span>
                            <div class="mt-2 text-2xl font-display font-black text-cocoa-950">
                                Rp {{ number_format($rekapBulanan['total_cash'] + $rekapBulanan['total_cashless'], 0, ',', '.') }}
                            </div>
                            <span class="text-[11px] text-emerald-700 font-extrabold mt-1 block">
                                <i class="fa-solid fa-arrow-trend-up mr-1"></i> Akumulasi Cash & Cashless
                            </span>
                        </div>

                        <div class="yellow-card rounded-2xl p-5 border-l-4 border-emerald-600 shadow-md">
                            <span class="text-xs font-black uppercase text-cocoa-800 block">Pemasukan Cash vs Cashless</span>
                            <div class="mt-2 text-base font-display font-black text-cocoa-950 flex items-baseline justify-between">
                                <span>Cash: Rp {{ number_format($rekapBulanan['total_cash']/1000000, 1) }}M</span>
                                <span class="text-amber-700">QRIS: Rp {{ number_format($rekapBulanan['total_cashless']/1000000, 1) }}M</span>
                            </div>
                            <div class="w-full bg-cocoa-800/20 rounded-full h-2 mt-2.5 overflow-hidden">
                                @php
                                    $totIn = max($rekapBulanan['total_cash'] + $rekapBulanan['total_cashless'], 1);
                                    $pctCash = round(($rekapBulanan['total_cash'] / $totIn) * 100);
                                @endphp
                                <div class="bg-amber-600 h-full rounded-full" style="width: {{ $pctCash }}%"></div>
                            </div>
                        </div>

                        <div class="yellow-card rounded-2xl p-5 border-l-4 border-red-600 shadow-md">
                            <span class="text-xs font-black uppercase text-cocoa-800 block">Total Pengeluaran Operasional</span>
                            <div class="mt-2 text-2xl font-display font-black text-red-700">
                                Rp {{ number_format($rekapBulanan['total_pengeluaran'], 0, ',', '.') }}
                            </div>
                            <span class="text-[11px] text-cocoa-700 font-extrabold mt-1 block">
                                Biaya harian & kebersihan toko
                            </span>
                        </div>

                        <div class="yellow-card rounded-2xl p-5 border-l-4 border-cocoa-900 bg-cocoa-900 text-gold-300 shadow-lg">
                            <span class="text-xs font-black uppercase text-gold-400 block">Laba Bersih Bulan Ini (Net)</span>
                            <div class="mt-2 text-2xl font-display font-black text-white">
                                Rp {{ number_format($rekapBulanan['laba_bersih'], 0, ',', '.') }}
                            </div>
                            <span class="text-[11px] text-emerald-400 font-extrabold mt-1 block">
                                ✨ Profit bersih cabang Anda
                            </span>
                        </div>
                    </div>

                    <!-- TABEL READ LAPORAN KEUANGAN HARIAN -->
                    <div class="yellow-card rounded-2xl p-6 space-y-4">
                        <div class="border-b-2 border-gold-400 pb-3 flex items-center justify-between">
                            <h4 class="text-base font-display font-black text-cocoa-950">
                                <i class="fa-solid fa-table-list text-amber-600 mr-1.5"></i> Detail Laporan Keuangan Harian
                            </h4>
                            <span class="text-xs font-black text-cocoa-800">{{ $riwayatKeuangan->count() }} Hari Terakhir</span>
                        </div>
                        <div class="overflow-x-auto max-h-72 overflow-y-auto custom-scrollbar">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b-2 border-gold-400 text-xs font-black uppercase text-cocoa-900 bg-gold-300/60 sticky top-0">
                                        <th class="py-2.5 px-4 rounded-l-xl">Tanggal</th>
                                        <th class="py-2.5 px-4 text-right">Pemasukan Cash</th>
                                        <th class="py-2.5 px-4 text-right">Pemasukan Cashless</th>
                                        <th class="py-2.5 px-4 text-right">Pengeluaran</th>
                                        <th class="py-2.5 px-4">Keterangan</th>
                                        <th class="py-2.5 px-4 text-right rounded-r-xl">Kas Bersih Harian</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gold-300 text-xs font-semibold text-cocoa-950">
                                    @forelse($riwayatKeuangan as $rk)
                                        @php
                                            $netHari = ($rk->pemasukan_cash + $rk->pemasukan_cashless) - $rk->pengeluaran_nominal;
                                        @endphp
                                        <tr class="hover:bg-gold-200/50 transition">
                                            <td class="py-3 px-4 font-black">{{ \Carbon\Carbon::parse($rk->tanggal)->format('d M Y') }}</td>
                                            <td class="py-3 px-4 text-right text-emerald-800">Rp {{ number_format($rk->pemasukan_cash, 0, ',', '.') }}</td>
                                            <td class="py-3 px-4 text-right text-emerald-800">Rp {{ number_format($rk->pemasukan_cashless, 0, ',', '.') }}</td>
                                            <td class="py-3 px-4 text-right text-red-700">Rp {{ number_format($rk->pengeluaran_nominal, 0, ',', '.') }}</td>
                                            <td class="py-3 px-4 italic text-cocoa-800">{{ $rk->pengeluaran_keterangan ?: '-' }}</td>
                                            <td class="py-3 px-4 text-right font-black {{ $netHari >= 0 ? 'text-emerald-700' : 'text-red-600' }}">
                                                Rp {{ number_format($netHari, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-6 text-center text-cocoa-700 font-bold">Belum ada catatan keuangan harian dari Kasir Cabang.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 2. READ LAPORAN BAHAN BAKU (SISA STOK TOKO ANDA) -->
                <div class="yellow-card rounded-2xl p-6 space-y-5">
                    <div class="border-b-2 border-gold-400 pb-3 flex flex-col md:flex-row md:items-center justify-between gap-3">
                        <div>
                            <h3 class="text-xl font-display font-black text-cocoa-950 flex items-center gap-2">
                                <i class="fa-solid fa-boxes-stacked text-amber-600"></i>
                                <span>Read Laporan Sisa Stok Toko Anda ({{ $myCabang->nama_cabang }})</span>
                            </h3>
                            <p class="text-xs text-cocoa-800 font-medium">Monitoring sisa stok bahan baku & barang terkini di toko Anda</p>
                        </div>
                        <span class="px-3.5 py-1.5 rounded-full bg-amber-500 text-cocoa-950 text-xs font-black border border-amber-600">
                            <i class="fa-solid fa-layer-group mr-1"></i> 41+ Item Terdata
                        </span>
                    </div>

                    <!-- RINGKASAN STOK PREMIX TEPUNG -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-gold-200 border-2 border-gold-400 space-y-1 shadow">
                            <span class="text-xs font-black uppercase text-cocoa-900 flex items-center gap-1.5">
                                <i class="fa-solid fa-shop text-amber-700"></i> Stok Premix di Toko Anda ({{ $myCabang->nama_cabang }})
                            </span>
                            <div class="text-2xl font-display font-black text-cocoa-950">
                                {{ $calc['sisa_stok_saat_ini_kg'] ?? $myCabang->sisa_stok_terkini }} Kg
                            </div>
                            <span class="text-[11px] text-amber-800 font-bold block">
                                Pemakaian: ~{{ $calc['rata_rata_premix_harian_kg'] ?? 15 }} Kg/hari (Ketahanan ~{{ round(($calc['sisa_stok_saat_ini_kg'] ?? 45) / max($calc['rata_rata_premix_harian_kg'] ?? 15, 1)) }} hari)
                            </span>
                        </div>

                        <div class="p-4 rounded-xl bg-white/90 border-2 border-gold-400 space-y-1 shadow flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-black uppercase text-cocoa-900 block">Total Item Bahan & Barang di Toko</span>
                                <div class="text-2xl font-display font-black text-cocoa-950">
                                    {{ $bahanBakus->count() }} Item
                                </div>
                            </div>
                            <span class="text-[11px] text-cocoa-700 font-bold block">
                                Dikelompokkan dalam 5 kategori logistik
                            </span>
                        </div>
                    </div>

                    <!-- QUICK FORM UPDATE STOK BY OWNER CABANG -->
                    <form id="formUpdateStokOwner" onsubmit="submitUpdateStokCabang(event, {{ $myCabang->id }})" class="p-3 bg-gold-200/80 rounded-xl border-2 border-gold-400 space-y-2">
                        <div class="flex items-center justify-between text-xs font-black text-cocoa-950">
                            <span><i class="fa-solid fa-pen-to-square text-amber-600 mr-1.5"></i> Update Sisa Stok Toko Anda:</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
                            <div class="sm:col-span-6">
                                <select required onchange="pilihStokBahanCabang(this)"
                                    class="w-full px-3 py-2 rounded-xl bg-white border-2 border-gold-400 text-cocoa-950 font-bold text-xs focus:border-amber-600 focus:outline-none">
                                    <option value="">-- Pilih Bahan Baku / Barang --</option>
                                    @foreach($bahanBakus->groupBy('kategori') as $kategori => $items)
                                        <optgroup label="📦 {{ $kategori }}">
                                            @foreach($items as $bb)
                                                @php
                                                    $stokItem = $myCabang->stok_cabangs->where('nama_bahan', $bb->nama_bahan)->first();
                                                    $valStok = $stokItem ? $stokItem->stok : 0;
                                                @endphp
                                                <option value="{{ $bb->nama_bahan }}" data-satuan="{{ $bb->satuan }}" data-stok="{{ $valStok }}">{{ $bb->nama_bahan }} (Stok Toko: {{ $valStok }} {{ $bb->satuan }})</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-3">
                                <input type="number" required min="0" step="0.1" placeholder="Stok"
                                    class="w-full px-3 py-2 rounded-xl bg-white border-2 border-gold-400 text-cocoa-950 font-bold text-xs focus:border-amber-600 focus:outline-none">
                            </div>
                            <div class="sm:col-span-3 flex gap-1.5">
                                <input type="text" value="Kg" readonly
                                    class="w-14 px-2 py-2 rounded-xl bg-gold-100 border-2 border-gold-400 text-cocoa-900 font-black text-xs text-center">
                                <button type="submit"
                                    class="flex-1 py-2 px-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-cocoa-950 font-black text-xs transition shadow border-2 border-amber-600">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- TABEL SISA STOK TOKO ANDA -->
                    <div class="overflow-x-auto max-h-96 overflow-y-auto custom-scrollbar border-2 border-gold-400 rounded-xl bg-white/90">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-gold-300/80 text-cocoa-950 font-black uppercase border-b-2 border-gold-400 sticky top-0 z-10">
                                    <th class="py-3 px-4">Nama Bahan Baku / Barang</th>
                                    <th class="py-3 px-4">Kategori</th>
                                    <th class="py-3 px-4 text-center bg-amber-500 text-cocoa-950">🏪 Sisa Stok Toko Anda ({{ $myCabang->nama_cabang }})</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gold-200 font-semibold text-cocoa-950">
                                @foreach($bahanBakus->groupBy('kategori') as $kategori => $items)
                                    @foreach($items as $bb)
                                        @php
                                            $stokItem = $myCabang->stok_cabangs->where('nama_bahan', $bb->nama_bahan)->first();
                                            $valStokCabang = $stokItem ? number_format($stokItem->stok, 1) : '0';
                                        @endphp
                                        <tr class="hover:bg-gold-100/50 transition">
                                            <td class="py-2.5 px-4 font-black text-cocoa-950">{{ $bb->nama_bahan }}</td>
                                            <td class="py-2.5 px-4">
                                                <span class="px-2 py-0.5 rounded bg-gold-200 text-cocoa-900 font-bold text-[10px]">📦 {{ $bb->kategori }}</span>
                                            </td>
                                            <td class="py-2.5 px-4 text-center bg-amber-500/10 font-black text-amber-900 text-sm border-l border-gold-300">
                                                {{ $valStokCabang }} <span class="text-[10px] font-normal text-amber-800">{{ $bb->satuan }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. AI PREDIKSI REORDER POINT MUSIMAN -->
                <div class="yellow-card rounded-2xl p-6 space-y-5">
                    <div class="border-b-2 border-gold-400 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <h3 class="text-base font-display font-black text-cocoa-950 flex items-center gap-2">
                                    <i class="fa-solid fa-robot text-amber-600"></i>
                                    <span>AI Prediksi Reorder Point (ROP) Musiman</span>
                                </h3>
                                <p class="text-xs text-cocoa-800 font-medium">Kalkulasi prediktif mempertimbangkan hari libur, musim wisuda, dsb.</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-cocoa-900 text-gold-300 text-xs font-black">
                                ✨ Dynamic Seasonal AI
                            </span>
                        </div>

                        <!-- EVENT SELECTOR -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-cocoa-950">Pilih Kondisi Hari / Musim Transaksi Cabang:</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs font-black">
                                <button type="button" onclick="ubahFaktorMusim(1.0, 'Hari Biasa (Normal)')" id="btn-musim-normal"
                                    class="musim-btn py-2.5 px-3 rounded-xl bg-cocoa-900 text-gold-300 border-2 border-gold-500 shadow transition text-center flex flex-col items-center justify-center gap-0.5">
                                    <span>🏢 Hari Biasa</span>
                                    <span class="text-[9px] font-medium opacity-80">Default harian</span>
                                </button>
                                <button type="button" onclick="ubahFaktorMusim(1.25, 'Payday (Gajian)')" id="btn-musim-payday"
                                    class="musim-btn py-2.5 px-3 rounded-xl bg-gold-200 text-cocoa-950 border-2 border-gold-400 hover:bg-gold-300 transition text-center flex flex-col items-center justify-center gap-0.5">
                                    <span>💸 Payday</span>
                                    <span class="text-[9px] font-bold text-amber-700">{{ $eventDates['payday'] ?? 'Tgl 25' }}</span>
                                </button>
                                <button type="button" onclick="ubahFaktorMusim(1.6, 'Musim Wisuda Kampus')" id="btn-musim-wisuda"
                                    class="musim-btn py-2.5 px-3 rounded-xl bg-gold-200 text-cocoa-950 border-2 border-gold-400 hover:bg-gold-300 transition text-center flex flex-col items-center justify-center gap-0.5">
                                    <span>🎓 Musim Wisuda</span>
                                    <span class="text-[9px] font-bold text-amber-700">{{ $eventDates['wisuda'] ?? '19 Sep' }}</span>
                                </button>
                                <button type="button" onclick="ubahFaktorMusim(1.85, 'Musim Liburan Panjang')" id="btn-musim-liburan"
                                    class="musim-btn py-2.5 px-3 rounded-xl bg-gold-200 text-cocoa-950 border-2 border-gold-400 hover:bg-gold-300 transition text-center flex flex-col items-center justify-center gap-0.5">
                                    <span>🎒 Libur Panjang</span>
                                    <span class="text-[9px] font-bold text-amber-700">{{ $eventDates['liburan'] ?? '24 Des' }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- PENJELASAN KLASIFIKASI STATUS AI -->
                        <div class="p-3 bg-white/80 border-2 border-gold-300 rounded-xl shadow-sm space-y-2">
                            <span class="text-xs font-black text-cocoa-950 block"><i class="fa-solid fa-circle-info text-amber-600 mr-1"></i> Panduan Status AI (Klasifikasi Stok):</span>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-[10px]">
                                <div class="bg-emerald-50 border border-emerald-200 p-2 rounded-lg shadow-sm">
                                    <span class="px-2 py-0.5 bg-emerald-600 text-white rounded font-bold mb-1 inline-block">AMAN</span>
                                    <p class="text-cocoa-800 font-semibold leading-tight">Sisa stok melimpah (di atas ROP + 25%). Belum perlu memesan barang tambahan.</p>
                                </div>
                                <div class="bg-amber-50 border border-amber-200 p-2 rounded-lg shadow-sm">
                                    <span class="px-2 py-0.5 bg-amber-500 text-cocoa-900 rounded font-bold mb-1 inline-block">WASPADA</span>
                                    <p class="text-cocoa-800 font-semibold leading-tight">Stok mendekati batas kritis. Peringatan dini untuk segera bersiap melakukan order.</p>
                                </div>
                                <div class="bg-red-50 border border-red-200 p-2 rounded-lg shadow-sm">
                                    <span class="px-2 py-0.5 bg-red-500 text-white rounded font-bold mb-1 inline-block">ORDER SEKARANG</span>
                                    <p class="text-cocoa-800 font-semibold leading-tight">Stok sudah kritis (di bawah batas ROP). Segera pesan sekarang sebelum kehabisan!</p>
                                </div>
                            </div>
                        </div>

                        <!-- HASIL PREDIKSI AI ROP (TABEL SELURUH BAHAN BAKU) -->
                        <div class="bg-gold-200/90 rounded-xl border-2 border-gold-400 space-y-4 overflow-hidden">
                            <div class="p-4 flex items-center justify-between border-b-2 border-gold-400 bg-gold-300/50 text-xs font-black">
                                <span class="text-cocoa-900">Kondisi AI Aktif: <strong id="label-kondisi-musim" class="text-amber-700 uppercase bg-amber-200 px-2 py-1 rounded">Hari Biasa (Normal)</strong></span>
                                <span class="text-cocoa-800">Multiplier: <strong id="val-multiplier" class="bg-cocoa-900 text-gold-300 px-2 py-1 rounded">1.0x</strong></span>
                            </div>

                            <div class="px-4 pb-2 flex items-center justify-between">
                                <span class="text-[11px] text-cocoa-700 font-bold" id="ai-rop-status-text">✨ Stok di cabang Anda diprediksi aman untuk kondisi saat ini.</span>
                                <span class="text-[10px] text-cocoa-600 font-black"><i class="fa-solid fa-clock mr-1"></i> Lead Time: {{ $calc['lead_time_hari'] ?? 2 }} Hari</span>
                            </div>

                            <div class="overflow-x-auto max-h-96 overflow-y-auto custom-scrollbar border-t-2 border-gold-400 bg-white/90">
                                <table class="w-full text-left border-collapse text-xs">
                                    <thead>
                                        <tr class="bg-cocoa-900 text-gold-300 font-black uppercase sticky top-0 z-10 border-b border-cocoa-950">
                                            <th class="py-3 px-4">Nama Bahan Baku</th>
                                            <th class="py-3 px-4 text-center">Sisa Stok</th>
                                            <th class="py-3 px-4 text-center" title="Batas Aman + Waktu Pengiriman">Prediksi ROP <i class="fa-solid fa-robot ml-1 text-gold-400"></i></th>
                                            <th class="py-3 px-4 text-center">Status AI</th>
                                            <th class="py-3 px-4 text-center">Aksi Belanja</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gold-200 font-semibold text-cocoa-950">
                                        @php
                                            // Menggunakan nilai prediksi Linear Regression (7 Hari Ke Depan) dari Python AI
                                            $grafik = $ai['grafik_tren'] ?? [];
                                            $prediksi7Hari = $grafik['prediksi_7_hari_donat'] ?? [];
                                            
                                            // Rata-rata dari prediksi regresi linier masa depan
                                            $baseDonatAvg = count($prediksi7Hari) > 0 
                                                ? array_sum($prediksi7Hari) / count($prediksi7Hari) 
                                                : ($calc['rata_rata_donat_harian'] ?? 300);
                                        @endphp
                                        @foreach($bahanBakus->groupBy('kategori') as $kategori => $items)
                                            <tr class="bg-gold-100"><td colspan="5" class="px-4 py-1.5 text-[10px] font-black text-amber-800 uppercase tracking-widest border-b border-gold-300">📦 Kategori: {{ $kategori }}</td></tr>
                                            @foreach($items as $bb)
                                                @php
                                                    $stokItem = $myCabang->stok_cabangs->where('nama_bahan', $bb->nama_bahan)->first();
                                                    $valStokCabang = $stokItem ? $stokItem->stok : 0;
                                                    
                                                    // Base formula sederhana per kategori untuk Prediksi
                                                    $usageMultiplier = 0.001;
                                                    $safetyStockBase = 2; // Default 2
                                                    if ($bb->kategori === 'Bahan Pokok & Lemak') {
                                                        $usageMultiplier = 0.05;
                                                        $safetyStockBase = 15;
                                                    } elseif ($bb->kategori === 'Kemasan') {
                                                        $usageMultiplier = 1;
                                                        $safetyStockBase = 50;
                                                    } elseif ($bb->kategori === 'Glaze') {
                                                        $usageMultiplier = 0.01;
                                                        $safetyStockBase = 5;
                                                    } elseif ($bb->kategori === 'Topping') {
                                                        $usageMultiplier = 0.02;
                                                        $safetyStockBase = 5;
                                                    }
                                                    
                                                    // ROP Base = (Rata2 Harian * Penggunaan * LeadTime) + SafetyStock
                                                    $ropBase = ($baseDonatAvg * $usageMultiplier * ($calc['lead_time_hari'] ?? 2)) + $safetyStockBase;
                                                    $ropBase = round($ropBase, 2);
                                                @endphp
                                                <tr class="hover:bg-gold-100/50 transition rop-item-row" 
                                                    data-bahan="{{ $bb->nama_bahan }}" 
                                                    data-stok="{{ $valStokCabang }}" 
                                                    data-rop-base="{{ $ropBase }}"
                                                    data-satuan="{{ $bb->satuan }}">
                                                    <td class="py-2.5 px-4 font-black">{{ $bb->nama_bahan }}</td>
                                                    <td class="py-2.5 px-4 text-center font-bold">{{ number_format($valStokCabang, 1) }} <span class="text-[10px]">{{ $bb->satuan }}</span></td>
                                                    <td class="py-2.5 px-4 text-center font-black text-amber-700 bg-amber-500/10 border-x border-gold-200">
                                                        <span class="rop-calculated-val">{{ $ropBase }}</span> <span class="text-[10px]">{{ $bb->satuan }}</span>
                                                    </td>
                                                    <td class="py-2.5 px-4 text-center font-black rop-status-badge">
                                                        @if($valStokCabang <= $ropBase)
                                                            <span class="px-2 py-0.5 bg-red-500 text-white rounded text-[10px]">ORDER SEKARANG</span>
                                                        @elseif($valStokCabang <= $ropBase * 1.25)
                                                            <span class="px-2 py-0.5 bg-amber-500 text-cocoa-900 rounded text-[10px]">WASPADA</span>
                                                        @else
                                                            <span class="px-2 py-0.5 bg-emerald-600 text-white rounded text-[10px]">AMAN</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-2.5 px-4 text-center">
                                                        <button onclick="isiOtomatisPermintaanItem('{{ $bb->nama_bahan }}', this)" type="button"
                                                            class="px-2 py-1 rounded bg-cocoa-900 hover:bg-amber-600 text-gold-300 hover:text-white font-black text-[10px] transition shadow">
                                                            <i class="fa-solid fa-cart-plus"></i> Order
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. FORM PERMINTAAN BELANJA KE PUSAT & RIWAYAT PENGAJUAN -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- FORM PERMINTAAN BELANJA (5 KOLOM) -->
                    <div class="lg:col-span-5 yellow-card rounded-2xl p-6 space-y-4">
                        <div class="border-b-2 border-gold-400 pb-3">
                            <h3 class="text-base font-display font-black text-cocoa-950 flex items-center gap-2">
                                <i class="fa-solid fa-file-invoice-dollar text-amber-600"></i>
                                <span>Form Permintaan Belanja ke Pusat</span>
                            </h3>
                            <p class="text-xs text-cocoa-800 font-medium">Kirim pemesanan bahan baku untuk dikirim dari Dapur Lodaya</p>
                        </div>

                        <form id="formPermintaanBelanja" onsubmit="submitPermintaanBelanja(event, {{ $myCabang->id }})" class="space-y-4">
                            <div>
                                <label class="block text-xs font-black text-cocoa-950 mb-1">Pilih Bahan Baku yang Dibutuhkan:</label>
                                <select id="belanja_bahan"
                                    class="w-full px-4 py-2.5 rounded-xl bg-white border-2 border-gold-400 text-cocoa-950 font-bold text-sm focus:border-amber-600 focus:outline-none">
                                    @foreach($bahanBakus->groupBy('kategori') as $kategori => $items)
                                        <optgroup label="📦 {{ $kategori }}">
                                            @foreach($items as $bb)
                                                <option value="{{ $bb->nama_bahan }}" data-satuan="{{ $bb->satuan }}">{{ $bb->nama_bahan }} (Satuan: {{ $bb->satuan }})</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-black text-cocoa-950 mb-1">Jumlah Pesanan:</label>
                                    <input type="number" id="belanja_jumlah" min="1" step="0.5" placeholder="50"
                                        class="w-full px-4 py-2.5 rounded-xl bg-white border-2 border-gold-400 text-cocoa-950 font-bold text-sm focus:border-amber-600 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-cocoa-950 mb-1">Satuan:</label>
                                    <input type="text" id="belanja_satuan" value="Kg" readonly
                                        class="w-full px-4 py-2.5 rounded-xl bg-gold-100 border-2 border-gold-400 text-cocoa-900 font-black text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-black text-cocoa-950 mb-1">Catatan Tambahan (Misal: Event Wisuda):</label>
                                <input type="text" id="belanja_keterangan" placeholder="Contoh: Stok weekend wisuda UPI & UNPAD"
                                    class="w-full px-4 py-2.5 rounded-xl bg-white border-2 border-gold-400 text-cocoa-950 font-bold text-sm focus:border-amber-600 focus:outline-none">
                            </div>

                            <!-- TOMBOL TAMBAH KE KERANJANG -->
                            <button type="button" onclick="tambahItemKeranjang()"
                                class="w-full py-2.5 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-cocoa-950 font-black text-xs tracking-wide shadow transition flex items-center justify-center gap-2 border-2 border-amber-600">
                                <i class="fa-solid fa-cart-plus text-sm"></i> + Tambah Item ke Daftar Pesanan
                            </button>

                            <!-- DAFTAR ITEM KERANJANG BELANJA -->
                            <div class="pt-3 border-t-2 border-gold-300">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-black text-cocoa-950 flex items-center gap-1.5">
                                        <i class="fa-solid fa-cart-shopping text-amber-700"></i> Daftar Pesanan (<span id="keranjangCount" class="font-display font-black text-amber-700">0</span> Item)
                                    </span>
                                    <button type="button" onclick="resetKeranjang()" class="text-[11px] text-red-600 hover:underline font-bold">Hapus Semua</button>
                                </div>
                                <div class="max-h-48 overflow-y-auto rounded-xl border-2 border-gold-400 bg-white/90 p-2 shadow-inner">
                                    <table class="w-full text-left text-xs">
                                        <thead class="text-cocoa-800 border-b-2 border-gold-300 font-black text-[10px] uppercase">
                                            <tr>
                                                <th class="py-1.5 px-2">Bahan / Barang</th>
                                                <th class="py-1.5 px-2">Qty</th>
                                                <th class="py-1.5 px-2">Catatan</th>
                                                <th class="py-1.5 px-2 text-right">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="keranjangTableBody" class="divide-y divide-gold-200 text-cocoa-950 font-medium text-xs">
                                            <tr id="keranjangEmptyRow">
                                                <td colSpan="4" class="py-4 text-center text-cocoa-700 font-medium italic text-xs">
                                                    Belum ada item di daftar pesanan. Pilih bahan dan klik "+ Tambah Item ke Daftar Pesanan".
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <button type="submit" id="btnSubmitKeranjang"
                                class="w-full py-3.5 px-4 rounded-xl bg-cocoa-900 hover:bg-cocoa-950 text-gold-300 font-black text-xs tracking-wide shadow-lg transition flex items-center justify-center gap-2 border-2 border-gold-400">
                                <i class="fa-solid fa-paper-plane text-sm"></i> Kirim Semua Permintaan (<span id="keranjangSubmitCount">0</span> Item) ke Pusat
                            </button>
                        </form>
                    </div>

                    <!-- RIWAYAT PERMINTAAN BELANJA CABANG SAYA (7 KOLOM) -->
                    <div class="lg:col-span-7 yellow-card rounded-2xl p-6 space-y-4">
                        <div class="border-b-2 border-gold-400 pb-3 flex items-center justify-between">
                            <h3 class="text-base font-display font-black text-cocoa-950 flex items-center gap-2">
                                <i class="fa-solid fa-clipboard-list text-amber-600"></i>
                                <span>Status Permintaan Belanja Cabang Saya</span>
                            </h3>
                            <span class="text-xs font-black text-cocoa-800">{{ $myRequests->count() }} Permintaan</span>
                        </div>

                        <div class="overflow-x-auto max-h-72 overflow-y-auto custom-scrollbar">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b-2 border-gold-400 text-xs font-black uppercase text-cocoa-900 bg-gold-300/60 sticky top-0">
                                        <th class="py-2.5 px-4 rounded-l-xl">Bahan Baku</th>
                                        <th class="py-2.5 px-4 text-right">Qty</th>
                                        <th class="py-2.5 px-4">Catatan</th>
                                        <th class="py-2.5 px-4 text-right rounded-r-xl">Status Admin</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gold-300 text-xs font-semibold text-cocoa-950">
                                    @forelse($myRequests as $mr)
                                        <tr class="hover:bg-gold-200/50 transition">
                                            <td class="py-3 px-4 font-black">{{ $mr->nama_bahan }}</td>
                                            <td class="py-3 px-4 text-right font-black text-amber-800">{{ $mr->jumlah }} {{ $mr->satuan }}</td>
                                            <td class="py-3 px-4 italic text-cocoa-800">{{ $mr->keterangan }}</td>
                                            <td class="py-3 px-4 text-right">
                                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-black {{ $mr->status == 'Menunggu Persetujuan' ? 'bg-amber-500 text-cocoa-950' : ($mr->status == 'Diproses' ? 'bg-blue-600 text-white' : 'bg-emerald-600 text-white') }}">
                                                    {{ $mr->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-6 text-center text-cocoa-700 font-bold">Belum ada permintaan belanja yang diajukan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


@endsection
