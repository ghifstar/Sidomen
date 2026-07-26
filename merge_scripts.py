import os

# Get scripts from welcome.blade.php
with open('resources/views/welcome.blade.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

scripts_content = []
in_scripts = False
for line in lines:
    if "<!-- INTERACTIVE MODAL FOR CHART & ROP SIMULATION -->" in line:
        in_scripts = True
    
    if in_scripts:
        if "</body>" in line:
            break
        scripts_content.append(line)

scripts_text = "".join(scripts_content)

# Insert into app.blade.php before </body>
with open('resources/views/layouts/app.blade.php', 'r', encoding='utf-8') as f:
    app_content = f.read()

app_content = app_content.replace('</body>', scripts_text + '\n</body>')

with open('resources/views/layouts/app.blade.php', 'w', encoding='utf-8') as f:
    f.write(app_content)

print("Merged scripts into app.blade.php")
