import json
import urllib.request
from concurrent.futures import ThreadPoolExecutor

def fetch(url):
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    try:
        with urllib.request.urlopen(req, timeout=10) as response:
            return json.loads(response.read().decode())
    except Exception as e:
        print(f"Error fetching {url}: {e}")
        return []

data = {"18": {"nama": "LAMPUNG (18)", "kabupaten": {}}}

kabs = fetch("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/18.json")
for kab in kabs:
    data["18"]["kabupaten"][kab['id']] = {"nama": f"{kab['name']} ({kab['id']})", "kecamatan": {}}

def process_kab(kab_id):
    kecs = fetch(f"https://emsifa.github.io/api-wilayah-indonesia/api/districts/{kab_id}.json")
    for kec in kecs:
        kec_id = kec['id']
        data["18"]["kabupaten"][kab_id]["kecamatan"][kec_id] = {"nama": f"{kec['name']} ({kec_id})", "desa": {}}

with ThreadPoolExecutor(max_workers=10) as executor:
    executor.map(process_kab, data["18"]["kabupaten"].keys())

def process_kec(kab_id, kec_id):
    desas = fetch(f"https://emsifa.github.io/api-wilayah-indonesia/api/villages/{kec_id}.json")
    for desa in desas:
        data["18"]["kabupaten"][kab_id]["kecamatan"][kec_id]["desa"][desa['id']] = f"{desa['name']} ({desa['id']})"

tasks = []
for kab_id in data["18"]["kabupaten"].keys():
    for kec_id in data["18"]["kabupaten"][kab_id]["kecamatan"].keys():
        tasks.append((kab_id, kec_id))

with ThreadPoolExecutor(max_workers=20) as executor:
    for kab_id, kec_id in tasks:
        executor.submit(process_kec, kab_id, kec_id)

with open("scratch/lampung_data.json", "w") as f:
    json.dump(data, f)
print("Done!")
