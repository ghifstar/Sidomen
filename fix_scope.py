import sys

def patch_file(file_path):
    with open(file_path, "r", encoding="utf-8") as f:
        content = f.read()

    target1 = """            // 2. URUTAN TEROPTIMASI AI TSP (Emas): Nearest-Neighbor loop geografis terpendek
            let coordStringOpt = "";
            try {"""
    
    rep1 = """            // 2. URUTAN TEROPTIMASI AI TSP (Emas): Nearest-Neighbor loop geografis terpendek
            let coordStringOpt = "";
            let waypointsOpt = [];
            try {"""

    target2 = """                const waypointsOpt = aiData.rute_pengiriman.map(node => ({ lat: node.latitude, lng: node.longitude }));"""
    
    rep2 = """                waypointsOpt = aiData.rute_pengiriman.map(node => ({ 
                    lat: node.latitude, 
                    lng: node.longitude,
                    name: node.nama_cabang,
                    isPusat: node.id === 1,
                    weight: node.permintaan_kg || 50
                }));"""

    if target1 in content and target2 in content:
        content = content.replace(target1, rep1).replace(target2, rep2)
        with open(file_path, "w", encoding="utf-8") as f:
            f.write(content)
        print(f"Patched {file_path} successfully.")
    else:
        print(f"Targets not found in {file_path}.")

patch_file("resources/views/layouts/app.blade.php")
patch_file("resources/views/dashboards/petugas_pusat.blade.php")
