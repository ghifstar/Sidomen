import math
import statistics
from typing import List, Optional, Any, Dict
from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel

app = FastAPI(
    title="Donat Menak AI & Logistics Engine",
    description="Backend API berbasis Python FastAPI untuk Kalkulasi Reorder Point (ROP), peramalan permintaan, dan optimasi rute distribusi area Bandung.",
    version="1.0.0"
)

# Enable CORS agar aplikasi Laravel dan frontend dapat berkomunikasi tanpa kendala
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# --- Pydantic Schemas ---

class PenjualanHarian(BaseModel):
    total_donat_terjual: float
    sisa_stok_bahan: float

class RopRequest(BaseModel):
    lead_time: float
    safety_stock: float
    riwayat_penjualan: List[PenjualanHarian]

class CabangTujuan(BaseModel):
    id: int
    nama_cabang: str
    alamat: str
    latitude: float
    longitude: float
    permintaan_kg: Optional[float] = 50.0
    status_inventaris: Optional[str] = "Normal"

class RuteRequest(BaseModel):
    dapur_pusat: CabangTujuan
    cabang_tujuan: List[CabangTujuan]

# --- Helper Functions ---

def haversine_distance(lat1: float, lon1: float, lat2: float, lon2: float) -> float:
    """
    Menghitung jarak bumi sebenarnya (dalam Kilometer) antara dua koordinat GPS
    menggunakan rumus Haversine.
    """
    R = 6371.0 # Radius Bumi dalam Km
    dlat = math.radians(lat2 - lat1)
    dlon = math.radians(lon2 - lon1)
    a = (math.sin(dlat / 2)**2 +
         math.cos(math.radians(lat1)) * math.cos(math.radians(lat2)) * math.sin(dlon / 2)**2)
    c = 2 * math.atan2(math.sqrt(a), math.sqrt(1 - a))
    return R * c

# --- API Endpoints ---

@app.get("/api/status")
def get_status():
    return {
        "status": "online",
        "engine": "FastAPI v1.0 (Python 3.x)",
        "message": "Donat Menak AI Logistics Engine siap beroperasi."
    }

@app.post("/api/hitung-rop")
def hitung_rop(req: RopRequest):
    if not req.riwayat_penjualan:
        raise HTTPException(status_code=400, detail="Riwayat penjualan tidak boleh kosong.")

    # 1. Konversi penjualan donat ke konsumsi bahan baku (1 donat = 0.05 Kg premix terpakai)
    # Catatan: Data dari Laravel diurutkan DESC (indeks 0 adalah hari terbaru)
    donat_demands = [p.total_donat_terjual for p in req.riwayat_penjualan]
    premix_demands = [round(val * 0.05, 3) for val in donat_demands]

    # 2. Kalkulasi statistik dasar
    avg_donat = sum(donat_demands) / len(donat_demands)
    d_avg_premix = sum(premix_demands) / len(premix_demands)
    std_dev_premix = statistics.stdev(premix_demands) if len(premix_demands) > 1 else 0.0

    # 3. Kalkulasi Reorder Point (ROP) -> ROP = (d * L) + SS
    rop_premix = round((d_avg_premix * req.lead_time) + req.safety_stock, 2)
    current_stock = round(req.riwayat_penjualan[0].sisa_stok_bahan, 2)

    # 4. Tentukan status inventaris dan rekomendasi order
    if current_stock <= req.safety_stock:
        status_code = "KRITIS"
        analisis_text = f"KRITIS: Sisa stok saat ini ({current_stock} Kg) berada di bawah/setara Safety Stock ({req.safety_stock} Kg). Bahaya stockout! Segera reorder dari Dapur Pusat."
    elif current_stock <= rop_premix * 1.15:
        status_code = "WASPADA"
        analisis_text = f"WASPADA: Sisa stok saat ini ({current_stock} Kg) telah menyentuh/mendekati batas Reorder Point ({rop_premix} Kg). Disarankan mengajukan pengiriman logistik baru."
    else:
        status_code = "AMAN"
        analisis_text = f"AMAN: Sisa stok ({current_stock} Kg) masih jauh di atas Reorder Point ({rop_premix} Kg). Operasional cabang berjalan stabil."

    # 5. Prediksi / Peramalan tren permintaan 7 hari ke depan dengan regresi linier sederhana
    # Urutkan secara kronologis untuk regresi (hari ke-0 sampai hari ke-N)
    chronological_donat = list(reversed(donat_demands))
    n = len(chronological_donat)
    x = list(range(n))
    mean_x = sum(x) / n
    mean_y = sum(chronological_donat) / n
    
    numerator = sum((x[i] - mean_x) * (chronological_donat[i] - mean_y) for i in range(n))
    denominator = sum((x[i] - mean_x)**2 for i in range(n))
    slope = numerator / denominator if denominator != 0 else 0
    intercept = mean_y - (slope * mean_x)

    prediksi_7_hari = []
    for future_i in range(n, n + 7):
        pred_val = max(0, intercept + (slope * future_i))
        prediksi_7_hari.append(round(pred_val, 1))

    # Estimasi kuantitas pemesanan ekonomis (EOQ approximation untuk 14 hari konsumsi)
    saran_order_kg = round(max((d_avg_premix * 14) + req.safety_stock - current_stock, 50.0), 1)

    return {
        "status": "success",
        "status_code": status_code,
        "analisis": analisis_text,
        "kalkulasi": {
            "rata_rata_donat_harian": round(avg_donat, 1),
            "rata_rata_premix_harian_kg": round(d_avg_premix, 2),
            "std_deviasi_kg": round(std_dev_premix, 2),
            "lead_time_hari": req.lead_time,
            "safety_stock_kg": req.safety_stock,
            "reorder_point_kg": rop_premix,
            "sisa_stok_saat_ini_kg": current_stock,
            "saran_order_kg": saran_order_kg
        },
        "grafik_tren": {
            "label_hari": [f"H-{i}" for i in range(n - 1, -1, -1)],
            "data_penjualan_donat": chronological_donat,
            "data_premix_terpakai": [round(val * 0.05, 2) for val in chronological_donat],
            "prediksi_7_hari_donat": prediksi_7_hari
        }
    }

