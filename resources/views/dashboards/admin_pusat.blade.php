@extends('layouts.app')
@section('content')
    <!-- HEADER -->
    <div class="bg-gold-200 border-b-2 border-gold-400 px-6 py-4 shadow-md rounded-2xl mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-black text-cocoa-950 uppercase">👑 Dashboard Admin Pusat – Logistik & Optimasi Rute</h2>
            <p class="text-xs font-semibold text-cocoa-800">Wewenang memproses pengajuan permintaan belanja dari seluruh cabang dan menghitung optimasi rute pengiriman.</p>
        </div>
    </div>

<!-- ========================================================================= -->
            <!-- DASHBOARD ADMIN PUSAT: STOK BAHAN BAKU, PERMINTAAN BELANJA & OPTIMASI RUTE -->
            <!-- ========================================================================= -->

            @if($activeRole == 'admin_pusat')
                <!-- 0. TABEL STOK BAHAN BAKU YANG TERSEDIA DI PUSAT DAN SETIAP CABANG -->
                <div class="yellow-card rounded-2xl p-6 space-y-5 mb-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between border-b-2 border-gold-400 pb-3.5 gap-3">
                        <div>
                            <h3 class="text-xl font-display font-black text-cocoa-950 flex items-center gap-2.5">
                                <i class="fa-solid fa-boxes-stacked text-amber-600 text-xl"></i>
                                <span>Tabel Stok Bahan Baku yang Tersedia di Pusat & Setiap Cabang</span>
                            </h3>
                            <p class="text-xs text-cocoa-800 font-medium">Monitoring menyeluruh sisa stok seluruh 41+ item di Dapur Pusat Lodaya (Hub 1) dan di setiap toko cabang</p>
                        </div>
                        <span class="px-3.5 py-1.5 rounded-full bg-amber-500 text-cocoa-950 text-xs font-black border border-amber-600 shadow-xs">
                            <i class="fa-solid fa-check-to-slot mr-1"></i> {{ count($bahanBakus) }} Item Terdaftar
                        </span>
                    </div>

                    <!-- RINGKASAN EKSEKUTIF STOK PREMIX -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @php
                            $premixCenter = $bahanBakus->where('nama_bahan', 'Tepung Terigu Premix')->first();
                            $stokPremixPusat = $premixCenter ? $premixCenter->stok_pusat : 2500;
                            $totalPremixCabang = 0;
                            foreach($cabangs as $c) {
                                $stItem = $c->stok_cabangs->where('nama_bahan', 'Tepung Terigu Premix')->first();
                                if($stItem) $totalPremixCabang += $stItem->stok;
                            }
                        @endphp
                        <div class="p-4 rounded-xl bg-cocoa-900 text-gold-300 border-2 border-gold-400 space-y-1 shadow">
                            <span class="text-xs font-black uppercase text-gold-400 flex items-center gap-1.5">
                                <i class="fa-solid fa-warehouse text-amber-500"></i> Total Stok Premix di Pusat (Lodaya)
                            </span>
                            <div class="text-2xl font-display font-black text-white">
                                {{ number_format($stokPremixPusat) }} Kg
                            </div>
                            <span class="text-[11px] text-emerald-400 font-bold block">
                                <i class="fa-solid fa-check-circle mr-1"></i> Kapasitas Dapur Utama
                            </span>
                        </div>

                        <div class="p-4 rounded-xl bg-gold-200 border-2 border-gold-400 space-y-1 shadow">
                            <span class="text-xs font-black uppercase text-cocoa-900 flex items-center gap-1.5">
                                <i class="fa-solid fa-store text-amber-700"></i> Total Stok Premix di Seluruh Cabang
                            </span>
                            <div class="text-2xl font-display font-black text-cocoa-950">
                                {{ number_format($totalPremixCabang, 1) }} Kg
                            </div>
                            <span class="text-[11px] text-amber-800 font-bold block">
                                Tersebar di {{ count($cabangs) }} toko cabang aktif
                            </span>
                        </div>

                        <div class="p-4 rounded-xl bg-white/90 border-2 border-gold-400 space-y-1 shadow flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-black uppercase text-cocoa-900 block">Total Item & Kategori Logistik</span>
                                <div class="text-2xl font-display font-black text-cocoa-950">
                                    {{ count($bahanBakus) }} Item
                                </div>
                            </div>
                            <span class="text-[11px] text-cocoa-700 font-bold block">
                                Bahan Pokok, Kemasan, Glaze, Topping & Seragam
                            </span>
                        </div>
                    </div>

                    <!-- TABEL MATRIX STOK PUSAT & CABANG FOR ADMIN -->
                    <div class="overflow-x-auto max-h-[520px] overflow-y-auto custom-scrollbar border-2 border-gold-400 rounded-xl bg-white/90">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-gold-300/90 text-cocoa-950 font-black uppercase border-b-2 border-gold-400 sticky top-0 z-10">
                                    <th class="py-3 px-3">ID</th>
                                    <th class="py-3 px-4">Nama Bahan Baku / Barang</th>
                                    <th class="py-3 px-3">Kategori</th>
                                    <th class="py-3 px-4 text-center bg-cocoa-900 text-gold-300">🏢 Stok Tersedia di Pusat (Lodaya)</th>
                                    <th class="py-3 px-4 bg-amber-500 text-cocoa-950">📍 Stok Tersedia di Setiap Cabang</th>
                                    <th class="py-3 px-3 text-center">Status Global</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gold-200 font-semibold text-cocoa-950">
                                @foreach($bahanBakus->groupBy('kategori') as $kategori => $items)
                                    @foreach($items as $bahan)
                                        @php
                                            $stok = $bahan->stok_pusat;
                                            $maxCapacity = $bahan->satuan == 'Pcs' ? 15000 : 2000;
                                            $pct = min(100, round(($stok / $maxCapacity) * 100));
                                            $statusStok = $pct < 25 ? 'Perlu Restock' : 'Aman';
                                            $statusColor = $pct < 25 ? 'text-white bg-red-600 font-bold' : 'text-white bg-emerald-600 font-bold';
                                        @endphp
                                        <tr class="hover:bg-gold-100/60 transition">
                                            <td class="py-2.5 px-3 font-mono text-cocoa-700 font-bold">#{{ $bahan->id }}</td>
                                            <td class="py-2.5 px-4 font-black text-cocoa-950 text-xs">{{ $bahan->nama_bahan }}</td>
                                            <td class="py-2.5 px-3">
                                                <span class="px-2 py-0.5 rounded bg-gold-200 text-cocoa-900 font-bold text-[10px]">📦 {{ $kategori }}</span>
                                            </td>
                                            <td class="py-2.5 px-4 text-center bg-cocoa-900/5 font-black text-cocoa-900 text-sm">
                                                {{ number_format($stok) }} <span class="text-[10px] font-normal text-cocoa-700">{{ $bahan->satuan }}</span>
                                            </td>
                                            <td class="py-2.5 px-4 bg-amber-500/10 border-x border-gold-300">
                                                <div class="flex flex-wrap gap-1.5">
                                                    @foreach($cabangs as $c)
                                                        @php
                                                            $cStokItem = $c->stok_cabangs->where('nama_bahan', $bahan->nama_bahan)->first();
                                                            $valCStok = $cStokItem ? number_format($cStokItem->stok, 1) : '0';
                                                        @endphp
                                                        <span class="inline-flex items-center gap-1 bg-gold-100 border border-gold-400 text-cocoa-950 px-2 py-0.5 rounded text-[10px] font-bold shadow-2xs">
                                                            <span class="text-cocoa-800">{{ str_replace('Donat Menak ', '', $c->nama_cabang) }}:</span>
                                                            <strong class="text-amber-800 font-black">{{ $valCStok }}</strong> {{ $bahan->satuan }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="py-2.5 px-3 text-center">
                                                <span class="px-2.5 py-0.5 rounded-md text-[10px] {{ $statusColor }} shadow-2xs">{{ $statusStok }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- 1. PROSES PERMINTAAN BELANJA CABANG -->
            <div class="yellow-card rounded-2xl p-6 space-y-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between border-b-2 border-gold-400 pb-3.5 gap-3">
                    <div>
                        <h3 class="text-lg font-display font-black text-cocoa-950 flex items-center gap-2.5">
                            <i class="fa-solid fa-cart-flatbed text-amber-600 text-xl"></i>
                            <span>Proses Permintaan Belanja Cabang</span>
                        </h3>
                        <p class="text-xs text-cocoa-800 font-medium">Daftar permintaan pasokan dan reorder bahan baku dari Owner & Kasir Cabang</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-xs font-bold">
                        <span class="px-3 py-1 rounded-full bg-white/90 border border-gold-500 text-cocoa-900">
                            Total: <strong class="text-amber-700">{{ $permintaanBelanjas->count() }}</strong> Pesanan
                        </span>
                        <span class="px-3 py-1 rounded-full bg-amber-100 border border-amber-400 text-amber-900">
                            Menunggu: <strong>{{ $permintaanBelanjas->where('status', 'Menunggu Persetujuan')->count() }}</strong>
                        </span>
                    </div>
                </div>

                <!-- TABEL PERMINTAAN BELANJA -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-gold-400 text-xs font-black uppercase text-cocoa-900 bg-gold-300/60">
                                <th class="py-3 px-4 rounded-l-xl">Cabang Pemesan</th>
                                <th class="py-3 px-4">Bahan Baku & Qty</th>
                                <th class="py-3 px-4">Keterangan</th>
                                <th class="py-3 px-4">Status Saat Ini</th>
                                <th class="py-3 px-4 text-right rounded-r-xl">Aksi Proses Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gold-300 text-xs font-semibold text-cocoa-950">
                            @forelse($permintaanBelanjas as $req)
                                <tr class="hover:bg-gold-200/50 transition">
                                    <td class="py-3.5 px-4 font-black">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                                            <span>{{ $req->nama_cabang }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 font-extrabold text-cocoa-900">
                                        {{ $req->nama_bahan }} — <span class="text-amber-700">{{ $req->jumlah }} {{ $req->satuan }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 text-cocoa-800 italic">
                                        "{{ $req->keterangan }}"
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2.5 py-1 rounded-lg text-[11px] font-black {{ $req->status == 'Menunggu Persetujuan' ? 'bg-amber-500 text-cocoa-950 shadow-sm' : ($req->status == 'Diproses' ? 'bg-blue-600 text-white shadow-sm' : 'bg-emerald-600 text-white shadow-sm') }}">
                                            {{ $req->status }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right space-x-1.5">
                                        @if($req->status == 'Menunggu Persetujuan')
                                            <button onclick="prosesPermintaanBelanja({{ $req->id }}, 'Diproses')"
                                                class="px-3 py-1.5 rounded-lg bg-cocoa-900 hover:bg-cocoa-950 text-gold-300 font-black transition shadow-sm border border-gold-400">
                                                <i class="fa-solid fa-truck-fast mr-1"></i> Setujui & Kirim
                                            </button>
                                        @elseif($req->status == 'Diproses')
                                            <button onclick="prosesPermintaanBelanja({{ $req->id }}, 'Selesai')"
                                                class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-black transition shadow-sm">
                                                <i class="fa-solid fa-check-double mr-1"></i> Selesai
                                            </button>
                                        @else
                                            <span class="text-emerald-700 font-extrabold"><i class="fa-solid fa-circle-check mr-1"></i> Tiba di Cabang</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-cocoa-700 font-bold">Belum ada permintaan belanja dari cabang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. OPTIMASI RUTE DISTRIBUSI (TSP OSRM & LEAFLET MAP) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- PANEL KIRI: MANIFEST BARANG YANG AKAN DIKIRIM & PILIH RUTE -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="yellow-card rounded-2xl p-6 space-y-5">
                        <div class="border-b-2 border-gold-400 pb-3.5">
                            <h3 class="text-base font-display font-black text-cocoa-950 flex items-center gap-2">
                                <i class="fa-solid fa-boxes-packing text-amber-600"></i>
                                <span>Manifest Barang Yang Akan Dikirim</span>
                            </h3>
                            <p class="text-xs text-cocoa-800 font-medium">Pilih cabang tujuan pengiriman dari Dapur Pusat Lodaya hari ini</p>
                        </div>

                        <!-- Checkboxes & Manifest Cargo per Branch -->
                        <div class="space-y-3 max-h-[420px] overflow-y-auto custom-scrollbar pr-1">
                            @foreach($cabangs as $cabang)
                                @php
                                    $ai = $cabang->ai_data ?? [];
                                    $calc = $ai['kalkulasi'] ?? [];
                                    $statusCode = $ai['status_code'] ?? 'AMAN';
                                    $eoq = $calc['saran_order_kg'] ?? 50;
                                    $glazeQty = round($eoq * 0.35);
                                    $boxQty = round($eoq * 10);
                                    $totalWeight = $eoq + $glazeQty + round($boxQty * 0.05);
                                    $badge = $statusCode == 'KRITIS' ? '⚠️ Kritis (Butuh Cepat)' : ($statusCode == 'WASPADA' ? '🔔 Waspada' : '✨ Aman');
                                @endphp
                                <div class="p-3.5 rounded-xl bg-gold-200/90 border-2 border-gold-400 hover:border-amber-600 transition shadow-sm">
                                    <label class="flex items-center justify-between cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" name="dispatch_branches[]" value="{{ $cabang->id }}" checked
                                                data-name="{{ $cabang->nama_cabang }}"
                                                data-lat="{{ $cabang->latitude }}"
                                                data-lng="{{ $cabang->longitude }}"
                                                data-premix="{{ $eoq }}"
                                                data-glaze="{{ $glazeQty }}"
                                                data-box="{{ $boxQty }}"
                                                data-weight="{{ $totalWeight }}"
                                                onchange="updateManifestSummary()"
                                                class="branch-dispatch-cb w-4 h-4 rounded text-amber-600 bg-white border-gold-400">
                                            <div>
                                                <span class="text-sm font-black text-cocoa-950 block">{{ $cabang->nama_cabang }}</span>
                                                <span class="text-[11px] text-cocoa-700 font-semibold"><i class="fa-solid fa-location-dot mr-1 text-amber-600"></i>{{ $cabang->alamat }}</span>
                                            </div>
                                        </div>
                                        <span class="text-[10px] px-2.5 py-0.5 rounded-md font-extrabold {{ $statusCode == 'KRITIS' ? 'bg-red-500 text-white shadow-sm' : ($statusCode == 'WASPADA' ? 'bg-amber-500 text-cocoa-950 shadow-sm' : 'bg-emerald-600 text-white shadow-sm') }}">
                                            {{ $badge }}
                                        </span>
                                    </label>

                                    <!-- Cargo Breakdown for this Branch -->
                                    <div class="mt-3 pt-2.5 border-t-2 border-gold-300 grid grid-cols-3 gap-2 text-center text-xs">
                                        <div class="bg-white/80 p-2 rounded-lg border border-gold-400">
                                            <span class="block text-[10px] text-cocoa-700 font-bold">Premix Tepung</span>
                                            <span class="font-black text-cocoa-950">{{ $eoq }} Kg</span>
                                        </div>
                                        <div class="bg-white/80 p-2 rounded-lg border border-gold-400">
                                            <span class="block text-[10px] text-cocoa-700 font-bold">Glaze Cokelat</span>
                                            <span class="font-black text-amber-700">{{ $glazeQty }} Kg</span>
                                        </div>
                                        <div class="bg-white/80 p-2 rounded-lg border border-gold-400">
                                            <span class="block text-[10px] text-cocoa-700 font-bold">Box Kemasan</span>
                                            <span class="font-black text-cocoa-900">{{ $boxQty }} Pcs</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Total Manifest Summary Box -->
                        <div class="bg-cocoa-900 text-gold-300 p-4 rounded-xl border-2 border-cocoa-950 space-y-2 shadow-md">
                            <div class="flex justify-between items-center text-xs font-bold">
                                <span>Total Cabang Tujuan:</span>
                                <span class="text-white font-black" id="manifest-total-cabang">4 Toko</span>
                            </div>
                            <div class="flex justify-between items-center text-xs font-bold">
                                <span>Total Muatan Logistik:</span>
                                <span class="text-gold-400 font-display font-black text-base" id="manifest-total-berat">232 Kg</span>
                            </div>
                        </div>

                        <button onclick="kalkulasiRuteJalanAsli()" id="btn-kalkulasi-asli"
                            class="w-full py-4 px-6 rounded-xl bg-cocoa-900 hover:bg-cocoa-950 text-gold-300 font-black text-sm tracking-wide shadow-lg transition flex items-center justify-center gap-2.5 border-2 border-gold-400">
                            <i class="fa-solid fa-route text-lg text-gold-400"></i>
                            <span>⚡ KALKULASI RUTE MERAH VS EMAS (OSRM MAP)</span>
                        </button>
                    </div>
                </div>

                <!-- PANEL KANAN: PETA ASLI BANDUNG (LEAFLET OSM & JALAN ASLI) -->
                <div class="lg:col-span-7 flex flex-col space-y-6">
                    <div class="yellow-card rounded-2xl p-5 flex flex-col">
                        <div class="flex items-center justify-between border-b-2 border-gold-400 pb-3 mb-4">
                            <div>
                                <h3 class="text-base font-display font-black text-cocoa-950 flex items-center gap-2">
                                    <i class="fa-solid fa-map-location-dot text-amber-600"></i>
                                    <span>Peta Distribusi Real-Time & Jalur Jalan Raya Asli (Bandung)</span>
                                </h3>
                                <p class="text-xs text-cocoa-800 font-medium">Menggunakan OpenStreetMap & Open Source Routing Machine (OSRM)</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick="toggleMapStyle('dark')" class="px-3 py-1.5 rounded-lg bg-cocoa-900 text-gold-300 border border-cocoa-950 text-xs font-bold hover:bg-cocoa-800">
                                    Dark Map
                                </button>
                                <button onclick="toggleMapStyle('street')" class="px-3 py-1.5 rounded-lg bg-white text-cocoa-950 border-2 border-gold-500 text-xs font-bold hover:bg-gold-200">
                                    Street Map
                                </button>
                            </div>
                        </div>

                        <!-- Map Legend Banner (DOMINAN KUNING & KONTRAS) -->
                        <div class="mb-3 flex flex-wrap items-center justify-between gap-2 bg-gold-200 px-4 py-2.5 rounded-xl border-2 border-gold-400 text-xs font-bold shadow-inner">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-2 rounded bg-red-600 border border-red-800"></span>
                                <span class="text-red-700 font-black">Garis Merah: <b class="text-cocoa-950">Rute Belum Dioptimasi</b></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-2 rounded bg-amber-500 border border-amber-700"></span>
                                <span class="text-amber-800 font-black">Garis Emas: <b class="text-cocoa-950">Rute Teroptimasi (AI TSP)</b></span>
                            </div>
                        </div>

                        <!-- Real Leaflet Map Container -->
                        <div id="realBandungMap" class="w-full h-[430px] rounded-xl border-2 border-gold-500 relative z-10 shadow-inner"></div>

                        <!-- Route Navigation Results & Step Itinerary -->
                        <div class="mt-5 space-y-4" id="real-route-info" style="display: none;">
                            <!-- Perbandingan Rute Belum Dioptimasi vs Teroptimasi -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <!-- Rute Belum Dioptimasi (Merah) -->
                                <div class="bg-red-100 p-3.5 rounded-xl border-2 border-red-400 text-center relative overflow-hidden shadow-sm">
                                    <span class="block text-[10px] text-red-700 font-black uppercase">🔴 Rute Belum Dioptimasi</span>
                                    <div class="mt-1 flex items-baseline justify-center gap-1">
                                        <span class="text-xl font-display font-black text-red-700" id="osm-jarak-unopt">0</span> 
                                        <span class="text-xs text-red-600 font-bold">Km</span>
                                    </div>
                                    <span class="text-[11px] text-red-800 font-semibold block mt-0.5" id="osm-waktu-unopt">~0 Menit</span>
                                </div>

                                <!-- Rute Setelah Dioptimasi (Emas/Kuning Dominan) -->
                                <div class="bg-gold-300 p-3.5 rounded-xl border-2 border-amber-600 text-center relative overflow-hidden shadow-md">
                                    <span class="block text-[10px] text-cocoa-950 font-black uppercase">🟡 Rute Teroptimasi (AI TSP)</span>
                                    <div class="mt-1 flex items-baseline justify-center gap-1">
                                        <span class="text-xl font-display font-black text-cocoa-950" id="osm-jarak">0</span> 
                                        <span class="text-xs text-cocoa-900 font-bold">Km</span>
                                    </div>
                                    <span class="text-[11px] text-cocoa-950 font-bold block mt-0.5" id="osm-waktu">~0 Menit</span>
                                </div>

                                <!-- Efisiensi Hemat -->
                                <div class="bg-emerald-100 p-3.5 rounded-xl border-2 border-emerald-500 text-center relative overflow-hidden shadow-sm">
                                    <span class="block text-[10px] text-emerald-800 font-black uppercase">✨ Efisiensi Hemat Rute</span>
                                    <div class="mt-1 flex items-baseline justify-center gap-1">
                                        <span class="text-xl font-display font-black text-emerald-700" id="osm-hemat-km">0</span> 
                                        <span class="text-xs text-emerald-700 font-bold">Km</span>
                                    </div>
                                    <span class="text-[11px] text-emerald-800 font-bold block mt-0.5" id="osm-hemat-waktu">Hemat 0 Menit</span>
                                </div>
                            </div>

                            <!-- List Barang yang Dikirim ke Tiap Titik Pemberhentian -->
                            <div class="space-y-2">
                                <span class="text-xs font-black uppercase text-cocoa-950 block">📋 Jadwal Pemberhentian Rute Teroptimasi (AI TSP) & Muatan Bongkar Barang:</span>
                                <div class="max-h-48 overflow-y-auto custom-scrollbar space-y-2 pr-1" id="osm-itinerary">
                                    <!-- Diisi oleh JS -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


@endsection
