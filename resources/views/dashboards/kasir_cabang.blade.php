@extends('layouts.app')
@section('content')
    <!-- HEADER -->
    <div class="bg-gold-200 border-b-2 border-gold-400 px-6 py-4 shadow-md rounded-2xl mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-black text-cocoa-950 uppercase">🏪 Dashboard Kasir Cabang – Operasional Laporan Keuangan & Sisa Bahan</h2>
            <p class="text-xs font-semibold text-cocoa-800">Wewenang menginput laporan keuangan harian (cash/cashless & pengeluaran) dan laporan sisa bahan harian toko cabang.</p>
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
                                    <button type="button" onclick="filterPosKategori('1-dozen')" class="pos-kat-btn px-3 py-1 rounded-lg bg-gold-200 text-cocoa-950 border border-gold-400 hover:bg-gold-300" data-kat="1-dozen">1 Dozen (12 Pcs)</button>
                                    <button type="button" onclick="filterPosKategori('half-dozen')" class="pos-kat-btn px-3 py-1 rounded-lg bg-gold-200 text-cocoa-950 border border-gold-400 hover:bg-gold-300" data-kat="half-dozen">1/2 Dozen (6 Pcs)</button>
                                    <button type="button" onclick="filterPosKategori('mix')" class="pos-kat-btn px-3 py-1 rounded-lg bg-gold-200 text-cocoa-950 border border-gold-400 hover:bg-gold-300" data-kat="mix">Mix Series</button>
                                    <button type="button" onclick="filterPosKategori('choco')" class="pos-kat-btn px-3 py-1 rounded-lg bg-gold-200 text-cocoa-950 border border-gold-400 hover:bg-gold-300" data-kat="choco">Choco Series</button>
                                    <button type="button" onclick="filterPosKategori('minuman')" class="pos-kat-btn px-3 py-1 rounded-lg bg-gold-200 text-cocoa-950 border border-gold-400 hover:bg-gold-300" data-kat="minuman">Minuman</button>
                                </div>
                            </div>

                            <!-- GRID KATALOG MENU RESMI DONAT MENAK -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-h-[520px] overflow-y-auto custom-scrollbar pr-1" id="pos-menu-grid">
                                <!-- MENU 1: 1 DOZEN MIX SERIES -->
                                <div class="pos-item-card p-3.5 rounded-xl bg-white/90 hover:bg-gold-100 border-2 border-gold-400 transition cursor-pointer flex flex-col justify-between shadow-sm group"
                                     data-kat="1-dozen mix" onclick="addToCart('1 Dozen Mix Series', 98000, '1-dozen mix', 12)">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-8 h-8 rounded-lg bg-purple-100 text-purple-800 flex items-center justify-center text-sm font-bold border border-purple-300 group-hover:scale-110 transition">
                                                📦
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-purple-200 text-purple-950 text-[10px] font-black">1 Dozen (12 Pcs)</span>
                                        </div>
                                        <h4 class="text-xs font-black text-cocoa-950 leading-tight">1 Dozen Mix Series</h4>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-gold-300 pt-2">
                                        <span class="text-xs font-extrabold text-amber-800">Rp 98.000</span>
                                        <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 flex items-center justify-center text-xs font-black shadow-sm">+</span>
                                    </div>
                                </div>

                                <!-- MENU 2: 1/2 DOZEN MIX SERIES -->
                                <div class="pos-item-card p-3.5 rounded-xl bg-white/90 hover:bg-gold-100 border-2 border-gold-400 transition cursor-pointer flex flex-col justify-between shadow-sm group"
                                     data-kat="half-dozen mix" onclick="addToCart('1/2 Dozen Mix Series', 52000, 'half-dozen mix', 6)">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-900 flex items-center justify-center text-sm font-bold border border-amber-300 group-hover:scale-110 transition">
                                                🎁
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-gold-200 text-cocoa-950 text-[10px] font-black">1/2 Dozen (6 Pcs)</span>
                                        </div>
                                        <h4 class="text-xs font-black text-cocoa-950 leading-tight">1/2 Dozen Mix Series</h4>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-gold-300 pt-2">
                                        <span class="text-xs font-extrabold text-amber-800">Rp 52.000</span>
                                        <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 flex items-center justify-center text-xs font-black shadow-sm">+</span>
                                    </div>
                                </div>

                                <!-- MENU 3: 1 DOZEN MILLENNIAL SERIES -->
                                <div class="pos-item-card p-3.5 rounded-xl bg-white/90 hover:bg-gold-100 border-2 border-gold-400 transition cursor-pointer flex flex-col justify-between shadow-sm group"
                                     data-kat="1-dozen millennial" onclick="addToCart('1 Dozen Millennial Series', 105000, '1-dozen millennial', 12)">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-8 h-8 rounded-lg bg-red-100 text-red-800 flex items-center justify-center text-sm font-bold border border-red-300 group-hover:scale-110 transition">
                                                ✨
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-purple-200 text-purple-950 text-[10px] font-black">1 Dozen (12 Pcs)</span>
                                        </div>
                                        <h4 class="text-xs font-black text-cocoa-950 leading-tight">1 Dozen Millennial Series</h4>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-gold-300 pt-2">
                                        <span class="text-xs font-extrabold text-amber-800">Rp 105.000</span>
                                        <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 flex items-center justify-center text-xs font-black shadow-sm">+</span>
                                    </div>
                                </div>

                                <!-- MENU 4: 1 DOZEN CHOCO SERIES -->
                                <div class="pos-item-card p-3.5 rounded-xl bg-white/90 hover:bg-gold-100 border-2 border-gold-400 transition cursor-pointer flex flex-col justify-between shadow-sm group"
                                     data-kat="1-dozen choco" onclick="addToCart('1 Dozen Choco Series', 102000, '1-dozen choco', 12)">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-8 h-8 rounded-lg bg-amber-200 text-amber-900 flex items-center justify-center text-sm font-bold border border-amber-400 group-hover:scale-110 transition">
                                                🍫
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-purple-200 text-purple-950 text-[10px] font-black">1 Dozen (12 Pcs)</span>
                                        </div>
                                        <h4 class="text-xs font-black text-cocoa-950 leading-tight">1 Dozen Choco Series</h4>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-gold-300 pt-2">
                                        <span class="text-xs font-extrabold text-amber-800">Rp 102.000</span>
                                        <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 flex items-center justify-center text-xs font-black shadow-sm">+</span>
                                    </div>
                                </div>

                                <!-- MENU 5: 1/2 DOZEN VANILLA SERIES -->
                                <div class="pos-item-card p-3.5 rounded-xl bg-white/90 hover:bg-gold-100 border-2 border-gold-400 transition cursor-pointer flex flex-col justify-between shadow-sm group"
                                     data-kat="half-dozen vanilla" onclick="addToCart('1/2 Dozen Vanilla Series', 54000, 'half-dozen vanilla', 6)">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center text-sm font-bold border border-amber-300 group-hover:scale-110 transition">
                                                🍦
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-gold-200 text-cocoa-950 text-[10px] font-black">1/2 Dozen (6 Pcs)</span>
                                        </div>
                                        <h4 class="text-xs font-black text-cocoa-950 leading-tight">1/2 Dozen Vanilla Series</h4>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-gold-300 pt-2">
                                        <span class="text-xs font-extrabold text-amber-800">Rp 54.000</span>
                                        <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 flex items-center justify-center text-xs font-black shadow-sm">+</span>
                                    </div>
                                </div>

                                <!-- MENU 6: 1/2 DOZEN CHOCO SERIES -->
                                <div class="pos-item-card p-3.5 rounded-xl bg-white/90 hover:bg-gold-100 border-2 border-gold-400 transition cursor-pointer flex flex-col justify-between shadow-sm group"
                                     data-kat="half-dozen choco" onclick="addToCart('1/2 Dozen Choco Series', 54000, 'half-dozen choco', 6)">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-8 h-8 rounded-lg bg-amber-200 text-amber-900 flex items-center justify-center text-sm font-bold border border-amber-400 group-hover:scale-110 transition">
                                                🍫
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-gold-200 text-cocoa-950 text-[10px] font-black">1/2 Dozen (6 Pcs)</span>
                                        </div>
                                        <h4 class="text-xs font-black text-cocoa-950 leading-tight">1/2 Dozen Choco Series</h4>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-gold-300 pt-2">
                                        <span class="text-xs font-extrabold text-amber-800">Rp 54.000</span>
                                        <span class="w-6 h-6 rounded-full bg-cocoa-900 text-gold-300 flex items-center justify-center text-xs font-black shadow-sm">+</span>
                                    </div>
                                </div>

                                <!-- MINUMAN 1: KOPI SUSU GULA AREN MENAK -->
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

                                <!-- MINUMAN 2: ES TEH LYCHEE SEGAR -->
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
                            <p class="text-xs text-cocoa-800 font-medium">Catat penjualan donat & update sisa stok seluruh bahan baku cabang</p>
                        </div>

                        <!-- FORM 1: INPUT PENJUALAN HARIAN & PREMIX -->
                        <form id="formInputPenjualan" onsubmit="submitLaporanCabang(event, {{ $myCabang->id }})" class="space-y-3">
                            <div>
                                <label class="block text-xs font-black text-cocoa-950 mb-1">Total Penjualan Donat Hari Ini (Pcs):</label>
                                <input type="number" id="input_donat" required min="0" placeholder="Contoh: 320"
                                    class="w-full px-4 py-2 rounded-xl bg-white border-2 border-gold-400 text-cocoa-950 font-bold text-sm focus:border-amber-600 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-black text-cocoa-950 mb-1">Total Penjualan Donat Hari Ini (Pcs):</label>
                                <input type="number" id="input_donat" required min="0" placeholder="Contoh: 320"
                                    class="w-full px-4 py-2 rounded-xl bg-white border-2 border-gold-400 text-cocoa-950 font-bold text-sm focus:border-amber-600 focus:outline-none">
                            </div>

                            <button type="submit"
                                class="w-full py-2.5 px-4 rounded-xl bg-cocoa-900 hover:bg-cocoa-950 text-gold-300 font-black text-xs tracking-wide shadow transition flex items-center justify-center gap-2 border-2 border-gold-400">
                                <i class="fa-solid fa-cloud-arrow-up text-sm"></i> Simpan Penjualan Donat Harian
                            </button>
                        </form>

                        <!-- PENCATATAN SISA STOK TOKO (SEMUA BAHAN BAKU / BARANG) -->
                        <div class="pt-3 border-t-2 border-gold-400">
                            <h4 class="text-xs font-black uppercase text-cocoa-950 mb-2 flex items-center gap-1.5">
                                <i class="fa-solid fa-boxes-packing text-amber-700"></i> Update Sisa Stok Bahan Baku Cabang:
                            </h4>
                            <div class="overflow-x-auto max-h-96 overflow-y-auto custom-scrollbar border-2 border-gold-400 rounded-xl bg-white/90">
                                <table class="w-full text-left border-collapse text-xs">
                                    <thead>
                                        <tr class="bg-cocoa-900 text-gold-300 font-black uppercase sticky top-0 z-10 border-b border-cocoa-950">
                                            <th class="py-2.5 px-3">Bahan Baku</th>
                                            <th class="py-2.5 px-3">Sisa Stok</th>
                                            <th class="py-2.5 px-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gold-200 font-semibold text-cocoa-950">
                                        @foreach($bahanBakus->groupBy('kategori') as $kategori => $items)
                                            <tr class="bg-gold-100"><td colspan="3" class="px-3 py-1.5 text-[10px] font-black text-amber-800 uppercase tracking-widest border-b border-gold-300">📦 {{ $kategori }}</td></tr>
                                            @foreach($items as $bb)
                                                @php
                                                    $stokItem = $myCabang->stok_cabangs->where('nama_bahan', $bb->nama_bahan)->first();
                                                    $valStokCabang = $stokItem ? $stokItem->stok : 0;
                                                @endphp
                                                <tr class="hover:bg-gold-100/50 transition">
                                                    <td class="py-2.5 px-3 font-black">{{ $bb->nama_bahan }}</td>
                                                    <td class="py-2.5 px-3">
                                                        <div class="flex items-center gap-1">
                                                            <input type="number" id="stok_inline_{{ Str::slug($bb->nama_bahan) }}" min="0" step="0.1" value="{{ $valStokCabang }}"
                                                                class="w-16 px-2 py-1 rounded bg-white border border-gold-400 text-cocoa-950 font-bold focus:outline-none text-center">
                                                            <span class="text-[10px] text-cocoa-800">{{ $bb->satuan }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="py-2.5 px-3 text-center">
                                                        <button type="button" onclick="simpanStokInline(this, {{ $myCabang->id }}, '{{ $bb->nama_bahan }}', 'stok_inline_{{ Str::slug($bb->nama_bahan) }}')"
                                                            class="px-2.5 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-cocoa-950 font-black text-[10px] transition shadow flex items-center justify-center gap-1 mx-auto">
                                                            <i class="fa-solid fa-floppy-disk"></i> Simpan
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


@endsection
