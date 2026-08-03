import sys

def patch_file(file_path):
    with open(file_path, "r", encoding="utf-8") as f:
        content = f.read()

    target = """        $eventDates = [
            'weekend' => $nextWeekend->translatedFormat('d M Y'),
            'payday' => $nextPayday->translatedFormat('d M Y'),
            'wisuda' => $nextWisuda->translatedFormat('d M Y'),
            'liburan' => $nextLiburan->translatedFormat('d M Y'),
        ];"""
    
    rep = """        $eventDates = [
            'weekend' => $nextWeekend->translatedFormat('d M') . ' - ' . $nextWeekend->copy()->addDay()->translatedFormat('d M Y'),
            'payday' => $nextPayday->translatedFormat('d M') . ' - ' . $nextPayday->copy()->addDays(7)->translatedFormat('d M Y'),
            'wisuda' => $nextWisuda->translatedFormat('d M') . ' - ' . $nextWisuda->copy()->addDays(14)->translatedFormat('d M Y'),
            'liburan' => $nextLiburan->translatedFormat('d M') . ' - ' . $nextLiburan->copy()->addDays(9)->translatedFormat('d M Y'),
        ];"""

    if target in content:
        content = content.replace(target, rep)
        with open(file_path, "w", encoding="utf-8") as f:
            f.write(content)
        print("Patched successfully.")
    else:
        print("Target not found.")

patch_file("app/Http/Controllers/SatuanLogistikController.php")
