import re
import os

filepath = 'resources/views/welcome.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    lines = f.readlines()

def write_view(name, content):
    path = f'resources/views/dashboards/{name}.blade.php'
    with open(path, 'w', encoding='utf-8') as f:
        f.write("@extends('layouts.app')\n")
        f.write("@section('content')\n")
        f.write(content)
        f.write("@endsection\n")

admin_lines = []
kasir_lines = []
owner_lines = []
petugas_lines = []
modals_and_scripts = []

current_section = None
for i, line in enumerate(lines):
    if "@if($activeRole == 'admin_pusat' || $activeRole == 'koordinator_logistik')" in line and current_section is None and i > 200:
        current_section = 'admin'
        continue
    elif "@if($activeRole == 'kasir_cabang' || $activeRole == 'petugas_cabang')" in line and current_section == 'admin':
        current_section = 'kasir'
        continue
    elif "@if($activeRole == 'owner_cabang')" in line and current_section == 'kasir':
        current_section = 'owner'
        continue
    elif "@if($activeRole == 'petugas_pusat')" in line and current_section == 'owner':
        current_section = 'petugas'
        continue
    elif "<!-- ================================================================================== -->" in line and "MODAL GLOBAL" in lines[i+1] and current_section == 'petugas':
        current_section = 'scripts'
        
    if current_section == 'admin':
        admin_lines.append(line)
    elif current_section == 'kasir':
        kasir_lines.append(line)
    elif current_section == 'owner':
        owner_lines.append(line)
    elif current_section == 'petugas':
        petugas_lines.append(line)
    elif current_section == 'scripts':
        modals_and_scripts.append(line)

# Remove the trailing @endif from each section
def clean_section(lines):
    # Reverse find @endif and remove it
    for j in range(len(lines)-1, -1, -1):
        if "@endif" in lines[j]:
            lines.pop(j)
            break
    return "".join(lines)

write_view('admin_pusat', clean_section(admin_lines))
write_view('kasir_cabang', clean_section(kasir_lines))
write_view('owner_cabang', clean_section(owner_lines))
write_view('petugas_pusat', clean_section(petugas_lines))

with open('resources/views/dashboards/scripts.blade.php', 'w', encoding='utf-8') as f:
    f.write("".join(modals_and_scripts))

print("Views split successfully.")
