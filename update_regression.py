import sys

def patch_file(file_path):
    with open(file_path, "r", encoding="utf-8") as f:
        content = f.read()

    target = """                                        @php
                                            $baseDonatAvg = $calc['rata_rata_donat_harian'] ?? 300;
                                        @endphp"""
    
    rep = """                                        @php
                                            // Menggunakan nilai prediksi Linear Regression (7 Hari Ke Depan) dari Python AI
                                            $grafik = $ai['grafik_tren'] ?? [];
                                            $prediksi7Hari = $grafik['prediksi_7_hari_donat'] ?? [];
                                            
                                            // Rata-rata dari prediksi regresi linier masa depan
                                            $baseDonatAvg = count($prediksi7Hari) > 0 
                                                ? array_sum($prediksi7Hari) / count($prediksi7Hari) 
                                                : ($calc['rata_rata_donat_harian'] ?? 300);
                                        @endphp"""

    if target in content:
        content = content.replace(target, rep)
        with open(file_path, "w", encoding="utf-8") as f:
            f.write(content)
        print("Patched owner_cabang.blade.php successfully.")
    else:
        print("Target not found in owner_cabang.blade.php.")

patch_file("resources/views/dashboards/owner_cabang.blade.php")
