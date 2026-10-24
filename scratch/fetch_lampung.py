import json
import time
import urllib.request
import urllib.error

def fetch(url):
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    try:
        with urllib.request.urlopen(req) as response:
            return json.loads(response.read().decode())
    except Exception as e:
        print(f"Error fetching {url}: {e}")
        return []

data = {
    "18": {
        "nama": "LAMPUNG (18)",
        "kabupaten": {}
    }
}

print("Fetching kabupaten...")
kabs = fetch("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/18.json")
for kab in kabs:
    kab_id = kab['id']
    data["18"]["kabupaten"][kab_id] = {
        "nama": f"{kab['name']} ({kab_id})",
        "kecamatan": {}
    }

print(f"Fetching kecamatan for {len(kabs)} kabupaten...")
for kab in kabs:
    kab_id = kab['id']
    kecs = fetch(f"https://emsifa.github.io/api-wilayah-indonesia/api/districts/{kab_id}.json")
    for kec in kecs:
        kec_id = kec['id']
        data["18"]["kabupaten"][kab_id]["kecamatan"][kec_id] = {
            "nama": f"{kec['name']} ({kec_id})",
            "desa": {}
        }

print("Fetching desa...")
for kab_id, kab_data in data["18"]["kabupaten"].items():
    for kec_id in kab_data["kecamatan"].keys():
        desas = fetch(f"https://emsifa.github.io/api-wilayah-indonesia/api/villages/{kec_id}.json")
        for desa in desas:
            desa_id = desa['id']
            data["18"]["kabupaten"][kab_id]["kecamatan"][kec_id]["desa"][desa_id] = f"{desa['name']} ({desa_id})"
        time.sleep(0.01)

with open("scratch/lampung_data.json", "w") as f:
    json.dump(data, f)
print("Done!")
