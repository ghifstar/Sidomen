import re

file_path = "database/seeders/DatabaseSeeder.php"
with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

new_cabangs = """        $cabangs = [
            ['nama_cabang' => 'Toko Pusat', 'alamat' => 'Jl. Ibu ganirah no. 70 (belakang unjani), Cibeber, Cimahi Selatan', 'latitude' => -6.9171, 'longitude' => 107.5458],
            ['nama_cabang' => 'Toko Batujajar', 'alamat' => 'Jl. Raya Batujajar No. 330, Kab. Bandung Barat', 'latitude' => -6.9183, 'longitude' => 107.4914],
            ['nama_cabang' => 'Toko Patrol', 'alamat' => 'Jl. Kolonel Matsuri No. 177, Patrol, Parongpong', 'latitude' => -6.8031, 'longitude' => 107.5800],
            ['nama_cabang' => 'Toko Cibiru', 'alamat' => 'Jl. A.H. Nasution No.82 (Depan Kampus UIN)', 'latitude' => -6.9308, 'longitude' => 107.7178],
            ['nama_cabang' => 'Toko Jati', 'alamat' => 'Jl. Jati no. 83, Kel. Cibabat, Kec. Cimahi Utara', 'latitude' => -6.8745, 'longitude' => 107.5568],
            ['nama_cabang' => 'Toko Lembang', 'alamat' => 'Jl. Raya Lembang No.388', 'latitude' => -6.8111, 'longitude' => 107.6171],
            ['nama_cabang' => 'Toko Derwati', 'alamat' => 'Jl. Derwati no.50, kec. Rancasari, kab. Bandung', 'latitude' => -6.9646, 'longitude' => 107.6829],
            ['nama_cabang' => 'Toko Cikutra', 'alamat' => 'Jl. Cikutra no. 230, kec.neglasari, kota bandung', 'latitude' => -6.8993, 'longitude' => 107.6372],
            ['nama_cabang' => 'Toko Sarijadi', 'alamat' => 'Jl. Sarimanah no. 25, sarijadi, kec. Sukasari, kota bandung', 'latitude' => -6.8713, 'longitude' => 107.5804],
            ['nama_cabang' => 'Toko Subang', 'alamat' => 'Jl raya cagak subang, kab. Subang', 'latitude' => -6.6669, 'longitude' => 107.6974],
            ['nama_cabang' => 'Toko Baksil', 'alamat' => 'Jl. Siliwangi no 31b', 'latitude' => -6.9103, 'longitude' => 107.6195],
        ];"""

target = re.search(r'\$cabangs\s*=\s*\[.*?\];', content, re.DOTALL)
if target:
    content = content[:target.start()] + new_cabangs + content[target.end():]
    
    # Also fix the loop length from 2 to 5 to 2 to 11
    content = content.replace("for ($cabangId = 2; $cabangId <= 5; $cabangId++)", "for ($cabangId = 2; $cabangId <= 11; $cabangId++)")
    
    with open(file_path, "w", encoding="utf-8") as f:
        f.write(content)
    print("DatabaseSeeder updated successfully.")
else:
    print("Target block not found.")
