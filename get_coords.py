import urllib.request
import urllib.parse
import json
import time

addresses = [
    "Jl. Ibu ganirah no. 70, Cibeber, Cimahi Selatan",
    "Jl. Raya Batujajar No. 330, Bandung Barat",
    "Jl. Kolonel Matsuri No. 177, Patrol, Parongpong",
    "Jl. A.H. Nasution No.82, UIN, Bandung",
    "Jl. Jati no. 83, Cibabat, Cimahi Utara",
    "Jl. Raya Lembang No.388, Lembang",
    "jl. Derwati no.50, Rancasari, Bandung",
    "jl. Cikutra no. 230, neglasari, bandung",
    "jl. Sarimanah no. 25, sarijadi, bandung",
    "jl raya cagak subang, Subang",
    "jl. Siliwangi no 31b, bandung"
]

results = []

for addr in addresses:
    try:
        url = "https://nominatim.openstreetmap.org/search?q=" + urllib.parse.quote(addr) + "&format=json"
        req = urllib.request.Request(url, headers={'User-Agent': 'MenakBot/1.0'})
        with urllib.request.urlopen(req) as resp:
            data = json.loads(resp.read().decode())
            if data:
                results.append({
                    "alamat": addr,
                    "lat": data[0]["lat"],
                    "lon": data[0]["lon"]
                })
            else:
                results.append({"alamat": addr, "lat": None, "lon": None})
    except Exception as e:
        results.append({"alamat": addr, "error": str(e)})
    time.sleep(1)

with open("cabang_data.json", "w") as f:
    json.dump(results, f, indent=4)
print("Done")
