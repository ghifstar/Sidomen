import sys

def patch_file(file_path):
    with open(file_path, "r", encoding="utf-8") as f:
        content = f.read()

    target = """            // 2. URUTAN TEROPTIMASI AI TSP (Emas): Nearest-Neighbor loop geografis terpendek
            let optList = [...selectedList];
            optList.sort((a, b) => a.lng - b.lng);
            const waypointsOpt = [pusat, ...optList, pusat];
            const coordStringOpt = waypointsOpt.map(w => `${w.lng},${w.lat}`).join(';');"""

    replacement = """            // 2. URUTAN TEROPTIMASI AI TSP (Emas): Nearest-Neighbor loop geografis terpendek
            let coordStringOpt = "";
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
                
                const waypointsOpt = aiData.rute_pengiriman.map(node => ({ lat: node.latitude, lng: node.longitude }));
                coordStringOpt = waypointsOpt.map(w => `${w.lng},${w.lat}`).join(';');
            } catch (err) {
                console.error(err);
                alert('Gagal menghubungi backend untuk TSP AI.');
                if (btn) {
                    btn.innerHTML = '<i class="fa-solid fa-truck-fast"></i> Kalkulasi Rute Optimal';
                    btn.disabled = false;
                }
                return;
            }"""

    if target in content:
        content = content.replace(target, replacement)
        with open(file_path, "w", encoding="utf-8") as f:
            f.write(content)
        print(f"Replaced in {file_path} successfully.")
    else:
        print(f"Target not found in {file_path}.")

patch_file("resources/views/layouts/app.blade.php")
patch_file("resources/views/dashboards/petugas_pusat.blade.php")