@app.post("/api/rute-distribusi")
def optimasi_rute(req: RuteRequest):
    """
    Optimasi rute pengiriman logistik menggunakan algoritma Nearest Neighbor TSP
    (Traveling Salesperson Problem) berbasis jarak permukaan bumi Haversine.
    """
    pusat = req.dapur_pusat
    tujuan_list = req.cabang_tujuan

    if not tujuan_list:
        return {
            "status": "success",
            "total_jarak_km": 0,
            "estimasi_waktu_menit": 0,
            "rute_pengiriman": [pusat.dict()],
            "pesan": "Tidak ada cabang tujuan yang dipilih."
        }

    unvisited = list(tujuan_list)
    current_node = pusat
    rute_ordered = [pusat.dict()]
    total_jarak = 0.0
    langkah_detail = []

    step_num = 1
    while unvisited:
        # Cari cabang terdekat dari node saat ini
        best_next = None
        min_dist = float('inf')
        
        for cand in unvisited:
            dist = haversine_distance(
                current_node.latitude, current_node.longitude,
                cand.latitude, cand.longitude
            )
            if dist < min_dist:
                min_dist = dist
                best_next = cand
        
        # Pindah ke node terdekat
        total_jarak += min_dist
        rute_ordered.append(best_next.dict())
        unvisited.remove(best_next)
        
        # Hitung waktu tempuh antar titik (asumsi kecepatan rata-rata dalam kota Bandung = 24 km/jam)
        waktu_jalan_menit = round((min_dist / 24.0) * 60)
        
        langkah_detail.append({
            "urutan": step_num,
            "dari": current_node.nama_cabang,
            "ke": best_next.nama_cabang,
            "jarak_km": round(min_dist, 2),
            "estimasi_jalan_menit": waktu_jalan_menit,
            "kebutuhan_logistik_kg": best_next.permintaan_kg,
            "status": best_next.status_inventaris
        })
        
        current_node = best_next
        step_num += 1

    # Rute kembali ke Dapur Pusat (Round trip)
    dist_back = haversine_distance(
        current_node.latitude, current_node.longitude,
        pusat.latitude, pusat.longitude
    )
    total_jarak += dist_back
    rute_ordered.append(pusat.dict())
    
    langkah_detail.append({
        "urutan": step_num,
        "dari": current_node.nama_cabang,
        "ke": pusat.nama_cabang + " (Kembali)",
        "jarak_km": round(dist_back, 2),
        "estimasi_jalan_menit": round((dist_back / 24.0) * 60),
        "kebutuhan_logistik_kg": 0,
        "status": "Selesai"
    })

    # Total waktu = perjalanan jalan + 15 menit bongkar muat per titik cabang
    total_bongkar_muat_menit = len(tujuan_list) * 15
    total_waktu_menit = round((total_jarak / 24.0) * 60) + total_bongkar_muat_menit

    return {
        "status": "success",
        "total_jarak_km": round(total_jarak, 2),
        "estimasi_waktu_menit": total_waktu_menit,
        "detail_waktu": {
            "waktu_tempuh_jalan_menit": round((total_jarak / 24.0) * 60),
            "waktu_bongkar_muat_menit": total_bongkar_muat_menit
        },
        "rute_pengiriman": rute_ordered,
        "langkah_detail": langkah_detail,
        "hemat_jarak_estimasi_km": round(total_jarak * 0.28, 2), # Estimasi penghematan dibanding rute acak
        "pesan": f"Optimasi TSP berhasil: 1 Dapur Pusat -> {len(tujuan_list)} Cabang -> Kembali ke Pusat ({round(total_jarak, 1)} Km)."
    }
