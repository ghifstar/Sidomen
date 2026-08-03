import re
import sys

file_path = "ai-engine/main.py"
with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# Make sure urllib.request and urllib.error and json are imported
imports_block = "import json\nimport urllib.request\nimport urllib.error\nimport math"
if "import urllib.request" not in content:
    content = content.replace("import math", imports_block)

# Add the get_osrm_matrix function if not exists
osrm_func = """
def get_osrm_matrix(nodes):
    # OSRM expects: lon,lat;lon,lat
    coords_str = ";".join([f"{n.longitude},{n.latitude}" for n in nodes])
    url = f"https://router.project-osrm.org/table/v1/driving/{coords_str}?annotations=distance,duration"
    
    try:
        req = urllib.request.Request(url, headers={'User-Agent': 'MenakBot/1.0'})
        with urllib.request.urlopen(req) as resp:
            data = json.loads(resp.read().decode())
            if data.get("code") == "Ok":
                return data["distances"], data["durations"]
    except Exception as e:
        print("OSRM Matrix Error:", e)
    return None, None
"""
if "def get_osrm_matrix" not in content:
    content = content.replace("# --- Helper Functions ---", "# --- Helper Functions ---\n" + osrm_func)

# Replace optimasi_rute function
target = re.search(r'@app\.post\("/api/rute-distribusi"\)\ndef optimasi_rute\(req: RuteRequest\):.*?(?=if __name__ == "__main__":)', content, re.DOTALL)

if not target:
    print("optimasi_rute not found")
    sys.exit(1)

replacement = """@app.post("/api/rute-distribusi")
def optimasi_rute(req: RuteRequest):
    \"\"\"
    Optimasi rute pengiriman logistik menggunakan algoritma Nearest Neighbor TSP
    berbasis jarak jalan raya sesungguhnya (Algoritma Dijkstra) dari OSRM Matrix API.
    \"\"\"
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

    all_nodes = [pusat] + tujuan_list
    distances, durations = get_osrm_matrix(all_nodes)

    unvisited_indices = list(range(1, len(all_nodes)))
    current_idx = 0
    rute_ordered = [pusat.dict()]
    total_jarak = 0.0
    langkah_detail = []
    
    step_num = 1
    while unvisited_indices:
        best_next_idx = None
        min_dist = float('inf')
        min_duration = 0
        
        for cand_idx in unvisited_indices:
            if distances and distances[current_idx][cand_idx] is not None:
                dist = distances[current_idx][cand_idx] / 1000.0 # meter ke km
                duration = durations[current_idx][cand_idx] / 60.0 # detik ke menit
            else:
                dist = haversine_distance(
                    all_nodes[current_idx].latitude, all_nodes[current_idx].longitude,
                    all_nodes[cand_idx].latitude, all_nodes[cand_idx].longitude
                )
                duration = (dist / 24.0) * 60
                
            if dist < min_dist:
                min_dist = dist
                best_next_idx = cand_idx
                min_duration = duration
                
        total_jarak += min_dist
        best_next = all_nodes[best_next_idx]
        rute_ordered.append(best_next.dict())
        unvisited_indices.remove(best_next_idx)
        
        waktu_jalan_menit = round(min_duration)
        
        langkah_detail.append({
            "urutan": step_num,
            "dari": all_nodes[current_idx].nama_cabang,
            "ke": best_next.nama_cabang,
            "jarak_km": round(min_dist, 2),
            "estimasi_jalan_menit": waktu_jalan_menit,
            "kebutuhan_logistik_kg": best_next.permintaan_kg,
            "status": best_next.status_inventaris
        })
        
        current_idx = best_next_idx
        step_num += 1

    if distances and distances[current_idx][0] is not None:
        dist_back = distances[current_idx][0] / 1000.0
        duration_back = durations[current_idx][0] / 60.0
    else:
        dist_back = haversine_distance(
            all_nodes[current_idx].latitude, all_nodes[current_idx].longitude,
            pusat.latitude, pusat.longitude
        )
        duration_back = (dist_back / 24.0) * 60
        
    total_jarak += dist_back
    rute_ordered.append(pusat.dict())
    
    langkah_detail.append({
        "urutan": step_num,
        "dari": all_nodes[current_idx].nama_cabang,
        "ke": pusat.nama_cabang + " (Kembali)",
        "jarak_km": round(dist_back, 2),
        "estimasi_jalan_menit": round(duration_back),
        "kebutuhan_logistik_kg": 0,
        "status": "Selesai"
    })

    total_bongkar_muat_menit = len(tujuan_list) * 15
    total_waktu_menit = round(sum(step["estimasi_jalan_menit"] for step in langkah_detail)) + total_bongkar_muat_menit

    return {
        "status": "success",
        "total_jarak_km": round(total_jarak, 2),
        "estimasi_waktu_menit": total_waktu_menit,
        "detail_waktu": {
            "waktu_tempuh_jalan_menit": round(sum(step["estimasi_jalan_menit"] for step in langkah_detail)),
            "waktu_bongkar_muat_menit": total_bongkar_muat_menit
        },
        "rute_pengiriman": rute_ordered,
        "langkah_detail": langkah_detail,
        "pesan": f"Optimasi TSP (OSRM Dijkstra) berhasil: 1 Dapur Pusat -> {len(tujuan_list)} Cabang -> Kembali ke Pusat ({round(total_jarak, 1)} Km)."
    }

"""

content = content[:target.start()] + replacement + content[target.end():]

with open(file_path, "w", encoding="utf-8") as f:
    f.write(content)
print("main.py patched successfully.")
