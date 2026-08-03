import sys
import re

def patch_file(file_path):
    with open(file_path, "r", encoding="utf-8") as f:
        content = f.read()

    target1 = """        const serverCabangs = @json($cabangs ?? []);
        const bandungBranches = serverCabangs.map(c => ({"""
    
    rep1 = """        const serverCabangs = @json($cabangs ?? []);
        const dapurPusat = @json($dapurPusat ?? null);
        let allCabangData = serverCabangs;
        if(dapurPusat) { allCabangData = [dapurPusat, ...serverCabangs]; }
        
        const bandungBranches = allCabangData.map(c => ({"""

    target2 = """const pusat = bandungBranches[0];"""
    rep2 = """const pusat = bandungBranches.find(b => b.isPusat) || bandungBranches[0];"""

    modified = False
    if target1 in content:
        content = content.replace(target1, rep1)
        modified = True
    if target2 in content:
        content = content.replace(target2, rep2)
        modified = True

    if modified:
        with open(file_path, "w", encoding="utf-8") as f:
            f.write(content)
        print(f"Patched {file_path} successfully.")
    else:
        print(f"Targets not found in {file_path}.")

patch_file("resources/views/layouts/app.blade.php")
patch_file("resources/views/dashboards/petugas_pusat.blade.php")
