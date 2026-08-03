@extends('layouts.app')
@section('content')
    <!-- HEADER -->
    <div class="bg-gold-200 border-b-2 border-gold-400 px-6 py-4 shadow-md rounded-2xl mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-black text-cocoa-950 uppercase">🏭 Dashboard Petugas Pusat – Dapur Lodaya</h2>
            <p class="text-xs font-semibold text-cocoa-800">Wewenang memantau ketersediaan stok bahan pokok dan glaze di Dapur Pusat Lodaya.</p>
        </div>
    </div>

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

            <!-- MATRIX STOK DAPUR PUSAT & MONITOR STOK SEMUA CABANG -->
            <section class="yellow-card rounded-2xl p-6 space-y-4">
                <div class="flex items-center justify-between border-b-2 border-gold-400 pb-3.5">
                    <div>
                        <h2 class="text-lg font-display font-black text-cocoa-950 tracking-wide flex items-center gap-2">
                            <i class="fa-solid fa-warehouse text-amber-600"></i>
                            <span>Matrix Stok Bahan Baku - Dapur Pusat Lodaya & Monitor Semua Cabang</span>
                        </h2>
                        <p class="text-xs text-cocoa-800 font-medium">Wewenang Petugas Pusat untuk memantau ketersediaan suplai di Hub 1 sekaligus memonitor sisa stok di seluruh toko cabang</p>
                    </div>
                    <span class="px-3.5 py-1.5 rounded-full bg-gold-300 text-cocoa-950 text-xs font-black border-2 border-gold-500">
                        <i class="fa-solid fa-boxes-stacked mr-1"></i> {{ count($bahanBakus) }} Item Terdaftar
                    </span>
                </div>

                <div class="overflow-x-auto max-h-[500px] overflow-y-auto custom-scrollbar border-2 border-gold-400 rounded-xl bg-white/90">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="text-cocoa-950 uppercase border-b-2 border-gold-400 font-black bg-gold-300/80 sticky top-0 z-10">
                                <th class="py-3 px-3">ID</th>
                                <th class="py-3 px-4">Nama Bahan Baku</th>
                                <th class="py-3 px-3">Kategori</th>
                                <th class="py-3 px-3 text-center bg-cocoa-900 text-gold-300">🏢 Stok Dapur Pusat</th>
                                <th class="py-3 px-4 bg-amber-500 text-cocoa-950">📍 Sisa Stok Semua Cabang</th>
                                <th class="py-3 px-3">Status Pasokan</th>
                                <th class="py-3 px-3 text-right">Aksi Petugas Pusat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gold-200 font-medium text-cocoa-950">
                            @foreach($bahanBakus as $bahan)
                                @php
                                    $stok = $bahan->stok_pusat;
                                    $maxCapacity = $bahan->satuan == 'Pcs' ? 15000 : 2000;
                                    $pct = min(100, round(($stok / $maxCapacity) * 100));
                                    $statusStok = $pct < 25 ? 'Perlu Restock' : 'Aman';
                                    $statusColor = $pct < 25 ? 'text-white bg-red-600 font-bold' : 'text-white bg-emerald-600 font-bold';
                                @endphp
                                <tr class="hover:bg-gold-100/60 transition">
                                    <td class="py-3 px-3 font-mono text-cocoa-700 font-bold">#{{ $bahan->id }}</td>
                                    <td class="py-3 px-4 font-black text-cocoa-950 text-xs">{{ $bahan->nama_bahan }}</td>
                                    <td class="py-3 px-3">
                                        <span class="px-2 py-0.5 rounded bg-gold-200 text-cocoa-900 font-bold text-[10px]">📦 {{ $bahan->kategori ?? 'Umum' }}</span>
                                    </td>
                                    <td class="py-3 px-3 text-center bg-cocoa-900/5 font-black text-amber-900 text-sm">
                                        {{ number_format($stok) }} <span class="text-[10px] font-normal text-cocoa-700">{{ $bahan->satuan }}</span>
                                    </td>
                                    <td class="py-3 px-4 bg-amber-500/10 border-x border-gold-300">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($cabangs as $c)
                                                @php
                                                    $cStokItem = $c->stok_cabangs->where('nama_bahan', $bahan->nama_bahan)->first();
                                                    $valCStok = $cStokItem ? number_format($cStokItem->stok, 1) : '0';
                                                @endphp
                                                <span class="inline-flex items-center gap-1 bg-gold-100 border border-gold-400 text-cocoa-950 px-1.5 py-0.5 rounded text-[10px] font-bold shadow-2xs">
                                                    <span class="text-cocoa-800">{{ str_replace('Donat Menak ', '', $c->nama_cabang) }}:</span>
                                                    <strong class="text-amber-800 font-black">{{ $valCStok }}</strong> {{ $bahan->satuan }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-16 bg-white h-2 rounded-full overflow-hidden border border-gold-400">
                                                <div class="h-full bg-gradient-to-r from-amber-500 to-emerald-500 rounded-full" style="width: {{ $pct }}%;"></div>
                                            </div>
                                            <span class="text-[10px] shrink-0 {{ $statusColor }} px-2 py-0.5 rounded">{{ $statusStok }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 text-right">
                                        <button onclick="updateStokDapur({{ $bahan->id }}, '{{ $bahan->nama_bahan }}')"
                                            class="px-3 py-1 rounded-lg bg-cocoa-900 hover:bg-cocoa-950 font-bold text-gold-300 border border-gold-400 transition shadow-xs text-xs">
                                            <i class="fa-solid fa-plus mr-1"></i> Restock
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

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

        const serverCabangs = @json($cabangs ?? []);
        const bandungBranches = serverCabangs.map(c => ({
            id: c.id,
            name: c.nama_cabang,
            lat: parseFloat(c.latitude),
            lng: parseFloat(c.longitude),
            isPusat: c.id === 1
        }));

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
            let coordStringOpt = "";
            let waypointsOpt = [];
            try {
                const reqIds = selectedList.map(item => item.id);
                const aiResp = await fetch('{{ route("api.optimasi.rute") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ cabang_ids: reqIds })
                });
                const aiData = await aiResp.json();
                
                if (aiData.status !== 'success') {
                    console.error('AI error', aiData);
                    alert('Gagal mendapatkan rute dari AI TSP: ' + (aiData.message || 'Error'));
                    return;
                }
                
                waypointsOpt = aiData.rute_pengiriman.map(node => ({ 
                    lat: node.latitude, 
                    lng: node.longitude,
                    name: node.nama_cabang,
                    isPusat: node.id === 1,
                    weight: node.permintaan_kg || 50
                }));
                coordStringOpt = waypointsOpt.map(w => `${w.lng},${w.lat}`).join(';');
            } catch (err) {
                console.error(err);
                alert('Gagal menghubungi backend untuk TSP AI.');
                if (btn) {
                    btn.innerHTML = '<i class="fa-solid fa-truck-fast"></i> Kalkulasi Rute Optimal';
                    btn.disabled = false;
                }
                return;
            }

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

            try {
                const res = await fetch('{{ route("api.input.penjualan") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ cabang_id: cabangId, total_donat_terjual: donat })
                });
                const data = await res.json();
                alert(data.message || 'Berhasil disimpan!');
                window.location.reload();
            } catch (err) {
                alert('Gagal menyimpan laporan penjualan.');
            }
        }

        // --- UPDATE SISA STOK INLINE (TABEL KASIR) ---
        async function simpanStokInline(btn, cabangId, namaBahan, inputId) {
            const valInput = document.getElementById(inputId);
            if (!valInput) return;
            const stok = valInput.value;

            const icon = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            btn.disabled = true;

            try {
                const res = await fetch('{{ route("api.update.stok.cabang") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ cabang_id: cabangId, nama_bahan: namaBahan, stok: stok })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    alert('Stok ' + namaBahan + ' berhasil diupdate!');
                    valInput.classList.add('bg-emerald-100', 'border-emerald-500');
                    setTimeout(() => {
                        valInput.classList.remove('bg-emerald-100', 'border-emerald-500');
                    }, 1500);
                } else {
                    alert('Gagal: ' + data.message);
                }
            } catch (err) {
                alert('Gagal update stok bahan baku.');
            } finally {
                btn.innerHTML = icon;
                btn.disabled = false;
            }
        }

        // --- UPDATE STOK BAHAN BAKU / BARANG CABANG ---
        function pilihStokBahanCabang(selectEl) {
            const opt = selectEl.options[selectEl.selectedIndex];
            if (!opt || !opt.value) return;
            const sat = opt.getAttribute('data-satuan') || 'Unit';
            const stok = opt.getAttribute('data-stok') || '0';
            
            // Cari input nilai & satuan yang relevan (di form yang sama)
            const form = selectEl.closest('form');
            if (form) {
                const inputNilai = form.querySelector('input[type="number"]');
                const inputSatuan = form.querySelector('input[readonly]');
                if (inputNilai) inputNilai.value = stok;
                if (inputSatuan) inputSatuan.value = sat;
            }
        }

        async function submitUpdateStokCabang(e, cabangId) {
            e.preventDefault();
            const form = e.target;
            const selectEl = form.querySelector('select');
            const inputNilai = form.querySelector('input[type="number"]');
            const inputSatuan = form.querySelector('input[readonly]');

            if (!selectEl || !selectEl.value || !inputNilai) {
                alert('Silakan pilih bahan baku dan masukkan sisa stok di toko.');
                return;
            }

            const nama_bahan = selectEl.value;
            const stok = parseFloat(inputNilai.value);
            const satuan = inputSatuan ? inputSatuan.value : 'Unit';

            try {
                const res = await fetch('{{ route("api.update.stok.cabang") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        cabang_id: cabangId,
                        nama_bahan: nama_bahan,
                        stok: stok,
                        satuan: satuan
                    })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    alert('✅ ' + data.message);
                    window.location.reload();
                } else {
                    alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
                }
            } catch (err) {
                alert('Gagal memperbarui sisa stok cabang. Periksa koneksi atau coba lagi.');
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
        // =========================================================================
        // HANDLER MUSIM & AI REORDER POINT OWNER CABANG (Semua Bahan)
        // =========================================================================
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

            const statusText = document.getElementById('ai-rop-status-text');
            if (statusText) {
                if (faktor > 1.3) {
                    statusText.innerHTML = '🔥 <span class="text-amber-700 font-bold">Rekomendasi stok AI dinaikkan ' + Math.round((faktor-1)*100) + '% untuk antisipasi lonjakan pesanan!</span>';
                } else {
                    statusText.innerHTML = '✨ Stok di cabang Anda diprediksi aman untuk kondisi normal.';
                }
            }

            // Loop seluruh baris bahan baku dan update angka ROP
            document.querySelectorAll('.rop-item-row').forEach(row => {
                const baseRop = parseFloat(row.getAttribute('data-rop-base'));
                const sisaStok = parseFloat(row.getAttribute('data-stok'));
                const newRop = Math.round(baseRop * faktor * 10) / 10;
                
                row.querySelector('.rop-calculated-val').innerText = newRop;
                
                const badgeTd = row.querySelector('.rop-status-badge');
                if (sisaStok <= newRop) {
                    badgeTd.innerHTML = '<span class="px-2 py-0.5 bg-red-500 text-white rounded text-[10px]">ORDER SEKARANG</span>';
                } else if (sisaStok <= newRop * 1.25) {
                    badgeTd.innerHTML = '<span class="px-2 py-0.5 bg-amber-500 text-cocoa-900 rounded text-[10px]">WASPADA</span>';
                } else {
                    badgeTd.innerHTML = '<span class="px-2 py-0.5 bg-emerald-600 text-white rounded text-[10px]">AMAN</span>';
                }
            });
        }

        function isiOtomatisPermintaanItem(namaBahan, btnElement) {
            // Set dropdown bahan baku
            const selectBahan = document.getElementById('belanja_bahan');
            if (selectBahan) {
                for (let i = 0; i < selectBahan.options.length; i++) {
                    if (selectBahan.options[i].value === namaBahan) {
                        selectBahan.selectedIndex = i;
                        break;
                    }
                }
                selectBahan.dispatchEvent(new Event('change')); // trigger satuan update
            }
            
            // Set jumlah sesuai ROP target dari baris tabel
            const row = btnElement.closest('tr');
            if (row) {
                const ropVal = row.querySelector('.rop-calculated-val')?.innerText;
                const stokVal = parseFloat(row.getAttribute('data-stok'));
                const ropNum = parseFloat(ropVal);
                
                const jm = document.getElementById('belanja_jumlah');
                if (jm) {
                    // Saran order: isi ulang sampai menutupi ROP + sedikit buffer (bebas)
                    jm.value = Math.max((ropNum - stokVal) + (ropNum * 0.5), ropNum).toFixed(1);
                    jm.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    jm.focus();
                }
            }
        }

        document.getElementById('belanja_bahan')?.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            const sat = opt?.getAttribute('data-satuan') || 'Kg';
            const satEl = document.getElementById('belanja_satuan');
            if (satEl) satEl.value = sat;
        });

        let keranjangBelanjaItems = [];

        function renderKeranjangBelanja() {
            const tbody = document.getElementById('keranjangTableBody');
            const countEl = document.getElementById('keranjangCount');
            const submitCountEl = document.getElementById('keranjangSubmitCount');
            if (!tbody) return;

            if (countEl) countEl.textContent = keranjangBelanjaItems.length;
            if (submitCountEl) submitCountEl.textContent = keranjangBelanjaItems.length;

            if (keranjangBelanjaItems.length === 0) {
                tbody.innerHTML = `
                    <tr id="keranjangEmptyRow">
                        <td colSpan="4" class="py-4 text-center text-cocoa-700 font-medium italic text-xs">
                            Belum ada item di daftar pesanan. Pilih bahan dan klik "+ Tambah Item ke Daftar Pesanan".
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = '';
            keranjangBelanjaItems.forEach((item, index) => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gold-100 transition';
                tr.innerHTML = `
                    <td class="py-2 px-2 font-black text-cocoa-950">${item.nama_bahan}</td>
                    <td class="py-2 px-2 font-bold text-amber-700">${item.jumlah} <span class="text-[10px] text-cocoa-800">${item.satuan}</span></td>
                    <td class="py-2 px-2 text-cocoa-800 text-[11px] truncate max-w-[120px]">${item.keterangan || '-'}</td>
                    <td class="py-2 px-2 text-right">
                        <button type="button" onclick="hapusItemKeranjang(${index})" class="text-red-600 hover:text-red-800 p-1 title="Hapus">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function tambahItemKeranjang() {
            const bahanEl = document.getElementById('belanja_bahan');
            const jumlahEl = document.getElementById('belanja_jumlah');
            const satuanEl = document.getElementById('belanja_satuan');
            const keteranganEl = document.getElementById('belanja_keterangan');

            if (!bahanEl || !jumlahEl || !jumlahEl.value || parseFloat(jumlahEl.value) <= 0) {
                alert('Silakan masukkan jumlah pesanan yang valid (minimal 1).');
                if (jumlahEl) jumlahEl.focus();
                return;
            }

            const nama_bahan = bahanEl.value;
            const jumlah = parseFloat(jumlahEl.value);
            const satuan = satuanEl ? satuanEl.value : 'Kg';
            const keterangan = keteranganEl ? keteranganEl.value.trim() : '';

            // Cek apakah item sudah ada di keranjang, kalau ada tambahkan jumlahnya
            const existingIdx = keranjangBelanjaItems.findIndex(i => i.nama_bahan === nama_bahan);
            if (existingIdx !== -1) {
                keranjangBelanjaItems[existingIdx].jumlah += jumlah;
                if (keterangan && !keranjangBelanjaItems[existingIdx].keterangan.includes(keterangan)) {
                    keranjangBelanjaItems[existingIdx].keterangan += (keranjangBelanjaItems[existingIdx].keterangan ? '; ' : '') + keterangan;
                }
            } else {
                keranjangBelanjaItems.push({
                    nama_bahan,
                    jumlah,
                    satuan,
                    keterangan: keterangan || '-'
                });
            }

            renderKeranjangBelanja();
            if (jumlahEl) jumlahEl.value = '';
            if (keteranganEl) keteranganEl.value = '';
        }

        function hapusItemKeranjang(index) {
            keranjangBelanjaItems.splice(index, 1);
            renderKeranjangBelanja();
        }

        function resetKeranjang() {
            if (keranjangBelanjaItems.length > 0 && confirm('Kosongkan semua daftar pesanan?')) {
                keranjangBelanjaItems = [];
                renderKeranjangBelanja();
            }
        }

        async function submitPermintaanBelanja(e, cabangId) {
            e.preventDefault();
            
            // Jika keranjang masih kosong tapi input form ada isinya, otomatis tambahkan dulu ke keranjang
            const jumlahEl = document.getElementById('belanja_jumlah');
            if (keranjangBelanjaItems.length === 0 && jumlahEl && parseFloat(jumlahEl.value) > 0) {
                tambahItemKeranjang();
            }

            if (keranjangBelanjaItems.length === 0) {
                alert('⚠️ Daftar pesanan masih kosong! Pilih bahan dan klik "+ Tambah Item ke Daftar Pesanan" terlebih dahulu.');
                return;
            }

            if (!confirm(`Kirim ${keranjangBelanjaItems.length} item permintaan belanja ke Admin Pusat sekarang?`)) {
                return;
            }

            const btnSubmit = document.getElementById('btnSubmitKeranjang');
            const originalText = btnSubmit ? btnSubmit.innerHTML : '';
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim Permintaan...';
            }

            try {
                const res = await fetch('{{ route("api.input.permintaan.belanja") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        cabang_id: cabangId,
                        items: keranjangBelanjaItems
                    })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    alert('✅ ' + data.message);
                    keranjangBelanjaItems = [];
                    location.reload();
                } else {
                    alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
                    if (btnSubmit) {
                        btnSubmit.disabled = false;
                        btnSubmit.innerHTML = originalText;
                    }
                }
            } catch (err) {
                alert('Gagal mengirim permintaan belanja. Periksa koneksi atau coba lagi.');
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = originalText;
                }
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
                const itemKat = (card.getAttribute('data-kat') || '');
                if (kat === 'semua' || itemKat.includes(kat)) {
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
@endsection
