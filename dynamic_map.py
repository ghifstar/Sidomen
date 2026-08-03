import sys
import re

def patch_file(file_path):
    with open(file_path, "r", encoding="utf-8") as f:
        content = f.read()

    target_block = re.search(r'const bandungBranches = \[\s*\{ id: 1, name: \'Dapur Pusat.*?\s*\];', content, re.DOTALL)
    
    if target_block:
        replacement = """const serverCabangs = @json($cabangs ?? []);
        const bandungBranches = serverCabangs.map(c => ({
            id: c.id,
            name: c.nama_cabang,
            lat: parseFloat(c.latitude),
            lng: parseFloat(c.longitude),
            isPusat: c.id === 1
        }));"""
        content = content[:target_block.start()] + replacement + content[target_block.end():]
        with open(file_path, "w", encoding="utf-8") as f:
            f.write(content)
        print(f"Patched {file_path} successfully.")
    else:
        print(f"Not found in {file_path}")

patch_file("resources/views/layouts/app.blade.php")
patch_file("resources/views/dashboards/petugas_pusat.blade.php")
