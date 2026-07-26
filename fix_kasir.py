import re

with open('resources/views/dashboards/kasir_cabang.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Remove the button group
content = re.sub(r'<div class="flex items-center gap-1\.5 bg-gold-300 p-1 rounded-xl border border-gold-500 text-xs font-black">.*?</div>', '', content, flags=re.DOTALL)

# Remove the hidden classes from the tabs
content = content.replace('id="tab-kasir-pos"', 'id="tab-kasir-pos" class="space-y-6"')
content = content.replace('id="tab-kasir-pos" class="space-y-6" class="space-y-6"', 'id="tab-kasir-pos" class="space-y-6"') # in case it was already there

content = content.replace('id="tab-kasir-laporan" class="hidden space-y-6"', 'id="tab-kasir-laporan" class="space-y-6 mt-8 pt-8 border-t-2 border-gold-400 border-dashed"')

with open('resources/views/dashboards/kasir_cabang.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Kasir tabs removed and combined.")
