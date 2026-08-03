import urllib.request
import urllib.parse
import json
import time

queries = [
    "Cibeber, Cimahi Selatan",
    "Batujajar, Bandung Barat",
    "Parongpong, Bandung Barat",
    "UIN Sunan Gunung Djati Bandung",
    "Cibabat, Cimahi",
    "Lembang, Bandung Barat",
    "Derwati, Bandung",
    "Cikutra, Bandung",
    "Sarijadi, Bandung",
    "Jalan Raya Cagak, Subang",
    "Siliwangi, Bandung"
]

results = []

for addr in queries:
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

with open("cabang_data_2.json", "w") as f:
    json.dump(results, f, indent=4)
print("Done")
