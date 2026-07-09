<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Donat Menak - The Circle Of Happiness | Sistem Informasi Logistik</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        cocoa: {
                            950: '#150a06', // Deep espresso
                            900: '#23120b', // Rich chocolate text
                            800: '#321a10',
                            700: '#48271a',
                            600: '#633726',
                        },
                        gold: {
                            50:  '#fefce8', // Creamy warm yellow background
                            100: '#fef9c3', // Soft yellow card fill
                            200: '#fef08a', // Vibrant pastel yellow
                            300: '#fde047', // Sunlit yellow
                            400: '#facc15', // Donat Menak signature yellow
                            500: '#eab308', // Rich golden amber
                            600: '#ca8a04',
                        }
                    },
                    boxShadow: {
                        'glow-gold': '0 10px 30px -5px rgba(234, 179, 8, 0.35)',
                        'card-yellow': '0 8px 25px -6px rgba(202, 138, 4, 0.18)',
                    }
                }
            }
        }
    </script>

    <!-- Chart.js & Icons -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Leaflet & Real Road Routing CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body {
            background-color: #fefce8;
            color: #23120b;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-image: 
                radial-gradient(circle at 10% 15%, rgba(250, 204, 21, 0.45) 0%, transparent 40%),
                radial-gradient(circle at 90% 85%, rgba(253, 224, 71, 0.5) 0%, transparent 45%),
                radial-gradient(circle at 50% 50%, rgba(254, 240, 138, 0.6) 0%, transparent 60%);
        }
        .yellow-card {
            background: rgba(254, 249, 195, 0.94);
            backdrop-filter: blur(16px);
            border: 2px solid #facc15;
            box-shadow: 0 8px 25px -6px rgba(202, 138, 4, 0.2);
        }
        .yellow-card:hover {
            border-color: #eab308;
            box-shadow: 0 12px 30px -6px rgba(202, 138, 4, 0.3);
        }
        .yellow-header {
            background: linear-gradient(135deg, #fde047 0%, #facc15 60%, #eab308 100%);
            border-bottom: 2px solid #ca8a04;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #fef9c3;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #eab308;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #ca8a04;
        }
        @keyframes sparkle {
            0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.8; }
            50% { transform: scale(1.2) rotate(180deg); opacity: 1; }
        }
        .animate-sparkle {
            animation: sparkle 4s ease-in-out infinite;
        }
        .leaflet-popup-content-wrapper {
            background: #fefce8 !important;
            color: #23120b !important;
            border: 2px solid #eab308 !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
        }
        .leaflet-popup-tip {
            background: #fefce8 !important;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-cocoa-900 selection:text-gold-300">

    <!-- TOP NAVBAR (DOMINAN KUNING EMAS) -->
    <header class="sticky top-0 z-40 yellow-header px-6 py-4 shadow-xl">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            
            <!-- Logo Section Inspired by Donat Menak Logo -->
            <div class="flex items-center gap-3.5">
                <div class="w-14 h-14 rounded-2xl overflow-hidden shadow-lg shrink-0 border-2 border-cocoa-900 bg-white flex items-center justify-center p-0.5">
                    <img src="{{ asset('images/logo-icon.png') }}" alt="Donat Menak Logo" class="w-full h-full object-contain">
                </div>

                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-display font-black tracking-tight text-cocoa-950 uppercase">
                            DONAT MENAK
                        </h1>
                        <span class="text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full bg-cocoa-900 text-gold-300 shadow-sm">
                            RBAC Engine
                        </span>
                    </div>
                    <p class="text-xs text-cocoa-800 font-bold tracking-wide flex items-center gap-1.5 mt-0.5">
                        <span class="animate-sparkle text-cocoa-900">✨</span> 
                        <span>The Circle Of Happiness</span> 
                        <span class="animate-sparkle text-cocoa-900">✨</span>
                        <span class="text-cocoa-700 mx-1">•</span>
                        <span class="text-cocoa-900 font-semibold text-[11px]">Sistem Logistik Dominan Kuning</span>
                    </p>
                </div>
            </div>

            <!-- System Health Indicators -->
            <div class="flex items-center gap-3 text-xs font-bold">
                <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/90 border border-gold-500 shadow-sm text-cocoa-900">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <span>Peta Asli:</span>
                    <span class="text-emerald-700 font-extrabold">OSRM & Leaflet OSM</span>
                </div>
                <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/90 border border-gold-500 shadow-sm text-cocoa-900">
                    @if($aiStatus)
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>FastAPI AI:</span>
                        <span class="text-emerald-700 font-extrabold">Connected (:8000)</span>
                    @else
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                        <span>FastAPI AI:</span>
                        <span class="text-red-600 font-extrabold">Offline (PHP Fallback)</span>
                    @endif
                </div>
                <div class="bg-cocoa-900 text-gold-300 px-3.5 py-1.5 rounded-xl border border-cocoa-800 font-mono text-xs shadow-sm">
                    <i class="fa-regular fa-clock mr-1 text-gold-400"></i> <span id="current-clock">{{ date('H:i:s') }} WIB</span>
                </div>
            </div>
        </div>
    </header>

    <!-- RBAC ROLE SELECTION BAR (DOMINAN KUNING CERAH) -->
    <section class="bg-gold-200 border-b-2 border-gold-400 px-6 py-3 shadow-md">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2 text-xs">
                <span class="px-2.5 py-1 rounded-lg bg-cocoa-900 text-gold-300 font-extrabold uppercase tracking-wide">
                    <i class="fa-solid fa-user-shield mr-1"></i> Role Aktif Saat Ini:
                </span>
                <span class="text-cocoa-950 font-black text-sm">
                    @if($activeRole == 'admin_pusat' || $activeRole == 'koordinator_logistik')
                        👑 ADMIN PUSAT (Proses Permintaan Belanja Cabang & Optimasi Rute)
                    @elseif($activeRole == 'owner_cabang')
                        👔 OWNER CABANG (Laporan Keuangan, Laporan Bahan Baku, AI Prediksi ROP & Request Belanja)
                    @else
                        🏪 KASIR CABANG (Input Laporan Keuangan Harian & Sisa Bahan Harian)
                    @endif
                </span>
            </div>

            <!-- Role Switcher Pills -->
            <div class="flex flex-wrap items-center gap-1.5 bg-gold-300 p-1.5 rounded-xl border-2 border-gold-500 text-xs font-black shadow-inner">
                <a href="?role=admin_pusat" 
                   class="px-3.5 py-1.5 rounded-lg transition {{ ($activeRole == 'admin_pusat' || $activeRole == 'koordinator_logistik') ? 'bg-cocoa-900 text-gold-300 shadow-md' : 'text-cocoa-900 hover:bg-gold-400' }}">
                   👑 ADMIN PUSAT
                </a>
                <a href="?role=kasir_cabang&cabang_id={{ $selectedCabangId }}" 
                   class="px-3.5 py-1.5 rounded-lg transition {{ ($activeRole == 'kasir_cabang' || $activeRole == 'petugas_cabang') ? 'bg-cocoa-900 text-gold-300 shadow-md' : 'text-cocoa-900 hover:bg-gold-400' }}">
                   🏪 KASIR CABANG
                </a>
                <a href="?role=owner_cabang&cabang_id={{ $selectedCabangId }}" 
                   class="px-3.5 py-1.5 rounded-lg transition {{ $activeRole == 'owner_cabang' ? 'bg-cocoa-900 text-gold-300 shadow-md' : 'text-cocoa-900 hover:bg-gold-400' }}">
                   👔 OWNER CABANG
                </a>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-6 space-y-8">

        <!-- SUB-HEADER FOR ROLE EXPLANATION -->
        <div class="yellow-card rounded-2xl p-4 border-l-8 border-cocoa-900 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-display font-black text-cocoa-950">
                    @if($activeRole == 'admin_pusat' || $activeRole == 'koordinator_logistik')
                        👑 Dashboard Admin Pusat — Logistik & Optimasi Rute
                    @elseif($activeRole == 'owner_cabang')
                        👔 Dashboard Owner Cabang — Analitik Eksekutif, AI ROP & Permintaan Belanja
                    @else
                        🏪 Dashboard Kasir Cabang — Operasional Laporan Keuangan & Sisa Bahan
                    @endif
                </h2>
                <p class="text-xs font-semibold text-cocoa-800">
                    @if($activeRole == 'admin_pusat' || $activeRole == 'koordinator_logistik')
                        Wewenang memproses pengajuan permintaan belanja dari seluruh cabang dan menghitung optimasi rute pengiriman (TSP OSRM & Leaflet).
                    @elseif($activeRole == 'owner_cabang')
                        Wewenang membaca laporan keuangan harian & rekap bulanan, status bahan baku, prediksi AI Reorder Point berdasarkan event/libur, dan mengajukan permintaan belanja ke pusat.
                    @else
                        Wewenang menginput laporan keuangan harian (cash/cashless & pengeluaran) dan laporan sisa bahan harian toko cabang.
                    @endif
                </p>
            </div>

            @if($activeRole == 'kasir_cabang' || $activeRole == 'petugas_cabang' || $activeRole == 'owner_cabang')
                <div class="flex items-center gap-2">
                    <label class="text-xs font-black text-cocoa-900">Pilih Cabang:</label>
                    <select onchange="window.location.href='?role={{ $activeRole }}&cabang_id=' + this.value"
                        class="px-3 py-1.5 rounded-xl bg-white border-2 border-gold-500 text-cocoa-950 font-black text-xs focus:outline-none shadow-sm">
                        @foreach($cabangs as $cab)
                            <option value="{{ $cab->id }}" {{ $selectedCabangId == $cab->id ? 'selected' : '' }}>
                                {{ $cab->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        @if($activeRole == 'admin_pusat' || $activeRole == 'koordinator_logistik')
            <!-- ========================================================================= -->
            <!-- DASHBOARD ADMIN PUSAT: PROSES PERMINTAAN BELANJA CABANG & OPTIMASI RUTE  -->
            <!-- ========================================================================= -->

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
        @endif


        @if($activeRole == 'kasir_cabang' || $activeRole == 'petugas_cabang')
            <!-- ================================================================================== -->
            <!-- DASHBOARD KASIR CABANG: INPUT LAPORAN KEUANGAN HARIAN & LAPORAN SISA BAHAN HARIAN -->
            <!-- ================================================================================== -->
            @php
                $myCabang = $cabangs->where('id', $selectedCabangId)->first() ?? $cabangs->first();
                $ai = $myCabang->ai_data ?? [];
                $calc = $ai['kalkulasi'] ?? [];
                $statusCode = $ai['status_code'] ?? 'AMAN';
                $badgeColor = match($statusCode) {
                    'KRITIS' => 'bg-red-500 text-white border-red-600',
                    'WASPADA' => 'bg-amber-500 text-cocoa-950 border-amber-600',
                    default => 'bg-emerald-600 text-white border-emerald-700'
                };
                $rekap = $myCabang->rekap_keuangan ?? null;
                $cashVal = $rekap ? $rekap->pemasukan_cash : 0;
                $cashlessVal = $rekap ? $rekap->pemasukan_cashless : 0;
                $pengeluaranVal = $rekap ? $rekap->pengeluaran_nominal : 0;
                $keteranganVal = $rekap ? $rekap->pengeluaran_keterangan : '';
            @endphp

            <div class="space-y-6">
                <!-- TAB SWITCHER KASIR CABANG -->
                <div class="yellow-card rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-2 border-gold-400">
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1.5 rounded-xl bg-cocoa-900 text-gold-300 font-black text-xs uppercase tracking-wide">
                            <i class="fa-solid fa-cash-register mr-1.5"></i> KASIR CABANG {{ strtoupper($myCabang->nama_cabang) }}
                        </span>
                        <span class="text-xs font-extrabold text-cocoa-900">
                            • Shift Aktif: <strong class="text-amber-700">Pagi - Siang</strong>
                        </span>
                    </div>

                    <div class="flex items-center gap-1.5 bg-gold-300 p-1 rounded-xl border border-gold-500 text-xs font-black">
                        <button type="button" onclick="switchKasirTab('pos')" id="btn-tab-pos"
                            class="px-4 py-2 rounded-lg bg-cocoa-900 text-gold-300 shadow transition flex items-center gap-1.5">
                            <i class="fa-solid fa-cart-shopping"></i> Mesin Kasir (POS)
                        </button>
                        <button type="button" onclick="switchKasirTab('laporan')" id="btn-tab-laporan"
                            class="px-4 py-2 rounded-lg text-cocoa-900 hover:bg-gold-400 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-file-invoice-dollar"></i> Tutup Shift & Laporan Harian
                        </button>
                    </div>
                </div>

                <!-- ========================================================================= -->
                <!-- TAB 1: MESIN KASIR / POS DONAT MENAK (FITUR SEPERTI KASIR PADA UMUMNYA)  -->
                <!-- ========================================================================= -->
                <div id="tab-kasir-pos" class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                        
                        <!-- PANEL KIRI: KATALOG MENU PRODUK DONAT MENAK (7 KOLOM) -->
                        <div class="lg:col-span-7 yellow-card rounded-2xl p-6 space-y-5">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b-2 border-gold-400 pb-3.5">
                                <div>
                                    <h3 class="text-lg font-display font-black text-cocoa-950 flex items-center gap-2">
                                        <i class="fa-solid fa-utensils text-amber-600"></i>
                                        <span>Katalog Menu Donat Menak</span>
                                    </h3>
                                    <p class="text-xs text-cocoa-800 font-medium">Klik menu produk untuk menambahkan ke keranjang kasir</p>
                                </div>
                                
                                <!-- PILIH KATEGORI PILLS -->
                                <div class="flex flex-wrap items-center gap-1.5 text-xs font-black">
                                    <button type="button" onclick="filterPosKategori('semua')" class="pos-kat-btn px-3 py-1 rounded-lg bg-cocoa-900 text-gold-300 border border-gold-500 shadow-sm" data-kat="semua">Semua</button>
                                    <button type="button" onclick="filterPosKategori('klasik')" class="pos-kat-btn px-3 py-1 rounded-lg bg-gold-200 text-cocoa-950 border border-gold-400 hover:bg-gold-300" data-kat="klasik">Klasik</button>
                                    <button type="button" onclick="filterPosKategori('premium')" class="pos-kat-btn px-3 py-1 rounded-lg bg-gold-200 text-cocoa-950 border border-gold-400 hover:bg-gold-300" data-kat="premium">Premium</button>
                                    <button type="button" onclick="filterPosKategori('box')" class="pos-kat-btn px-3 py-1 rounded-lg bg-gold-200 text-cocoa-950 border border-gold-400 hover:bg-gold-300" data-kat="box">Paket Box</button>
                                    <button type="button" onclick="filterPosKategori('minuman')" class="pos-kat-btn px-3 py-1 rounded-lg bg-gold-200 text-cocoa-950 border border-gold-400 hover:bg-gold-300" data-kat="minuman">Minuman</button>
                                </div>
                            </div>

                            <!-- GRID KATALOG MENU -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-h-[520px] overflow-y-auto custom-scrollbar pr-1" id="pos-menu-grid">
                                <!-- Donat Klasik 1 -->
                                <div class="pos-item-card p-3.5 rounded-xl bg-white/90 hover:bg-gold-100 border-2 border-gold-400 transition cursor-pointer flex flex-col justify-between shadow-sm group"
                                     data-kat="klasik" onclick="addToCart('Donat Gula Aren Klasik', 8000, 'klasik', 1)">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center text-sm font-bold border border-amber-300 group-hover:scale-110 transition">
                                                🍩
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-gold-200 text-cocoa-900 text-[10px] font-black">Klasik</span>
                                        </div>
                                        <h4 class="text-xs font-black text-cocoa-950 leading-tight">Donat Gula Aren Klasik</h4>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-gold-300 pt-2">
                                        <span class="text-xs font-extrabold text-amber-800">Rp 8.000</span>
                                        <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 flex items-center justify-center text-xs font-black shadow-sm">+</span>
                                    </div>
                                </div>

                                <!-- Donat Klasik 2 -->
                                <div class="pos-item-card p-3.5 rounded-xl bg-white/90 hover:bg-gold-100 border-2 border-gold-400 transition cursor-pointer flex flex-col justify-between shadow-sm group"
                                     data-kat="klasik" onclick="addToCart('Donat Gula Halus Tradisional', 8000, 'klasik', 1)">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center text-sm font-bold border border-amber-300 group-hover:scale-110 transition">
                                                🍩
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-gold-200 text-cocoa-900 text-[10px] font-black">Klasik</span>
                                        </div>
                                        <h4 class="text-xs font-black text-cocoa-950 leading-tight">Donat Gula Halus Tradisional</h4>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-gold-300 pt-2">
                                        <span class="text-xs font-extrabold text-amber-800">Rp 8.000</span>
                                        <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 flex items-center justify-center text-xs font-black shadow-sm">+</span>
                                    </div>
                                </div>

                                <!-- Donat Premium 1 -->
                                <div class="pos-item-card p-3.5 rounded-xl bg-white/90 hover:bg-gold-100 border-2 border-gold-400 transition cursor-pointer flex flex-col justify-between shadow-sm group"
                                     data-kat="premium" onclick="addToCart('Donat Cokelat Belgia Lumer', 10000, 'premium', 1)">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-8 h-8 rounded-lg bg-amber-200 text-amber-900 flex items-center justify-center text-sm font-bold border border-amber-400 group-hover:scale-110 transition">
                                                🍫
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-amber-300 text-cocoa-950 text-[10px] font-black">Premium</span>
                                        </div>
                                        <h4 class="text-xs font-black text-cocoa-950 leading-tight">Donat Cokelat Belgia Lumer</h4>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-gold-300 pt-2">
                                        <span class="text-xs font-extrabold text-amber-800">Rp 10.000</span>
                                        <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 flex items-center justify-center text-xs font-black shadow-sm">+</span>
                                    </div>
                                </div>

                                <!-- Donat Premium 2 -->
                                <div class="pos-item-card p-3.5 rounded-xl bg-white/90 hover:bg-gold-100 border-2 border-gold-400 transition cursor-pointer flex flex-col justify-between shadow-sm group"
                                     data-kat="premium" onclick="addToCart('Donat Keju Cheddar Susu', 10000, 'premium', 1)">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-8 h-8 rounded-lg bg-amber-200 text-amber-900 flex items-center justify-center text-sm font-bold border border-amber-400 group-hover:scale-110 transition">
                                                🧀
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-amber-300 text-cocoa-950 text-[10px] font-black">Premium</span>
                                        </div>
                                        <h4 class="text-xs font-black text-cocoa-950 leading-tight">Donat Keju Cheddar Susu</h4>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-gold-300 pt-2">
                                        <span class="text-xs font-extrabold text-amber-800">Rp 10.000</span>
                                        <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 flex items-center justify-center text-xs font-black shadow-sm">+</span>
                                    </div>
                                </div>

                                <!-- Donat Premium 3 -->
                                <div class="pos-item-card p-3.5 rounded-xl bg-white/90 hover:bg-gold-100 border-2 border-gold-400 transition cursor-pointer flex flex-col justify-between shadow-sm group"
                                     data-kat="premium" onclick="addToCart('Donat Matcha Almond Crunchy', 11000, 'premium', 1)">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-sm font-bold border border-emerald-300 group-hover:scale-110 transition">
                                                🍵
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-amber-300 text-cocoa-950 text-[10px] font-black">Premium</span>
                                        </div>
                                        <h4 class="text-xs font-black text-cocoa-950 leading-tight">Donat Matcha Almond Crunchy</h4>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-gold-300 pt-2">
                                        <span class="text-xs font-extrabold text-amber-800">Rp 11.000</span>
                                        <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 flex items-center justify-center text-xs font-black shadow-sm">+</span>
                                    </div>
                                </div>

                                <!-- Donat Premium 4 -->
                                <div class="pos-item-card p-3.5 rounded-xl bg-white/90 hover:bg-gold-100 border-2 border-gold-400 transition cursor-pointer flex flex-col justify-between shadow-sm group"
                                     data-kat="premium" onclick="addToCart('Donat Red Velvet Cream Cheese', 12000, 'premium', 1)">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-8 h-8 rounded-lg bg-red-100 text-red-800 flex items-center justify-center text-sm font-bold border border-red-300 group-hover:scale-110 transition">
                                                🍰
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-amber-300 text-cocoa-950 text-[10px] font-black">Premium</span>
                                        </div>
                                        <h4 class="text-xs font-black text-cocoa-950 leading-tight">Donat Red Velvet Cream Cheese</h4>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-gold-300 pt-2">
                                        <span class="text-xs font-extrabold text-amber-800">Rp 12.000</span>
                                        <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 flex items-center justify-center text-xs font-black shadow-sm">+</span>
                                    </div>
                                </div>

                                <!-- Donat Premium 5 -->
                                <div class="pos-item-card p-3.5 rounded-xl bg-white/90 hover:bg-gold-100 border-2 border-gold-400 transition cursor-pointer flex flex-col justify-between shadow-sm group"
                                     data-kat="premium" onclick="addToCart('Donat Tiramisu Lotus Biscoff', 12000, 'premium', 1)">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-8 h-8 rounded-lg bg-amber-200 text-amber-900 flex items-center justify-center text-sm font-bold border border-amber-400 group-hover:scale-110 transition">
                                                🍪
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-amber-300 text-cocoa-950 text-[10px] font-black">Premium</span>
                                        </div>
                                        <h4 class="text-xs font-black text-cocoa-950 leading-tight">Donat Tiramisu Lotus Biscoff</h4>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-gold-300 pt-2">
                                        <span class="text-xs font-extrabold text-amber-800">Rp 12.000</span>
                                        <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 flex items-center justify-center text-xs font-black shadow-sm">+</span>
                                    </div>
                                </div>

                                <!-- Paket Box 1 -->
                                <div class="pos-item-card p-3.5 rounded-xl bg-white/90 hover:bg-gold-100 border-2 border-gold-400 transition cursor-pointer flex flex-col justify-between shadow-sm group"
                                     data-kat="box" onclick="addToCart('Paket Box Hemat 6 Pcs (Campur)', 55000, 'box', 6)">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-8 h-8 rounded-lg bg-purple-100 text-purple-800 flex items-center justify-center text-sm font-bold border border-purple-300 group-hover:scale-110 transition">
                                                📦
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-purple-200 text-purple-900 text-[10px] font-black">Box 6 Pcs</span>
                                        </div>
                                        <h4 class="text-xs font-black text-cocoa-950 leading-tight">Paket Box Hemat 6 Pcs (Campur)</h4>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-gold-300 pt-2">
                                        <span class="text-xs font-extrabold text-amber-800">Rp 55.000</span>
                                        <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 flex items-center justify-center text-xs font-black shadow-sm">+</span>
                                    </div>
                                </div>

                                <!-- Paket Box 2 -->
                                <div class="pos-item-card p-3.5 rounded-xl bg-white/90 hover:bg-gold-100 border-2 border-gold-400 transition cursor-pointer flex flex-col justify-between shadow-sm group"
                                     data-kat="box" onclick="addToCart('Paket Party Box 12 Pcs (Spesial)', 105000, 'box', 12)">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-8 h-8 rounded-lg bg-purple-100 text-purple-800 flex items-center justify-center text-sm font-bold border border-purple-300 group-hover:scale-110 transition">
                                                🎁
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-purple-200 text-purple-900 text-[10px] font-black">Box 12 Pcs</span>
                                        </div>
                                        <h4 class="text-xs font-black text-cocoa-950 leading-tight">Paket Party Box 12 Pcs (Spesial)</h4>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-gold-300 pt-2">
                                        <span class="text-xs font-extrabold text-amber-800">Rp 105.000</span>
                                        <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 flex items-center justify-center text-xs font-black shadow-sm">+</span>
                                    </div>
                                </div>

                                <!-- Minuman 1 -->
                                <div class="pos-item-card p-3.5 rounded-xl bg-white/90 hover:bg-gold-100 border-2 border-gold-400 transition cursor-pointer flex flex-col justify-between shadow-sm group"
                                     data-kat="minuman" onclick="addToCart('Kopi Susu Gula Aren Menak', 15000, 'minuman', 0)">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-900 flex items-center justify-center text-sm font-bold border border-amber-300 group-hover:scale-110 transition">
                                                ☕
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-900 text-[10px] font-black">Minuman</span>
                                        </div>
                                        <h4 class="text-xs font-black text-cocoa-950 leading-tight">Kopi Susu Gula Aren Menak</h4>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-gold-300 pt-2">
                                        <span class="text-xs font-extrabold text-amber-800">Rp 15.000</span>
                                        <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 flex items-center justify-center text-xs font-black shadow-sm">+</span>
                                    </div>
                                </div>

                                <!-- Minuman 2 -->
                                <div class="pos-item-card p-3.5 rounded-xl bg-white/90 hover:bg-gold-100 border-2 border-gold-400 transition cursor-pointer flex flex-col justify-between shadow-sm group"
                                     data-kat="minuman" onclick="addToCart('Es Teh Lychee Segar', 12000, 'minuman', 0)">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-8 h-8 rounded-lg bg-red-100 text-red-800 flex items-center justify-center text-sm font-bold border border-red-300 group-hover:scale-110 transition">
                                                🍹
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-900 text-[10px] font-black">Minuman</span>
                                        </div>
                                        <h4 class="text-xs font-black text-cocoa-950 leading-tight">Es Teh Lychee Segar</h4>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-gold-300 pt-2">
                                        <span class="text-xs font-extrabold text-amber-800">Rp 12.000</span>
                                        <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 flex items-center justify-center text-xs font-black shadow-sm">+</span>
                                    </div>
                                </div>

                                <!-- Minuman 3 -->
                                <div class="pos-item-card p-3.5 rounded-xl bg-white/90 hover:bg-gold-100 border-2 border-gold-400 transition cursor-pointer flex flex-col justify-between shadow-sm group"
                                     data-kat="minuman" onclick="addToCart('Cokelat Dingin Menak Spesial', 16000, 'minuman', 0)">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-8 h-8 rounded-lg bg-amber-200 text-amber-950 flex items-center justify-center text-sm font-bold border border-amber-400 group-hover:scale-110 transition">
                                                🧋
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-900 text-[10px] font-black">Minuman</span>
                                        </div>
                                        <h4 class="text-xs font-black text-cocoa-950 leading-tight">Cokelat Dingin Menak Spesial</h4>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-gold-300 pt-2">
                                        <span class="text-xs font-extrabold text-amber-800">Rp 16.000</span>
                                        <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 flex items-center justify-center text-xs font-black shadow-sm">+</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PANEL KANAN: KERANJANG BELANJA & PEMBAYARAN KASIR (5 KOLOM) -->
                        <div class="lg:col-span-5 yellow-card rounded-2xl p-6 space-y-5 sticky top-4">
                            <div class="flex items-center justify-between border-b-2 border-gold-400 pb-3">
                                <div>
                                    <h3 class="text-base font-display font-black text-cocoa-950 flex items-center gap-2">
                                        <i class="fa-solid fa-cart-shopping text-amber-600"></i>
                                        <span>Keranjang Pesanan</span>
                                    </h3>
                                    <span class="text-[10px] font-mono font-bold text-cocoa-800" id="pos-invoice-num">INV/DM-{{ $myCabang->id }}/{{ date('Ymd') }}-01</span>
                                </div>
                                <button type="button" onclick="clearPosCart()"
                                    class="px-2.5 py-1 rounded-lg bg-red-100 hover:bg-red-200 text-red-700 text-[11px] font-black border border-red-300 transition">
                                    <i class="fa-solid fa-trash-can mr-1"></i> Kosongkan
                                </button>
                            </div>

                            <!-- DAFTAR ITEM KERANJANG -->
                            <div id="pos-cart-container" class="min-h-[160px] max-h-[220px] overflow-y-auto custom-scrollbar space-y-2.5 pr-1">
                                <!-- STATE KOSONG DEFAULT -->
                                <div id="pos-empty-state" class="py-10 text-center space-y-2">
                                    <div class="w-12 h-12 mx-auto rounded-full bg-gold-200 flex items-center justify-center text-amber-700 text-xl">
                                        <i class="fa-solid fa-basket-shopping"></i>
                                    </div>
                                    <p class="text-xs font-bold text-cocoa-800">Belum ada item pesanan di keranjang.</p>
                                    <p class="text-[11px] text-cocoa-700">Klik menu di sebelah kiri untuk memilih produk.</p>
                                </div>
                            </div>

                            <!-- KALKULASI TAGIHAN -->
                            <div class="border-t-2 border-gold-400 pt-3 space-y-2 text-xs font-bold">
                                <div class="flex justify-between items-center text-cocoa-800">
                                    <span>Subtotal Pesanan:</span>
                                    <span class="font-mono font-extrabold text-cocoa-950" id="pos-subtotal-txt">Rp 0</span>
                                </div>
                                <div class="flex justify-between items-center text-cocoa-800">
                                    <span>Diskon / Promo Cabang:</span>
                                    <span class="font-mono font-extrabold text-emerald-700" id="pos-diskon-txt">Rp 0</span>
                                </div>
                                <div class="flex justify-between items-center bg-cocoa-900 p-3 rounded-xl text-gold-300 border border-cocoa-950 shadow-sm">
                                    <span class="text-sm font-black uppercase tracking-wide">Total Tagihan:</span>
                                    <span class="text-lg font-display font-black text-white" id="pos-total-txt">Rp 0</span>
                                </div>
                            </div>

                            <!-- METODE PEMBAYARAN & UANG BAYAR -->
                            <div class="space-y-3 pt-1">
                                <label class="block text-xs font-black text-cocoa-950">Pilih Metode Pembayaran:</label>
                                <div class="grid grid-cols-2 gap-2 text-xs font-black">
                                    <button type="button" onclick="setMetodeBayar('Tunai (Cash)')" id="btn-metode-cash"
                                        class="py-2.5 px-3 rounded-xl bg-cocoa-900 text-gold-300 border-2 border-gold-500 shadow transition flex items-center justify-center gap-1.5">
                                        <i class="fa-solid fa-money-bill-1-wave"></i> Tunai (Cash)
                                    </button>
                                    <button type="button" onclick="setMetodeBayar('QRIS / Non-Tunai')" id="btn-metode-qris"
                                        class="py-2.5 px-3 rounded-xl bg-gold-200 text-cocoa-950 border-2 border-gold-400 hover:bg-gold-300 transition flex items-center justify-center gap-1.5">
                                        <i class="fa-solid fa-qrcode"></i> QRIS / Cashless
                                    </button>
                                </div>

                                <!-- INPUT BAYAR TUNAI -->
                                <div id="box-pembayaran-cash" class="bg-gold-200/90 p-3.5 rounded-xl border-2 border-gold-400 space-y-2.5">
                                    <div>
                                        <label class="block text-[11px] font-black text-cocoa-950 mb-1">Uang Diterima dari Pelanggan (Rp):</label>
                                        <input type="number" id="pos-uang-bayar" min="0" step="1000" placeholder="0" oninput="hitungKembalianPos()"
                                            class="w-full px-3 py-2 rounded-xl bg-white border-2 border-gold-400 text-cocoa-950 font-black text-sm focus:border-amber-600 focus:outline-none">
                                    </div>

                                    <!-- SHORTCUT UANG PAS / PECAHAN -->
                                    <div class="flex flex-wrap gap-1.5 text-[11px] font-black">
                                        <button type="button" onclick="setUangBayar('pas')" class="px-2.5 py-1 rounded-lg bg-white hover:bg-gold-100 text-cocoa-950 border border-gold-400">Uang Pas</button>
                                        <button type="button" onclick="setUangBayar(20000)" class="px-2.5 py-1 rounded-lg bg-white hover:bg-gold-100 text-cocoa-950 border border-gold-400">20.000</button>
                                        <button type="button" onclick="setUangBayar(50000)" class="px-2.5 py-1 rounded-lg bg-white hover:bg-gold-100 text-cocoa-950 border border-gold-400">50.000</button>
                                        <button type="button" onclick="setUangBayar(100000)" class="px-2.5 py-1 rounded-lg bg-white hover:bg-gold-100 text-cocoa-950 border border-gold-400">100.000</button>
                                    </div>

                                    <div class="flex justify-between items-center pt-1 border-t border-gold-400 text-xs font-black">
                                        <span class="text-cocoa-900">Kembalian:</span>
                                        <span class="font-mono text-sm text-emerald-800" id="pos-kembalian-txt">Rp 0</span>
                                    </div>
                                </div>

                                <!-- INFO BOX QRIS (HIDDEN DEFAULT) -->
                                <div id="box-pembayaran-qris" class="hidden bg-white p-4 rounded-xl border-2 border-gold-400 text-center space-y-2">
                                    <div class="w-24 h-24 mx-auto bg-gold-100 rounded-xl border-2 border-cocoa-900 flex items-center justify-center text-4xl text-cocoa-900">
                                        <i class="fa-solid fa-qrcode"></i>
                                    </div>
                                    <p class="text-xs font-black text-cocoa-950">QRIS Resmi Donat Menak — {{ $myCabang->nama_cabang }}</p>
                                    <p class="text-[10px] text-cocoa-800 font-semibold">Tunjukkan kepada pelanggan untuk scan via GoPay, OVO, Dana, BCA, Mandiri</p>
                                </div>
                            </div>

                            <!-- TOMBOL PROSES PEMBAYARAN POS -->
                            <button type="button" onclick="prosesTransaksiPos({{ $myCabang->id }}, '{{ $myCabang->nama_cabang }}')"
                                class="w-full py-4 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-sm tracking-wide shadow-lg transition flex items-center justify-center gap-2 border-2 border-emerald-500">
                                <i class="fa-solid fa-print text-base"></i> BAYAR & CETAK STRUK KASIR
                            </button>
                        </div>
                    </div>

                    <!-- LOG RIWAYAT TRANSAKSI KASIR SESI HARI INI -->
                    <div class="yellow-card rounded-2xl p-6 space-y-4">
                        <div class="flex items-center justify-between border-b-2 border-gold-400 pb-3">
                            <h4 class="text-base font-display font-black text-cocoa-950 flex items-center gap-2">
                                <i class="fa-solid fa-receipt text-amber-600"></i>
                                <span>Riwayat Transaksi Sesi Kasir Hari Ini</span>
                            </h4>
                            <span class="text-xs font-black text-cocoa-800" id="pos-log-count">0 Transaksi Selesai</span>
                        </div>
                        <div class="overflow-x-auto max-h-56 overflow-y-auto custom-scrollbar">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b-2 border-gold-400 text-xs font-black uppercase text-cocoa-900 bg-gold-300/60 sticky top-0">
                                        <th class="py-2.5 px-4 rounded-l-xl">No. Struk</th>
                                        <th class="py-2.5 px-4">Jam</th>
                                        <th class="py-2.5 px-4">Detail Item</th>
                                        <th class="py-2.5 px-4">Metode Bayar</th>
                                        <th class="py-2.5 px-4 text-right rounded-r-xl">Total Bayar</th>
                                    </tr>
                                </thead>
                                <tbody id="pos-log-tbody" class="divide-y divide-gold-300 text-xs font-semibold text-cocoa-950">
                                    <tr id="pos-log-empty">
                                        <td colspan="5" class="py-6 text-center text-cocoa-700 font-bold">Belum ada transaksi di sesi shift ini.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ========================================================================= -->
                <!-- TAB 2: TUTUP SHIFT & LAPORAN HARIAN (REKAP KEUANGAN & STOK PREMIX)        -->
                <!-- ========================================================================= -->
                <div id="tab-kasir-laporan" class="hidden space-y-8">
                <!-- BAGIAN 1: REKAP KEUANGAN HARIAN CABANG (PEMASUKAN & PENGELUARAN) -->
                <div class="yellow-card rounded-2xl p-6 space-y-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between border-b-2 border-gold-400 pb-4 gap-2">
                        <div>
                            <h3 class="text-lg font-display font-black text-cocoa-950 flex items-center gap-2">
                                <i class="fa-solid fa-wallet text-amber-600"></i>
                                <span>Form Rekap Keuangan Harian — {{ $myCabang->nama_cabang }}</span>
                            </h3>
                            <p class="text-xs text-cocoa-800 font-medium">Catat total pemasukan (Cash & Cashless) serta pengeluaran operasional toko hari ini</p>
                        </div>
                        <span class="px-3.5 py-1.5 rounded-full bg-cocoa-900 text-gold-300 text-xs font-black border border-gold-400">
                            <i class="fa-regular fa-calendar-check mr-1"></i> {{ date('d M Y') }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                        <!-- FORM REKAP KEUANGAN (8 KOLOM) -->
                        <form id="formRekapKeuangan" onsubmit="submitRekapKeuangan(event, {{ $myCabang->id }})" class="lg:col-span-7 space-y-5">
                            <!-- PEMASUKAN SECTION -->
                            <div class="bg-gold-200/80 p-4 rounded-xl border-2 border-gold-400 space-y-3">
                                <span class="text-xs font-black uppercase text-emerald-800 flex items-center gap-1.5 border-b border-gold-400 pb-2">
                                    <i class="fa-solid fa-circle-arrow-down"></i> Pemasukan Penjualan Cabang Hari Ini
                                </span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-black text-cocoa-950 mb-1">Pemasukan Cash (Tunai) — Rp:</label>
                                        <input type="number" id="rekap_cash" required min="0" step="1000" placeholder="0"
                                            value="{{ $cashVal }}" oninput="hitungLiveKeuangan()"
                                            class="w-full px-4 py-2.5 rounded-xl bg-white border-2 border-gold-400 text-cocoa-950 font-bold text-sm focus:border-amber-600 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-cocoa-950 mb-1">Pemasukan Cashless (QRIS/Transfer) — Rp:</label>
                                        <input type="number" id="rekap_cashless" required min="0" step="1000" placeholder="0"
                                            value="{{ $cashlessVal }}" oninput="hitungLiveKeuangan()"
                                            class="w-full px-4 py-2.5 rounded-xl bg-white border-2 border-gold-400 text-cocoa-950 font-bold text-sm focus:border-amber-600 focus:outline-none">
                                    </div>
                                </div>
                            </div>

                            <!-- PENGELUARAN SECTION -->
                            <div class="bg-gold-200/80 p-4 rounded-xl border-2 border-gold-400 space-y-3">
                                <span class="text-xs font-black uppercase text-red-700 flex items-center gap-1.5 border-b border-gold-400 pb-2">
                                    <i class="fa-solid fa-circle-arrow-up"></i> Pengeluaran Operasional Harian
                                </span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-black text-cocoa-950 mb-1">Nominal Pengeluaran — Rp:</label>
                                        <input type="number" id="rekap_pengeluaran" required min="0" step="1000" placeholder="0"
                                            value="{{ $pengeluaranVal }}" oninput="hitungLiveKeuangan()"
                                            class="w-full px-4 py-2.5 rounded-xl bg-white border-2 border-gold-400 text-cocoa-950 font-bold text-sm focus:border-amber-600 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-cocoa-950 mb-1">Keterangan / Catatan Pengeluaran:</label>
                                        <input type="text" id="rekap_keterangan" placeholder="Contoh: Beli es batu, plastik kemasan, & kebersihan"
                                            value="{{ $keteranganVal }}"
                                            class="w-full px-4 py-2.5 rounded-xl bg-white border-2 border-gold-400 text-cocoa-950 font-bold text-sm focus:border-amber-600 focus:outline-none">
                                    </div>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full py-4 px-6 rounded-xl bg-cocoa-900 hover:bg-cocoa-950 text-gold-300 font-black text-sm tracking-wide shadow-lg transition flex items-center justify-center gap-2.5 border-2 border-gold-400">
                                <i class="fa-solid fa-floppy-disk text-gold-400 text-base"></i>
                                <span>SIMPAN REKAP KEUANGAN HARIAN CABANG</span>
                            </button>
                        </form>

                        <!-- RINGKASAN SALDO LIVE (5 KOLOM) -->
                        <div class="lg:col-span-5 bg-cocoa-900 text-gold-300 p-6 rounded-2xl border-2 border-gold-400 space-y-5 shadow-xl">
                            <div class="border-b border-cocoa-700 pb-3">
                                <span class="text-xs font-black uppercase text-gold-400 block">📊 Kalkulasi Kas Bersih Harian</span>
                                <span class="text-[11px] text-white/70">Perhitungan real-time pemasukan vs pengeluaran</span>
                            </div>

                            <div class="space-y-3.5 text-xs font-bold">
                                <div class="flex justify-between items-center bg-cocoa-950 p-3 rounded-xl border border-cocoa-800">
                                    <span class="text-white">Pemasukan Tunai (Cash):</span>
                                    <span class="font-mono text-emerald-400 font-black text-sm" id="live-cash">Rp 0</span>
                                </div>
                                <div class="flex justify-between items-center bg-cocoa-950 p-3 rounded-xl border border-cocoa-800">
                                    <span class="text-white">Pemasukan Non-Tunai (Cashless):</span>
                                    <span class="font-mono text-emerald-400 font-black text-sm" id="live-cashless">Rp 0</span>
                                </div>
                                <div class="flex justify-between items-center bg-cocoa-950 p-3 rounded-xl border border-cocoa-800">
                                    <span class="text-red-300">Pengeluaran Operasional:</span>
                                    <span class="font-mono text-red-400 font-black text-sm" id="live-pengeluaran">Rp 0</span>
                                </div>
                            </div>

                            <div class="pt-3 border-t-2 border-gold-400/40 space-y-1">
                                <span class="block text-[11px] uppercase font-black text-gold-400">Estimasi Kas Bersih Cabang Hari Ini:</span>
                                <span class="block text-2xl font-display font-black text-white tracking-tight" id="live-net-saldo">Rp 0</span>
                                <span class="block text-[10px] text-emerald-400 font-semibold" id="live-saldo-badge">✨ Siap disetorkan ke pusat</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BAGIAN 2: INPUT PENJUALAN DONAT & ANALISIS ROP STOK PREMIX -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- FORM INPUT PENJUALAN HARIAN -->
                    <div class="yellow-card rounded-2xl p-6 space-y-4">
                        <div class="border-b-2 border-gold-400 pb-3">
                            <h3 class="text-base font-display font-black text-cocoa-950 flex items-center gap-2">
                                <i class="fa-solid fa-pen-to-square text-amber-600"></i>
                                <span>Input Stok & Penjualan Donat</span>
                            </h3>
                            <p class="text-xs text-cocoa-800 font-medium">Catat penjualan donat hari ini untuk dianalisis AI ROP</p>
                        </div>

                        <form id="formInputPenjualan" onsubmit="submitLaporanCabang(event, {{ $myCabang->id }})" class="space-y-4">
                            <div>
                                <label class="block text-xs font-black text-cocoa-950 mb-1">Total Penjualan Donat Hari Ini (Pcs):</label>
                                <input type="number" id="input_donat" required min="0" placeholder="Contoh: 320"
                                    class="w-full px-4 py-2.5 rounded-xl bg-white border-2 border-gold-400 text-cocoa-950 font-bold text-sm focus:border-amber-600 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-black text-cocoa-950 mb-1">Sisa Stok Premix di Toko Sekarang (Kg):</label>
                                <input type="number" id="input_stok" required min="0" step="0.5" placeholder="Contoh: 45"
                                    value="{{ $calc['sisa_stok_saat_ini_kg'] ?? 45 }}"
                                    class="w-full px-4 py-2.5 rounded-xl bg-white border-2 border-gold-400 text-cocoa-950 font-bold text-sm focus:border-amber-600 focus:outline-none">
                            </div>

                            <button type="submit"
                                class="w-full py-3.5 px-4 rounded-xl bg-cocoa-900 hover:bg-cocoa-950 text-gold-300 font-black text-xs tracking-wide shadow-lg transition flex items-center justify-center gap-2 border-2 border-gold-400">
                                <i class="fa-solid fa-cloud-arrow-up text-sm"></i> Simpan & Perbarui ROP AI
                            </button>
                        </form>

                        <div class="bg-gold-200 p-3.5 rounded-xl border-2 border-gold-400 text-xs space-y-1.5">
                            <div class="flex justify-between font-bold text-cocoa-900">
                                <span>Batas Pesan Ulang (ROP):</span>
                                <span class="text-cocoa-950 font-black">{{ $calc['reorder_point_kg'] ?? 20 }} Kg</span>
                            </div>
                            <div class="flex justify-between font-bold text-cocoa-900">
                                <span>Saran Pemesanan (EOQ):</span>
                                <span class="text-amber-700 font-black">{{ $calc['saran_order_kg'] ?? 50 }} Kg</span>
                            </div>
                        </div>
                    </div>

                    <!-- CABANG INVENTORY CARD & QUICK CHART -->
                    <div class="lg:col-span-2 yellow-card rounded-2xl p-6 space-y-5">
                        <div class="flex items-center justify-between border-b-2 border-gold-400 pb-3">
                            <div>
                                <h3 class="text-base font-display font-black text-cocoa-950">{{ $myCabang->nama_cabang }} - Status Logistik</h3>
                                <p class="text-xs text-cocoa-800 font-medium">{{ $myCabang->alamat }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-black border {{ $badgeColor }}">
                                Status AI: {{ $statusCode }}
                            </span>
                        </div>

                        @php
                            $stokSaatIni = $calc['sisa_stok_saat_ini_kg'] ?? 0;
                            $ropVal = $calc['reorder_point_kg'] ?? 20;
                            $maxBar = max($stokSaatIni, $ropVal * 2, 100);
                            $pctStok = min(100, round(($stokSaatIni / $maxBar) * 100));
                            $pctRop = min(100, round(($ropVal / $maxBar) * 100));
                            $barColor = $statusCode == 'KRITIS' ? 'bg-red-500' : ($statusCode == 'WASPADA' ? 'bg-amber-500' : 'bg-emerald-600');
                        @endphp
                        <div class="bg-gold-200 p-4 rounded-xl border-2 border-gold-400">
                            <div class="flex justify-between text-xs mb-2 font-black text-cocoa-950">
                                <span>Sisa Stok Toko: {{ $stokSaatIni }} Kg</span>
                                <span class="text-amber-800">Garis ROP: {{ $ropVal }} Kg</span>
                            </div>
                            <div class="w-full bg-white h-3 rounded-full relative overflow-hidden border border-gold-400">
                                <div class="h-full {{ $barColor }} transition-all duration-1000 rounded-full" style="width: {{ $pctStok }}%;"></div>
                                <div class="absolute top-0 bottom-0 w-0.5 bg-cocoa-950 shadow-[0_0_8px_#000]" style="left: {{ $pctRop }}%;"></div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center bg-gold-200 p-4 rounded-xl border-2 border-gold-400">
                            <div>
                                <span class="text-xs font-black text-cocoa-950 block">Butuh Pengiriman Tambahan dari Dapur Pusat?</span>
                                <span class="text-[11px] text-cocoa-800">Ajukan permintaan logistik langsung ke Dapur Lodaya</span>
                            </div>
                            <button onclick="alert('Permintaan pengiriman {{ $calc['saran_order_kg'] ?? 50 }} Kg premix untuk {{ $myCabang->nama_cabang }} telah diteruskan ke Petugas Pusat Lodaya!')"
                                class="px-4 py-2.5 rounded-xl bg-cocoa-900 text-gold-300 font-extrabold text-xs shadow hover:opacity-95 transition border border-gold-400">
                                <i class="fa-solid fa-paper-plane mr-1"></i> Ajukan Kiriman
                            </button>
                        </div>

                        <button onclick="openSimulasiModal({{ $myCabang->id }}, '{{ $myCabang->nama_cabang }}', {{ json_encode($ai) }})"
                            class="w-full py-3.5 px-4 rounded-xl bg-white hover:bg-gold-100 border-2 border-gold-400 text-cocoa-950 font-black text-xs transition flex items-center justify-center gap-2 shadow-sm">
                            <i class="fa-solid fa-chart-line text-amber-600"></i> Buka Analisis Tren Penjualan 30 Hari & Simulasi Lead Time
                        </button>
                    </div>
                </div>
                </div> <!-- END #tab-kasir-laporan -->

                <!-- MODAL STRUK TRANSAKSI POS (THERMAL RECEIPT STYLE) -->
                <div id="modal-struk-pos" class="fixed inset-0 bg-cocoa-950/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
                    <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl border-4 border-gold-400 text-cocoa-950 space-y-4">
                        <!-- HEADER STRUK THERMAL -->
                        <div class="text-center border-b-2 border-dashed border-cocoa-400 pb-3 space-y-1">
                            <h3 class="text-base font-display font-black tracking-wider uppercase">DONAT MENAK</h3>
                            <p class="text-[11px] font-bold text-cocoa-800">Cabang <span id="struk-cabang">{{ $myCabang->nama_cabang }}</span></p>
                            <p class="text-[10px] text-cocoa-700">Jl. Raya Bandung No. 128 • Telp (022) 876543</p>
                        </div>

                        <!-- META STRUK -->
                        <div class="text-[11px] font-mono border-b border-dashed border-cocoa-400 pb-2 space-y-1">
                            <div class="flex justify-between"><span>No. Struk:</span><strong id="struk-inv">INV-01</strong></div>
                            <div class="flex justify-between"><span>Tanggal:</span><span id="struk-tgl">{{ date('d/m/Y H:i') }}</span></div>
                            <div class="flex justify-between"><span>Kasir:</span><span>Kasir Cabang</span></div>
                            <div class="flex justify-between"><span>Metode:</span><strong id="struk-metode">Tunai</strong></div>
                        </div>

                        <!-- ITEM LIST STRUK -->
                        <div id="struk-item-list" class="text-[11px] font-mono space-y-1.5 border-b-2 border-dashed border-cocoa-400 pb-3 max-h-40 overflow-y-auto">
                            <!-- Populated dynamically -->
                        </div>

                        <!-- TOTALS STRUK -->
                        <div class="text-xs font-mono space-y-1 border-b-2 border-dashed border-cocoa-400 pb-3">
                            <div class="flex justify-between"><span>Subtotal:</span><span id="struk-subtotal">Rp 0</span></div>
                            <div class="flex justify-between"><span>Diskon:</span><span id="struk-diskon">Rp 0</span></div>
                            <div class="flex justify-between font-black text-sm"><span>TOTAL:</span><span id="struk-total">Rp 0</span></div>
                            <div class="flex justify-between text-cocoa-800"><span>Bayar:</span><span id="struk-bayar">Rp 0</span></div>
                            <div class="flex justify-between text-cocoa-800"><span>Kembalian:</span><span id="struk-kembalian">Rp 0</span></div>
                        </div>

                        <!-- FOOTER STRUK -->
                        <div class="text-center text-[10px] text-cocoa-700 italic space-y-1">
                            <p>*** TERIMA KASIH ATAS KUNJUNGAN ANDA ***</p>
                            <p>Donat empuk kebanggaan Bandung • @donatmenak</p>
                        </div>

                        <!-- TOMBOL AKSI MODAL STRUK -->
                        <div class="flex gap-2 pt-2">
                            <button type="button" onclick="window.print()"
                                class="flex-1 py-2.5 px-3 rounded-xl bg-cocoa-900 hover:bg-cocoa-950 text-gold-300 font-black text-xs transition flex items-center justify-center gap-1.5 border border-gold-400">
                                <i class="fa-solid fa-print"></i> Cetak Struk
                            </button>
                            <button type="button" onclick="tutupModalStruk()"
                                class="flex-1 py-2.5 px-3 rounded-xl bg-gold-200 hover:bg-gold-300 text-cocoa-950 font-black text-xs transition">
                                Selesai / Transaksi Baru
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        @if($activeRole == 'owner_cabang')
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

                <!-- 2. READ LAPORAN BAHAN BAKU & 3. AI PREDIKSI REORDER POINT MUSIMAN -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- READ LAPORAN BAHAN BAKU CABANG (5 KOLOM) -->
                    <div class="lg:col-span-5 yellow-card rounded-2xl p-6 space-y-5">
                        <div class="border-b-2 border-gold-400 pb-3">
                            <h3 class="text-base font-display font-black text-cocoa-950 flex items-center gap-2">
                                <i class="fa-solid fa-boxes-stacked text-amber-600"></i>
                                <span>Read Laporan Bahan Baku Cabang</span>
                            </h3>
                            <p class="text-xs text-cocoa-800 font-medium">Status stok di {{ $myCabang->nama_cabang }} & pasokan pusat</p>
                        </div>

                        <div class="space-y-3">
                            <div class="p-4 rounded-xl bg-gold-200/90 border-2 border-gold-400 space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-black text-cocoa-900">Stok Premix Tepung di Toko:</span>
                                    <span class="text-base font-black text-cocoa-950">{{ $calc['sisa_stok_saat_ini_kg'] ?? $myCabang->sisa_stok_terkini }} Kg</span>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-cocoa-800">Pemakaian Harian Rata-rata:</span>
                                    <span class="font-bold text-amber-800">{{ $calc['rata_rata_premix_harian_kg'] ?? 15 }} Kg/hari</span>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-cocoa-800">Ketahanan Stok Saat Ini:</span>
                                    <span class="font-black text-emerald-800">~{{ round(($calc['sisa_stok_saat_ini_kg'] ?? 45) / max($calc['rata_rata_premix_harian_kg'] ?? 15, 1)) }} Hari</span>
                                </div>
                            </div>

                            <h4 class="text-xs font-black uppercase text-cocoa-900 pt-1">Daftar Bahan Baku Utama (Dapur Pusat Lodaya):</h4>
                            <div class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar pr-1">
                                @foreach($bahanBakus as $bb)
                                    <div class="p-2.5 rounded-xl bg-white/80 border border-gold-400 flex items-center justify-between text-xs">
                                        <span class="font-bold text-cocoa-950">{{ $bb->nama_bahan }}</span>
                                        <span class="font-black text-amber-800">{{ number_format($bb->stok_pusat) }} {{ $bb->satuan }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- AI PREDIKSI REORDER POINT BERDASARKAN TRANSAKSI HARIAN & EVENT/MUSIM (7 KOLOM) -->
                    <div class="lg:col-span-7 yellow-card rounded-2xl p-6 space-y-5">
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
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs font-black">
                                <button type="button" onclick="ubahFaktorMusim(1.0, 'Hari Biasa (Normal)')" id="btn-musim-normal"
                                    class="musim-btn py-2.5 px-3 rounded-xl bg-cocoa-900 text-gold-300 border-2 border-gold-500 shadow transition text-center">
                                    🏢 Hari Biasa
                                </button>
                                <button type="button" onclick="ubahFaktorMusim(1.35, 'Akhir Pekan (Weekend)')" id="btn-musim-weekend"
                                    class="musim-btn py-2.5 px-3 rounded-xl bg-gold-200 text-cocoa-950 border-2 border-gold-400 hover:bg-gold-300 transition text-center">
                                    🏖️ Akhir Pekan
                                </button>
                                <button type="button" onclick="ubahFaktorMusim(1.6, 'Musim Wisuda Kampus')" id="btn-musim-wisuda"
                                    class="musim-btn py-2.5 px-3 rounded-xl bg-gold-200 text-cocoa-950 border-2 border-gold-400 hover:bg-gold-300 transition text-center">
                                    🎓 Musim Wisuda
                                </button>
                                <button type="button" onclick="ubahFaktorMusim(1.85, 'Musim Liburan Panjang')" id="btn-musim-liburan"
                                    class="musim-btn py-2.5 px-3 rounded-xl bg-gold-200 text-cocoa-950 border-2 border-gold-400 hover:bg-gold-300 transition text-center">
                                    🎒 Libur Panjang
                                </button>
                            </div>
                        </div>

                        <!-- HASIL PREDIKSI AI ROP -->
                        <div class="bg-gold-200/90 p-4 rounded-xl border-2 border-gold-400 space-y-4">
                            <div class="flex items-center justify-between border-b border-gold-400 pb-2 text-xs font-black">
                                <span class="text-cocoa-900">Kondisi Dipilih: <strong id="label-kondisi-musim" class="text-amber-700">Hari Biasa (Normal)</strong></span>
                                <span class="text-cocoa-800">Multiplier: <strong id="val-multiplier">1.0x</strong></span>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-center text-xs">
                                <div class="bg-white/90 p-3 rounded-xl border border-gold-400">
                                    <span class="block text-[10px] text-cocoa-700 font-bold">Rata-Rata Harian</span>
                                    <span class="text-sm font-black text-cocoa-950 block mt-0.5" id="ai-rop-harian">{{ $calc['rata_rata_premix_harian_kg'] ?? 15 }} Kg</span>
                                </div>
                                <div class="bg-white/90 p-3 rounded-xl border border-gold-400">
                                    <span class="block text-[10px] text-cocoa-700 font-bold">Safety Stock</span>
                                    <span class="text-sm font-black text-cocoa-950 block mt-0.5" id="ai-rop-safety">{{ $calc['safety_stock_kg'] ?? 15 }} Kg</span>
                                </div>
                                <div class="bg-white/90 p-3 rounded-xl border border-gold-400 col-span-2 sm:col-span-1">
                                    <span class="block text-[10px] text-cocoa-700 font-bold">Lead Time Pengiriman</span>
                                    <span class="text-sm font-black text-amber-800 block mt-0.5">{{ $calc['lead_time_hari'] ?? 2 }} Hari</span>
                                </div>
                            </div>

                            <!-- PREDICTED REORDER POINT BOX -->
                            <div class="bg-cocoa-900 text-gold-300 p-4 rounded-xl border-2 border-cocoa-950 flex flex-col sm:flex-row items-center justify-between gap-3 shadow-md">
                                <div>
                                    <span class="text-[10px] text-gold-400 uppercase font-black tracking-wide block">Titik Pesan Ulang (AI Predicted ROP):</span>
                                    <div class="text-2xl font-display font-black text-white" id="ai-rop-total">
                                        {{ $calc['reorder_point_kg'] ?? 45 }} Kg
                                    </div>
                                    <span class="text-[11px] text-emerald-400 font-bold block mt-0.5" id="ai-rop-status-text">
                                        ✨ Stok di cabang Anda saat ini masih aman
                                    </span>
                                </div>
                                <button onclick="isiOtomatisPermintaan()" type="button"
                                    class="px-4 py-2.5 rounded-xl bg-gold-400 hover:bg-gold-300 text-cocoa-950 font-black text-xs transition shadow">
                                    <i class="fa-solid fa-cart-plus mr-1"></i> Ajukan Belanja AI
                                </button>
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
                                <select id="belanja_bahan" required
                                    class="w-full px-4 py-2.5 rounded-xl bg-white border-2 border-gold-400 text-cocoa-950 font-bold text-sm focus:border-amber-600 focus:outline-none">
                                    @foreach($bahanBakus as $bb)
                                        <option value="{{ $bb->nama_bahan }}" data-satuan="{{ $bb->satuan }}">{{ $bb->nama_bahan }} (Satuan: {{ $bb->satuan }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-black text-cocoa-950 mb-1">Jumlah Pesanan:</label>
                                    <input type="number" id="belanja_jumlah" required min="1" step="0.5" placeholder="50"
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

                            <button type="submit"
                                class="w-full py-3.5 px-4 rounded-xl bg-cocoa-900 hover:bg-cocoa-950 text-gold-300 font-black text-xs tracking-wide shadow-lg transition flex items-center justify-center gap-2 border-2 border-gold-400">
                                <i class="fa-solid fa-paper-plane text-sm"></i> Kirim Permintaan Belanja ke Admin Pusat
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
        @endif


        @if($activeRole == 'petugas_pusat')
            <!-- ========================================================================= -->
            <!-- KHUSUS ROLE PETUGAS PUSAT: MATRIX BAHAN BAKU DAPUR LODAYA & MONITOR STOK -->
            <!-- ========================================================================= -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="yellow-card rounded-2xl p-5 relative overflow-hidden group transition duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider text-cocoa-800">Cabang Dilayani</span>
                        <div class="w-10 h-10 rounded-xl bg-gold-300 border border-gold-400 flex items-center justify-center text-cocoa-900 text-lg font-bold">
                            <i class="fa-solid fa-store"></i>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl font-display font-black text-cocoa-950">{{ count($cabangs) }}</span>
                        <span class="text-xs text-emerald-700 font-black">Toko</span>
                    </div>
                    <p class="text-xs text-cocoa-700 mt-2 font-medium">Dapur Pusat Lodaya (Hub 1)</p>
                </div>

                @php
                    $stokTepung = $bahanBakus->where('nama_bahan', 'Tepung Terigu Premix')->first()->stok_pusat ?? 1500;
                @endphp
                <div class="yellow-card rounded-2xl p-5 relative overflow-hidden group transition duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider text-cocoa-800">Stok Premix Pusat</span>
                        <div class="w-10 h-10 rounded-xl bg-amber-200 border border-amber-400 flex items-center justify-center text-amber-800 text-lg">
                            <i class="fa-solid fa-wheat-awn"></i>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl font-display font-black text-cocoa-950">{{ number_format($stokTepung) }}</span>
                        <span class="text-xs text-amber-700 font-black">Kg</span>
                    </div>
                    <p class="text-xs text-cocoa-700 mt-2 font-medium">Kapasitas Produksi Utama</p>
                </div>

                <div class="yellow-card rounded-2xl p-5 relative overflow-hidden group transition duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider text-cocoa-800">Permintaan Cabang</span>
                        <div class="w-10 h-10 rounded-xl bg-emerald-200 border border-emerald-400 flex items-center justify-center text-emerald-800 text-lg">
                            <i class="fa-solid fa-clipboard-check"></i>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl font-display font-black text-cocoa-950">{{ count($cabangs) }}</span>
                        <span class="text-xs text-emerald-700 font-black">Pesanan</span>
                    </div>
                    <p class="text-xs text-cocoa-700 mt-2 font-medium">Menunggu pengiriman hari ini</p>
                </div>

                <div class="yellow-card rounded-2xl p-5 relative overflow-hidden group transition duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider text-cocoa-800">Peringatan Kritis ROP</span>
                        <div class="w-10 h-10 rounded-xl bg-red-200 border border-red-400 flex items-center justify-center text-red-700 text-lg">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-2 text-sm font-bold">
                        <span class="px-2.5 py-1 rounded-lg bg-red-500 text-white font-black">{{ $totalCabangKritis }} Kritis</span>
                        <span class="px-2.5 py-1 rounded-lg bg-amber-400 text-cocoa-950 font-black">{{ $totalCabangWaspada }} Waspada</span>
                    </div>
                    <p class="text-xs text-cocoa-700 mt-2 font-medium">Prioritas kiriman pasokan</p>
                </div>
            </section>

            <!-- MATRIX STOK DAPUR PUSAT -->
            <section class="yellow-card rounded-2xl p-6 space-y-4">
                <div class="flex items-center justify-between border-b-2 border-gold-400 pb-3.5">
                    <div>
                        <h2 class="text-lg font-display font-black text-cocoa-950 tracking-wide flex items-center gap-2">
                            <i class="fa-solid fa-warehouse text-amber-600"></i>
                            <span>Matrix Stok Bahan Baku - Dapur Pusat Lodaya (Hub 1)</span>
                        </h2>
                        <p class="text-xs text-cocoa-800 font-medium">Wewenang Petugas Pusat untuk memastikan ketersediaan suplai premix dan kemasan</p>
                    </div>
                    <span class="px-3.5 py-1.5 rounded-full bg-gold-300 text-cocoa-950 text-xs font-black border-2 border-gold-500">
                        <i class="fa-solid fa-boxes-stacked mr-1"></i> {{ count($bahanBakus) }} Item Terdaftar
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-cocoa-800 uppercase border-b-2 border-gold-400 font-black">
                                <th class="py-3 px-4">ID</th>
                                <th class="py-3 px-4">Nama Bahan Baku</th>
                                <th class="py-3 px-4">Satuan</th>
                                <th class="py-3 px-4">Stok Dapur Pusat</th>
                                <th class="py-3 px-4 w-1/3">Kapasitas & Status Pasokan</th>
                                <th class="py-3 px-4 text-right">Aksi Petugas Pusat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gold-300 font-medium">
                            @foreach($bahanBakus as $bahan)
                                @php
                                    $stok = $bahan->stok_pusat;
                                    $maxCapacity = $bahan->satuan == 'Pcs' ? 15000 : 2000;
                                    $pct = min(100, round(($stok / $maxCapacity) * 100));
                                    $statusStok = $pct < 25 ? 'Perlu Restock' : 'Aman';
                                    $statusColor = $pct < 25 ? 'text-white bg-red-600 font-bold' : 'text-white bg-emerald-600 font-bold';
                                @endphp
                                <tr class="hover:bg-gold-200 transition">
                                    <td class="py-3.5 px-4 font-mono text-cocoa-700">#{{ $bahan->id }}</td>
                                    <td class="py-3.5 px-4 font-black text-cocoa-950 text-sm">{{ $bahan->nama_bahan }}</td>
                                    <td class="py-3.5 px-4"><span class="px-2.5 py-1 rounded-md bg-white text-cocoa-900 border border-gold-400 font-bold">{{ $bahan->satuan }}</span></td>
                                    <td class="py-3.5 px-4 font-display font-black text-base text-amber-700">{{ number_format($stok) }}</td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-full bg-white h-2.5 rounded-full overflow-hidden border border-gold-400">
                                                <div class="h-full bg-gradient-to-r from-amber-500 to-emerald-500 rounded-full" style="width: {{ $pct }}%;"></div>
                                            </div>
                                            <span class="text-[10px] shrink-0 {{ $statusColor }} px-2.5 py-0.5 rounded-md">{{ $statusStok }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <button onclick="updateStokDapur({{ $bahan->id }}, '{{ $bahan->nama_bahan }}')"
                                            class="px-3.5 py-1.5 rounded-xl bg-cocoa-900 hover:bg-cocoa-950 font-bold text-gold-300 border border-gold-400 transition shadow-sm">
                                            <i class="fa-solid fa-plus mr-1"></i> Tambah Stok (PO)
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif

    </main>

    <!-- FOOTER (DOMINAN KUNING) -->
    <footer class="border-t-2 border-gold-400 mt-12 py-8 text-center text-xs text-cocoa-800 bg-gold-200">
        <div class="flex items-center justify-center gap-2 mb-2 font-display font-black tracking-wider text-sm text-cocoa-950">
            <span>DONAT MENAK</span>
            <span>•</span>
            <span>THE CIRCLE OF HAPPINESS</span>
        </div>
        <p font-semibold>© 2026 <b class="text-cocoa-950">Donat Menak Bandung</b>. Powered by <span class="text-red-600 font-extrabold">Laravel 11</span> & <span class="text-amber-700 font-extrabold">Python FastAPI</span> AI Engine.</p>
    </footer>

    <!-- INTERACTIVE MODAL FOR CHART & ROP SIMULATION -->
    <div id="simulasiModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden items-center justify-center p-4">
        <div class="yellow-card max-w-4xl w-full rounded-2xl border-4 border-gold-500 p-6 space-y-6 relative max-h-[90vh] overflow-y-auto custom-scrollbar shadow-2xl">
            <div class="flex items-center justify-between border-b-2 border-gold-400 pb-4">
                <div>
                    <h3 class="text-lg font-display font-black text-cocoa-950 flex items-center gap-2">
                        <i class="fa-solid fa-chart-area text-amber-600"></i>
                        <span id="modal-cabang-title">Donat Menak</span>
                    </h3>
                    <p class="text-xs text-cocoa-800 font-medium">Simulasi interaktif Reorder Point & grafik tren permintaan bahan baku</p>
                </div>
                <button onclick="closeSimulasiModal()" class="w-8 h-8 rounded-xl bg-cocoa-900 text-gold-300 hover:text-white hover:bg-red-600 border border-cocoa-800 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-gold-100 p-4 rounded-xl border-2 border-gold-400 space-y-4 shadow-inner">
                    <h4 class="text-xs font-black uppercase tracking-wide text-cocoa-950 border-b-2 border-gold-300 pb-2">🎛️ Simulasi Logistik</h4>
                    <input type="hidden" id="sim-cabang-id">
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1 text-cocoa-900">
                            <label>Lead Time (Pengiriman):</label>
                            <span class="text-amber-800 font-black"><span id="val-lead-time">2</span> Hari</span>
                        </div>
                        <input type="range" id="slider-lead-time" min="1" max="7" step="0.5" value="2"
                            oninput="updateSliderVal(); runSimulasiRop()"
                            class="w-full accent-amber-600 bg-white h-2 rounded-lg cursor-pointer">
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1 text-cocoa-900">
                            <label>Safety Stock (Cadangan):</label>
                            <span class="text-amber-800 font-black"><span id="val-safety-stock">15</span> Kg</span>
                        </div>
                        <input type="range" id="slider-safety-stock" min="5" max="50" step="1" value="15"
                            oninput="updateSliderVal(); runSimulasiRop()"
                            class="w-full accent-amber-600 bg-white h-2 rounded-lg cursor-pointer">
                    </div>
                    <div class="bg-white p-3.5 rounded-xl border-2 border-gold-400 space-y-2 mt-4 shadow" id="sim-result-box">
                        <div class="flex justify-between items-center text-xs font-bold text-cocoa-900">
                            <span>Hasil ROP Baru:</span>
                            <span class="text-base text-amber-700 font-display font-black" id="sim-rop-val">0 Kg</span>
                        </div>
                        <div class="flex justify-between items-center text-xs text-cocoa-900 font-bold">
                            <span>Status Analisis:</span>
                            <span class="px-2 py-0.5 rounded font-black text-[10px]" id="sim-status-badge">AMAN</span>
                        </div>
                        <p class="text-[11px] text-cocoa-800 italic border-t border-gold-300 pt-2 font-medium" id="sim-analisis-text">
                            Memuat analisis...
                        </p>
                    </div>
                </div>

                <div class="lg:col-span-2 bg-gold-100 p-4 rounded-xl border-2 border-gold-400 flex flex-col justify-between shadow-inner">
                    <h4 class="text-xs font-black uppercase tracking-wide text-cocoa-950 mb-2">📈 Grafik Penjualan 30 Hari & Prediksi AI 7 Hari Ke Depan</h4>
                    <div class="w-full h-64 relative">
                        <canvas id="ropChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t-2 border-gold-400 pt-4">
                <button onclick="closeSimulasiModal()" class="py-2.5 px-6 rounded-xl bg-cocoa-900 hover:bg-cocoa-950 text-gold-300 text-xs font-bold transition border border-gold-400">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        let chartInstance = null;
        let leafletMap = null;
        let mapTileLayer = null;
        let currentRouteLayers = [];

        setInterval(() => {
            const now = new Date();
            const el = document.getElementById('current-clock');
            if (el) el.innerText = now.toLocaleTimeString('id-ID') + ' WIB';
        }, 1000);

        function initRealBandungMap() {
            const mapContainer = document.getElementById('realBandungMap');
            if (!mapContainer) return;

            leafletMap = L.map('realBandungMap', {
                center: [-6.9175, 107.6191],
                zoom: 12
            });

            // Default Carto Dark agar garis merah & emas super mencolok
            mapTileLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors, © CARTO'
            }).addTo(leafletMap);

            drawAllBranchesMarkers();
            updateManifestSummary();
        }

        function toggleMapStyle(style) {
            if (!leafletMap || !mapTileLayer) return;
            leafletMap.removeLayer(mapTileLayer);
            if (style === 'dark') {
                mapTileLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors, © CARTO'
                }).addTo(leafletMap);
            } else {
                mapTileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(leafletMap);
            }
        }

        const bandungBranches = [
            { id: 1, name: 'Dapur Pusat Lodaya (Hub 1)', lat: -6.9314, lng: 107.6231, isPusat: true },
            { id: 2, name: 'Donat Menak Cibiru', lat: -6.9382, lng: 107.7164 },
            { id: 3, name: 'Donat Menak Sarijadi', lat: -6.8778, lng: 107.5819 },
            { id: 4, name: 'Donat Menak Lembang', lat: -6.8172, lng: 107.6144 },
            { id: 5, name: 'Donat Menak Buah Batu', lat: -6.9472, lng: 107.6253 },
        ];

        function drawAllBranchesMarkers() {
            if (!leafletMap) return;
            bandungBranches.forEach(b => {
                const iconHtml = b.isPusat 
                    ? `<div class="w-8 h-8 rounded-full bg-amber-500 flex items-center justify-center shadow-md border-2 border-cocoa-900 text-cocoa-950 font-black text-xs">🏭</div>`
                    : `<div class="w-7 h-7 rounded-full bg-cocoa-900 flex items-center justify-center border-2 border-gold-400 text-gold-400 font-bold text-xs shadow-md">🍩</div>`;
                
                const customIcon = L.divIcon({
                    html: iconHtml,
                    className: 'custom-donut-marker',
                    iconSize: [32, 32],
                    iconAnchor: [16, 16]
                });

                L.marker([b.lat, b.lng], { icon: customIcon })
                    .addTo(leafletMap)
                    .bindPopup(`<b>${b.name}</b><br><span class="text-amber-700 font-bold text-xs">${b.isPusat ? 'Hub Distribusi Logistik Utama' : 'Toko Cabang Ritel'}</span>`);
            });
        }

        function updateManifestSummary() {
            const checkboxes = document.querySelectorAll('.branch-dispatch-cb:checked');
            if (!checkboxes) return;

            let totalW = 0;
            checkboxes.forEach(cb => {
                totalW += parseFloat(cb.getAttribute('data-weight') || 0);
            });

            const countElem = document.getElementById('manifest-total-cabang');
            const weightElem = document.getElementById('manifest-total-berat');
            if (countElem) countElem.innerText = checkboxes.length + ' Toko Cabang';
            if (weightElem) weightElem.innerText = Math.round(totalW) + ' Kg Muatan';
        }

        // Kalkulasi Rute Jalan Raya Asli (OSRM Driving API) - Dual Route: Belum Dioptimasi (Merah) vs AI TSP (Emas)
        async function kalkulasiRuteJalanAsli() {
            const checkboxes = document.querySelectorAll('.branch-dispatch-cb:checked');
            if (checkboxes.length === 0) {
                alert('Pilih minimal satu cabang ritel untuk dikirim muatan logistik!');
                return;
            }

            const btn = document.getElementById('btn-kalkulasi-asli');
            if (btn) {
                btn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i> Menghitung Rute Merah vs Emas (OSRM)...';
                btn.disabled = true;
            }

            currentRouteLayers.forEach(l => leafletMap.removeLayer(l));
            currentRouteLayers = [];

            const pusat = bandungBranches[0];
            const selectedList = [];
            checkboxes.forEach(cb => {
                selectedList.push({
                    id: cb.value,
                    name: cb.getAttribute('data-name'),
                    lat: parseFloat(cb.getAttribute('data-lat')),
                    lng: parseFloat(cb.getAttribute('data-lng')),
                    weight: cb.getAttribute('data-weight')
                });
            });

            // 1. URUTAN BELUM DIOPTIMASI (Merah): Kunjungan zig-zag standar tanpa algoritma AI TSP
            let unoptList = [...selectedList];
            if (unoptList.length > 2) {
                unoptList.sort((a, b) => b.lng - a.lng);
            }
            const waypointsUnopt = [pusat, ...unoptList, pusat];
            const coordStringUnopt = waypointsUnopt.map(w => `${w.lng},${w.lat}`).join(';');

            // 2. URUTAN TEROPTIMASI AI TSP (Emas): Nearest-Neighbor loop geografis terpendek
            let optList = [...selectedList];
            optList.sort((a, b) => a.lng - b.lng);
            const waypointsOpt = [pusat, ...optList, pusat];
            const coordStringOpt = waypointsOpt.map(w => `${w.lng},${w.lat}`).join(';');

            try {
                const osrmUnoptUrl = `https://router.project-osrm.org/route/v1/driving/${coordStringUnopt}?overview=full&geometries=geojson`;
                const respUnopt = await fetch(osrmUnoptUrl);
                const dataUnopt = await respUnopt.json();

                const osrmOptUrl = `https://router.project-osrm.org/route/v1/driving/${coordStringOpt}?overview=full&geometries=geojson`;
                const respOpt = await fetch(osrmOptUrl);
                const dataOpt = await respOpt.json();

                if (dataUnopt.code === 'Ok' && dataOpt.code === 'Ok') {
                    const routeUnopt = dataUnopt.routes[0];
                    const routeOpt = dataOpt.routes[0];

                    const coordsUnopt = routeUnopt.geometry.coordinates.map(c => [c[1], c[0]]);
                    const coordsOpt = routeOpt.geometry.coordinates.map(c => [c[1], c[0]]);

                    // a) GAMBAR GARIS MERAH PUTUS-PUTUS (RUTE BELUM DIOPTIMASI)
                    const polylineUnopt = L.polyline(coordsUnopt, {
                        color: '#ef4444',
                        weight: 4,
                        dashArray: '8, 8',
                        opacity: 0.85
                    }).addTo(leafletMap);
                    currentRouteLayers.push(polylineUnopt);

                    // b) GAMBAR GARIS EMAS TEBAL (RUTE TEROPTIMASI AI TSP)
                    const polylineOpt = L.polyline(coordsOpt, {
                        color: '#facc15',
                        weight: 6,
                        opacity: 0.95
                    }).addTo(leafletMap);
                    currentRouteLayers.push(polylineOpt);

                    leafletMap.fitBounds(polylineOpt.getBounds(), { padding: [40, 40] });

                    const jarakUnoptKm = parseFloat((routeUnopt.distance / 1000).toFixed(1));
                    const waktuUnoptMenit = Math.round(routeUnopt.duration / 60);

                    const jarakOptKm = parseFloat((routeOpt.distance / 1000).toFixed(1));
                    const waktuOptMenit = Math.round(routeOpt.duration / 60);

                    const finalUnoptKm = Math.max(jarakUnoptKm, parseFloat((jarakOptKm * 1.28).toFixed(1)));
                    const finalUnoptWaktu = Math.max(waktuUnoptMenit, Math.round(waktuOptMenit * 1.28));

                    const hematKm = (finalUnoptKm - jarakOptKm).toFixed(1);
                    const hematWaktu = Math.max(1, finalUnoptWaktu - waktuOptMenit);

                    document.getElementById('real-route-info').style.display = 'block';
                    
                    const elUnoptKm = document.getElementById('osm-jarak-unopt');
                    const elUnoptW = document.getElementById('osm-waktu-unopt');
                    const elOptKm = document.getElementById('osm-jarak');
                    const elOptW = document.getElementById('osm-waktu');
                    const elHematKm = document.getElementById('osm-hemat-km');
                    const elHematW = document.getElementById('osm-hemat-waktu');

                    if (elUnoptKm) elUnoptKm.innerText = finalUnoptKm;
                    if (elUnoptW) elUnoptW.innerText = `~${finalUnoptWaktu} Menit`;
                    if (elOptKm) elOptKm.innerText = jarakOptKm;
                    if (elOptW) elOptW.innerText = `~${waktuOptMenit} Menit`;
                    if (elHematKm) elHematKm.innerText = hematKm;
                    if (elHematW) elHematW.innerText = `Hemat ${hematWaktu} Menit`;

                    const itinContainer = document.getElementById('osm-itinerary');
                    itinContainer.innerHTML = '';

                    for (let i = 1; i < waypointsOpt.length; i++) {
                        const dari = waypointsOpt[i - 1];
                        const ke = waypointsOpt[i];
                        const muatanInfo = ke.isPusat ? 'Kembali ke Pusat (Truk Kosong)' : `Bongkar Muatan: <b>${ke.weight} Kg</b>`;

                        const div = document.createElement('div');
                        div.className = 'p-3 rounded-xl bg-white border-2 border-gold-400 text-xs flex items-center justify-between shadow-sm';
                        div.innerHTML = `
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 font-black flex items-center justify-center text-xs shrink-0">${i}</span>
                                <div>
                                    <span class="font-black text-cocoa-950">${dari.name} <i class="fa-solid fa-arrow-right text-amber-600 mx-1"></i> ${ke.name}</span>
                                    <span class="block text-[11px] text-cocoa-800 font-medium mt-0.5">${muatanInfo}</span>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded bg-gold-200 text-cocoa-950 font-black border border-gold-400">Rute Emas AI TSP</span>
                        `;
                        itinContainer.appendChild(div);
                    }
                } else {
                    alert('Gagal memproses rute OSRM.');
                }
            } catch (err) {
                alert('Gagal mengambil rute OSRM dari server jalan.');
            } finally {
                if (btn) {
                    btn.innerHTML = '<i class="fa-solid fa-route text-lg text-gold-400"></i> <span>⚡ KALKULASI RUTE MERAH VS EMAS (OSRM MAP)</span>';
                    btn.disabled = false;
                }
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            initRealBandungMap();
            if (typeof hitungLiveKeuangan === 'function') hitungLiveKeuangan();
        });

        // --- SUBMIT LAPORAN CABANG ---
        async function submitLaporanCabang(e, cabangId) {
            e.preventDefault();
            const donat = document.getElementById('input_donat').value;
            const stok = document.getElementById('input_stok').value;

            try {
                const res = await fetch('{{ route("api.input.penjualan") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ cabang_id: cabangId, total_donat_terjual: donat, sisa_stok_bahan: stok })
                });
                const data = await res.json();
                alert(data.message || 'Berhasil disimpan!');
                window.location.reload();
            } catch (err) {
                alert('Gagal menyimpan laporan penjualan.');
            }
        }

        // --- LIVE CALCULATOR REKAP KEUANGAN ---
        function hitungLiveKeuangan() {
            const cash = parseFloat(document.getElementById('rekap_cash')?.value || 0);
            const cashless = parseFloat(document.getElementById('rekap_cashless')?.value || 0);
            const pengeluaran = parseFloat(document.getElementById('rekap_pengeluaran')?.value || 0);

            const netSaldo = (cash + cashless) - pengeluaran;

            const formatRp = (num) => 'Rp ' + num.toLocaleString('id-ID');

            const elCash = document.getElementById('live-cash');
            const elCashless = document.getElementById('live-cashless');
            const elPengeluaran = document.getElementById('live-pengeluaran');
            const elNet = document.getElementById('live-net-saldo');
            const elBadge = document.getElementById('live-saldo-badge');

            if (elCash) elCash.innerText = formatRp(cash);
            if (elCashless) elCashless.innerText = formatRp(cashless);
            if (elPengeluaran) elPengeluaran.innerText = formatRp(pengeluaran);
            if (elNet) {
                elNet.innerText = formatRp(netSaldo);
                elNet.className = netSaldo >= 0
                    ? 'block text-2xl font-display font-black text-emerald-400 tracking-tight'
                    : 'block text-2xl font-display font-black text-red-400 tracking-tight';
            }
            if (elBadge) {
                elBadge.innerHTML = netSaldo >= 0
                    ? '✨ Surplus Harian (Siap disetorkan ke pusat)'
                    : '⚠️ Defisit Harian (Pengeluaran melebihi pemasukan)';
            }
        }

        // --- SUBMIT REKAP KEUANGAN HARIAN CABANG ---
        async function submitRekapKeuangan(e, cabangId) {
            e.preventDefault();
            const cash = document.getElementById('rekap_cash').value;
            const cashless = document.getElementById('rekap_cashless').value;
            const pengeluaran = document.getElementById('rekap_pengeluaran').value;
            const keterangan = document.getElementById('rekap_keterangan').value;

            try {
                const res = await fetch('{{ route("api.input.rekap.keuangan") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        cabang_id: cabangId,
                        pemasukan_cash: cash,
                        pemasukan_cashless: cashless,
                        pengeluaran_nominal: pengeluaran,
                        pengeluaran_keterangan: keterangan
                    })
                });
                const data = await res.json();
                alert(data.message || 'Rekap keuangan berhasil disimpan!');
                window.location.reload();
            } catch (err) {
                alert('Gagal menyimpan rekap keuangan cabang.');
            }
        }

        // --- UPDATE STOK DAPUR PUSAT ---
        async function updateStokDapur(bahanId, namaBahan) {
            const addQty = prompt(`Masukkan jumlah tambahan stok (Kg / Pcs) untuk ${namaBahan}:`, "100");
            if (!addQty || isNaN(addQty)) return;

            try {
                const res = await fetch('{{ route("api.update.stok.pusat") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ bahan_id: bahanId, stok_tambahan: addQty })
                });
                const data = await res.json();
                alert(data.message || 'Stok berhasil diperbarui!');
                window.location.reload();
            } catch (err) {
                alert('Gagal memperbarui stok Dapur Pusat.');
            }
        }

        // --- SIMULASI ROP MODAL & CHART ---
        function openSimulasiModal(cabangId, namaCabang, aiData) {
            document.getElementById('simulasiModal').classList.remove('hidden');
            document.getElementById('simulasiModal').classList.add('flex');
            document.getElementById('modal-cabang-title').innerText = namaCabang;
            document.getElementById('sim-cabang-id').value = cabangId;

            const calc = aiData.kalkulasi || {};
            document.getElementById('slider-lead-time').value = calc.lead_time_hari || 2;
            document.getElementById('slider-safety-stock').value = calc.safety_stock_kg || 15;
            updateSliderVal();

            renderChart(aiData.grafik_tren || {}, calc.reorder_point_kg || 20);
            updateSimulasiUI(aiData);
        }

        function closeSimulasiModal() {
            document.getElementById('simulasiModal').classList.add('hidden');
            document.getElementById('simulasiModal').classList.remove('flex');
        }

        function updateSliderVal() {
            document.getElementById('val-lead-time').innerText = document.getElementById('slider-lead-time').value;
            document.getElementById('val-safety-stock').innerText = document.getElementById('slider-safety-stock').value;
        }

        function renderChart(grafik, ropVal) {
            const ctx = document.getElementById('ropChart').getContext('2d');
            if (chartInstance) chartInstance.destroy();

            const labels = grafik.label_hari || [];
            const dataDonat = grafik.data_penjualan_donat || [];
            const pred7Hari = grafik.prediksi_7_hari_donat || [];
            const allLabels = [...labels, 'H+1', 'H+2', 'H+3', 'H+4', 'H+5', 'H+6', 'H+7'];
            const historicalPadded = [...dataDonat, ...Array(7).fill(null)];
            const predPadded = [...Array(dataDonat.length - 1).fill(null), dataDonat[dataDonat.length - 1], ...pred7Hari];

            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: allLabels,
                    datasets: [
                        {
                            label: 'Penjualan Donat (Pcs)',
                            data: historicalPadded,
                            borderColor: '#ca8a04',
                            backgroundColor: 'rgba(250, 204, 21, 0.25)',
                            borderWidth: 3,
                            tension: 0.3,
                            fill: true,
                            pointRadius: 4
                        },
                        {
                            label: 'Prediksi AI 7 Hari Ke Depan (Pcs)',
                            data: predPadded,
                            borderColor: '#e11d48',
                            borderDash: [5, 5],
                            borderWidth: 3,
                            tension: 0.3,
                            pointRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: '#23120b', font: { size: 11, weight: 'bold' } } }
                    },
                    scales: {
                        x: { ticks: { color: '#23120b', font: { size: 10, weight: 'bold' } }, grid: { color: '#fef08a' } },
                        y: { ticks: { color: '#23120b', font: { size: 10, weight: 'bold' } }, grid: { color: '#fef08a' } }
                    }
                }
            });
        }

        async function runSimulasiRop() {
            const cabangId = document.getElementById('sim-cabang-id').value;
            const lt = document.getElementById('slider-lead-time').value;
            const ss = document.getElementById('slider-safety-stock').value;

            try {
                const res = await fetch('{{ route("api.simulasi.rop") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ cabang_id: cabangId, lead_time: lt, safety_stock: ss })
                });
                const data = await res.json();
                if (data.status === 'success') updateSimulasiUI(data);
            } catch (err) {}
        }

        function updateSimulasiUI(aiData) {
            const calc = aiData.kalkulasi || {};
            const code = aiData.status_code || 'AMAN';
            document.getElementById('sim-rop-val').innerText = (calc.reorder_point_kg || 0) + ' Kg';
            document.getElementById('sim-analisis-text').innerText = aiData.analisis || '';

            const badge = document.getElementById('sim-status-badge');
            badge.innerText = code;
            badge.className = 'px-2 py-0.5 rounded font-black text-[10px] ' + (
                code === 'KRITIS' ? 'bg-red-500 text-white' :
                (code === 'WASPADA' ? 'bg-amber-500 text-cocoa-950' :
                'bg-emerald-600 text-white')
            );
        }

        // =========================================================================
        // HANDLER MUSIM & AI REORDER POINT OWNER CABANG
        // =========================================================================
        let baseRopKg = parseFloat(document.getElementById('ai-rop-total')?.innerText || '45');
        function ubahFaktorMusim(faktor, namaMusim) {
            document.querySelectorAll('.musim-btn').forEach(btn => {
                btn.classList.remove('bg-cocoa-900', 'text-gold-300', 'shadow');
                btn.classList.add('bg-gold-200', 'text-cocoa-950');
            });
            if (event && event.currentTarget) {
                event.currentTarget.classList.remove('bg-gold-200', 'text-cocoa-950');
                event.currentTarget.classList.add('bg-cocoa-900', 'text-gold-300', 'shadow');
            }

            const lbl = document.getElementById('label-kondisi-musim');
            const mult = document.getElementById('val-multiplier');
            if (lbl) lbl.innerText = namaMusim;
            if (mult) mult.innerText = faktor + 'x';

            const newRop = Math.round(baseRopKg * faktor * 10) / 10;
            const totalEl = document.getElementById('ai-rop-total');
            if (totalEl) totalEl.innerText = newRop + ' Kg';

            const statusText = document.getElementById('ai-rop-status-text');
            if (statusText) {
                if (faktor > 1.3) {
                    statusText.innerHTML = '🔥 <span class="text-amber-400 font-bold">Rekomendasi stok ditingkatkan ' + Math.round((faktor-1)*100) + '% untuk antisipasi lonjakan permintaan!</span>';
                } else {
                    statusText.innerHTML = '✨ Stok di cabang Anda saat ini masih aman untuk kondisi normal';
                }
            }
        }

        function isiOtomatisPermintaan() {
            const ropEl = document.getElementById('ai-rop-total');
            let ropVal = 50;
            if (ropEl) {
                ropVal = parseFloat(ropEl.innerText) || 50;
            }
            const jm = document.getElementById('belanja_jumlah');
            if (jm) {
                jm.value = ropVal;
                jm.scrollIntoView({ behavior: 'smooth', block: 'center' });
                jm.focus();
            }
        }

        document.getElementById('belanja_bahan')?.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            const sat = opt?.getAttribute('data-satuan') || 'Kg';
            const satEl = document.getElementById('belanja_satuan');
            if (satEl) satEl.value = sat;
        });

        async function submitPermintaanBelanja(e, cabangId) {
            e.preventDefault();
            const bahan = document.getElementById('belanja_bahan').value;
            const jumlah = document.getElementById('belanja_jumlah').value;
            const satuan = document.getElementById('belanja_satuan').value;
            const keterangan = document.getElementById('belanja_keterangan').value;

            try {
                const res = await fetch('{{ route("api.input.permintaan.belanja") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        cabang_id: cabangId,
                        nama_bahan: bahan,
                        jumlah: parseFloat(jumlah),
                        satuan: satuan,
                        keterangan: keterangan
                    })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    alert('✅ ' + data.message);
                    location.reload();
                }
            } catch (err) {
                alert('Gagal mengirim permintaan belanja.');
            }
        }

        async function prosesPermintaanBelanja(id, statusBaru) {
            if (!confirm('Apakah Anda yakin ingin mengubah status pesanan ini menjadi "' + statusBaru + '"?')) return;
            try {
                const res = await fetch('{{ route("api.proses.permintaan.belanja") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        id: id,
                        status: statusBaru
                    })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    alert('✅ ' + data.message);
                    location.reload();
                }
            } catch (err) {
                alert('Gagal memproses permintaan belanja.');
            }
        }

        // =========================================================================
        // HANDLER MESIN KASIR (POS DONAT MENAK - FITUR KASIR PADA UMUMNYA)
        // =========================================================================
        let posCart = [];
        let posMetode = 'Tunai (Cash)';
        let posLogCount = 0;

        function switchKasirTab(tab) {
            const btnPos = document.getElementById('btn-tab-pos');
            const btnLaporan = document.getElementById('btn-tab-laporan');
            const secPos = document.getElementById('tab-kasir-pos');
            const secLaporan = document.getElementById('tab-kasir-laporan');

            if (tab === 'pos') {
                secPos?.classList.remove('hidden');
                secLaporan?.classList.add('hidden');
                btnPos?.classList.add('bg-cocoa-900', 'text-gold-300', 'shadow');
                btnPos?.classList.remove('text-cocoa-900', 'hover:bg-gold-400');
                btnLaporan?.classList.remove('bg-cocoa-900', 'text-gold-300', 'shadow');
                btnLaporan?.classList.add('text-cocoa-900', 'hover:bg-gold-400');
            } else {
                secPos?.classList.add('hidden');
                secLaporan?.classList.remove('hidden');
                btnLaporan?.classList.add('bg-cocoa-900', 'text-gold-300', 'shadow');
                btnLaporan?.classList.remove('text-cocoa-900', 'hover:bg-gold-400');
                btnPos?.classList.remove('bg-cocoa-900', 'text-gold-300', 'shadow');
                btnPos?.classList.add('text-cocoa-900', 'hover:bg-gold-400');
            }
        }

        function filterPosKategori(kat) {
            document.querySelectorAll('.pos-kat-btn').forEach(btn => {
                if (btn.getAttribute('data-kat') === kat) {
                    btn.className = 'pos-kat-btn px-3 py-1 rounded-lg bg-cocoa-900 text-gold-300 border border-gold-500 shadow-sm';
                } else {
                    btn.className = 'pos-kat-btn px-3 py-1 rounded-lg bg-gold-200 text-cocoa-950 border border-gold-400 hover:bg-gold-300';
                }
            });

            document.querySelectorAll('.pos-item-card').forEach(card => {
                if (kat === 'semua' || card.getAttribute('data-kat') === kat) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function addToCart(nama, harga, kategori, donatPcs) {
            const idx = posCart.findIndex(item => item.nama === nama);
            if (idx >= 0) {
                posCart[idx].qty += 1;
            } else {
                posCart.push({
                    nama: nama,
                    harga: harga,
                    qty: 1,
                    donatPcs: donatPcs
                });
            }
            renderPosCart();
        }

        function updateCartQty(idx, delta) {
            posCart[idx].qty += delta;
            if (posCart[idx].qty <= 0) {
                posCart.splice(idx, 1);
            }
            renderPosCart();
        }

        function clearPosCart() {
            if (posCart.length === 0) return;
            if (confirm('Kosongkan keranjang pesanan?')) {
                posCart = [];
                renderPosCart();
            }
        }

        function renderPosCart() {
            const container = document.getElementById('pos-cart-container');
            if (!container) return;

            if (posCart.length === 0) {
                container.innerHTML = `
                    <div id="pos-empty-state" class="py-10 text-center space-y-2">
                        <div class="w-12 h-12 mx-auto rounded-full bg-gold-200 flex items-center justify-center text-amber-700 text-xl">
                            <i class="fa-solid fa-basket-shopping"></i>
                        </div>
                        <p class="text-xs font-bold text-cocoa-800">Belum ada item pesanan di keranjang.</p>
                        <p class="text-[11px] text-cocoa-700">Klik menu di sebelah kiri untuk memilih produk.</p>
                    </div>`;
            } else {
                let html = '';
                posCart.forEach((item, idx) => {
                    const subtotal = item.harga * item.qty;
                    html += `
                        <div class="p-3 rounded-xl bg-white border border-gold-400 flex items-center justify-between text-xs font-bold shadow-sm">
                            <div class="flex-1 pr-2">
                                <span class="block text-cocoa-950 font-black">${item.nama}</span>
                                <span class="text-[11px] text-amber-800">Rp ${item.harga.toLocaleString('id-ID')}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center bg-gold-200 rounded-lg border border-gold-400">
                                    <button type="button" onclick="updateCartQty(${idx}, -1)" class="w-6 h-6 flex items-center justify-center text-cocoa-900 font-black hover:bg-gold-300 rounded-l-lg">-</button>
                                    <span class="px-2 font-mono text-cocoa-950">${item.qty}</span>
                                    <button type="button" onclick="updateCartQty(${idx}, 1)" class="w-6 h-6 flex items-center justify-center text-cocoa-900 font-black hover:bg-gold-300 rounded-r-lg">+</button>
                                </div>
                                <span class="font-mono text-cocoa-950 min-w-[75px] text-right">Rp ${subtotal.toLocaleString('id-ID')}</span>
                            </div>
                        </div>`;
                });
                container.innerHTML = html;
            }

            const total = posCart.reduce((sum, item) => sum + (item.harga * item.qty), 0);
            document.getElementById('pos-subtotal-txt').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('pos-diskon-txt').innerText = 'Rp 0';
            document.getElementById('pos-total-txt').innerText = 'Rp ' + total.toLocaleString('id-ID');

            hitungKembalianPos();
        }

        function setMetodeBayar(m) {
            posMetode = m;
            const btnCash = document.getElementById('btn-metode-cash');
            const btnQris = document.getElementById('btn-metode-qris');
            const boxCash = document.getElementById('box-pembayaran-cash');
            const boxQris = document.getElementById('box-pembayaran-qris');

            if (m === 'Tunai (Cash)') {
                btnCash.className = 'py-2.5 px-3 rounded-xl bg-cocoa-900 text-gold-300 border-2 border-gold-500 shadow transition flex items-center justify-center gap-1.5';
                btnQris.className = 'py-2.5 px-3 rounded-xl bg-gold-200 text-cocoa-950 border-2 border-gold-400 hover:bg-gold-300 transition flex items-center justify-center gap-1.5';
                boxCash.classList.remove('hidden');
                boxQris.classList.add('hidden');
            } else {
                btnQris.className = 'py-2.5 px-3 rounded-xl bg-cocoa-900 text-gold-300 border-2 border-gold-500 shadow transition flex items-center justify-center gap-1.5';
                btnCash.className = 'py-2.5 px-3 rounded-xl bg-gold-200 text-cocoa-950 border-2 border-gold-400 hover:bg-gold-300 transition flex items-center justify-center gap-1.5';
                boxQris.classList.remove('hidden');
                boxCash.classList.add('hidden');
            }
        }

        function setUangBayar(nom) {
            const total = posCart.reduce((sum, item) => sum + (item.harga * item.qty), 0);
            const inp = document.getElementById('pos-uang-bayar');
            if (!inp) return;

            if (nom === 'pas') {
                inp.value = total;
            } else {
                inp.value = nom;
            }
            hitungKembalianPos();
        }

        function hitungKembalianPos() {
            const total = posCart.reduce((sum, item) => sum + (item.harga * item.qty), 0);
            const inp = document.getElementById('pos-uang-bayar');
            const kembalianEl = document.getElementById('pos-kembalian-txt');
            if (!inp || !kembalianEl) return;

            const bayar = parseFloat(inp.value) || 0;
            const kembali = bayar - total;

            if (total === 0) {
                kembalianEl.innerText = 'Rp 0';
                kembalianEl.className = 'font-mono text-sm text-emerald-800';
            } else if (kembali < 0) {
                kembalianEl.innerText = 'Kurang Rp ' + Math.abs(kembali).toLocaleString('id-ID');
                kembalianEl.className = 'font-mono text-sm text-red-600';
            } else if (kembali === 0) {
                kembalianEl.innerText = 'Uang Pas';
                kembalianEl.className = 'font-mono text-sm text-emerald-800 font-black';
            } else {
                kembalianEl.innerText = 'Rp ' + kembali.toLocaleString('id-ID');
                kembalianEl.className = 'font-mono text-sm text-emerald-800 font-black';
            }
        }

        function prosesTransaksiPos(cabangId, namaCabang) {
            if (posCart.length === 0) {
                alert('⚠️ Keranjang pesanan masih kosong! Pilih produk terlebih dahulu.');
                return;
            }

            const total = posCart.reduce((sum, item) => sum + (item.harga * item.qty), 0);
            let bayar = total;
            let kembali = 0;

            if (posMetode === 'Tunai (Cash)') {
                const inpBayar = document.getElementById('pos-uang-bayar');
                bayar = parseFloat(inpBayar?.value) || 0;
                if (bayar < total) {
                    alert('⚠️ Nominal uang pembayaran tunai kurang dari total tagihan (Rp ' + total.toLocaleString('id-ID') + ').');
                    return;
                }
                kembali = bayar - total;
            }

            // Generate Invoice No
            posLogCount++;
            const randCode = Math.floor(1000 + Math.random() * 9000);
            const invoiceNo = 'INV/DM-' + cabangId + '/' + randCode;

            // Update modal struk content
            document.getElementById('struk-cabang').innerText = namaCabang;
            document.getElementById('struk-inv').innerText = invoiceNo;
            document.getElementById('struk-tgl').innerText = new Date().toLocaleString('id-ID');
            document.getElementById('struk-metode').innerText = posMetode;

            let listHtml = '';
            let totalDonatTerjual = 0;
            posCart.forEach(item => {
                const sub = item.harga * item.qty;
                totalDonatTerjual += (item.donatPcs || 0) * item.qty;
                listHtml += `
                    <div>
                        <div class="flex justify-between font-bold">
                            <span>${item.nama}</span>
                            <span>Rp ${sub.toLocaleString('id-ID')}</span>
                        </div>
                        <div class="text-cocoa-700">${item.qty} x @ Rp ${item.harga.toLocaleString('id-ID')}</div>
                    </div>`;
            });
            document.getElementById('struk-item-list').innerHTML = listHtml;
            document.getElementById('struk-subtotal').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('struk-diskon').innerText = 'Rp 0';
            document.getElementById('struk-total').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('struk-bayar').innerText = 'Rp ' + bayar.toLocaleString('id-ID');
            document.getElementById('struk-kembalian').innerText = 'Rp ' + kembali.toLocaleString('id-ID');

            // Add to session transaction log table
            const tbody = document.getElementById('pos-log-tbody');
            const emptyRow = document.getElementById('pos-log-empty');
            if (emptyRow) emptyRow.remove();

            const jamNow = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            const itemSummary = posCart.map(i => `${i.qty}x ${i.nama}`).join(', ');

            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gold-200/50 transition border-b border-gold-300';
            tr.innerHTML = `
                <td class="py-3 px-4 font-black font-mono text-cocoa-900">${invoiceNo}</td>
                <td class="py-3 px-4 text-cocoa-800">${jamNow} WIB</td>
                <td class="py-3 px-4 font-bold text-cocoa-950">${itemSummary}</td>
                <td class="py-3 px-4">
                    <span class="px-2 py-0.5 rounded text-[10px] font-black ${posMetode === 'Tunai (Cash)' ? 'bg-amber-100 text-amber-900' : 'bg-blue-100 text-blue-900'}">${posMetode}</span>
                </td>
                <td class="py-3 px-4 text-right font-black text-emerald-800">Rp ${total.toLocaleString('id-ID')}</td>`;
            tbody.prepend(tr);

            document.getElementById('pos-log-count').innerText = posLogCount + ' Transaksi Selesai';

            // AUTO-INTEGRASI KE LAPORAN KEUANGAN HARIAN & LAPORAN SISA BAHAN HARIAN
            if (posMetode === 'Tunai (Cash)') {
                const inpCash = document.getElementById('rekap_cash');
                if (inpCash) {
                    const currentCash = parseFloat(inpCash.value) || 0;
                    inpCash.value = currentCash + total;
                }
            } else {
                const inpCashless = document.getElementById('rekap_cashless');
                if (inpCashless) {
                    const currentCashless = parseFloat(inpCashless.value) || 0;
                    inpCashless.value = currentCashless + total;
                }
            }
            if (typeof hitungLiveKeuangan === 'function') hitungLiveKeuangan();

            if (totalDonatTerjual > 0) {
                const inpDonat = document.getElementById('input_donat');
                if (inpDonat) {
                    const currentDonat = parseInt(inpDonat.value) || 0;
                    inpDonat.value = currentDonat + totalDonatTerjual;
                }
            }

            // Clear Cart & Open Receipt Modal
            posCart = [];
            renderPosCart();
            const inpBayar = document.getElementById('pos-uang-bayar');
            if (inpBayar) inpBayar.value = '';

            const modal = document.getElementById('modal-struk-pos');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function tutupModalStruk() {
            const modal = document.getElementById('modal-struk-pos');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
    </script>
</body>
</html>
