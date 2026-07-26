import os

def fix_view(filepath, title, desc, needs_select=False):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    header = f"""@extends('layouts.app')
@section('content')
    <!-- HEADER -->
    <div class="bg-gold-200 border-b-2 border-gold-400 px-6 py-4 shadow-md rounded-2xl mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-black text-cocoa-950 uppercase">{title}</h2>
            <p class="text-xs font-semibold text-cocoa-800">{desc}</p>
        </div>
"""
    if needs_select:
        header += """        <div class="flex items-center gap-2">
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
"""
    header += "    </div>\n\n"

    # Find the first <!-- ==== 
    split_idx = content.find("<!-- ===")
    if split_idx != -1:
        new_content = header + content[split_idx:]
    else:
        new_content = content
        
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(new_content)

fix_view('resources/views/dashboards/admin_pusat.blade.php', 
         '👑 Dashboard Admin Pusat – Logistik & Optimasi Rute', 
         'Wewenang memproses pengajuan permintaan belanja dari seluruh cabang dan menghitung optimasi rute pengiriman.', 
         False)

fix_view('resources/views/dashboards/kasir_cabang.blade.php', 
         '🏪 Dashboard Kasir Cabang – Operasional Laporan Keuangan & Sisa Bahan', 
         'Wewenang menginput laporan keuangan harian (cash/cashless & pengeluaran) dan laporan sisa bahan harian toko cabang.', 
         True)

fix_view('resources/views/dashboards/owner_cabang.blade.php', 
         '👔 Dashboard Owner Cabang – Analitik Eksekutif, AI ROP & Permintaan Belanja', 
         'Wewenang membaca laporan keuangan harian & rekap bulanan, status bahan baku, prediksi AI Reorder Point berdasarkan event/libur, dan mengajukan permintaan belanja ke pusat.', 
         True)

fix_view('resources/views/dashboards/petugas_pusat.blade.php', 
         '🏭 Dashboard Petugas Pusat – Dapur Lodaya', 
         'Wewenang memantau ketersediaan stok bahan pokok dan glaze di Dapur Pusat Lodaya.', 
         False)

print("Dashboards headers fixed again.")
