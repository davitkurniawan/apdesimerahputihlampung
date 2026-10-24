import urllib.request, json, concurrent.futures, time, subprocess

print("1. Fetching all Lampung regional data from API...")
def fetch_json(url):
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    for _ in range(3):
        try:
            with urllib.request.urlopen(req, timeout=10) as resp:
                return json.loads(resp.read().decode())
        except Exception:
            time.sleep(0.3)
    return []

regencies = fetch_json('https://www.emsifa.com/api-wilayah-indonesia/api/regencies/18.json')
all_districts = []
for r in regencies:
    districts = fetch_json(f'https://www.emsifa.com/api-wilayah-indonesia/api/districts/{r["id"]}.json')
    for d in districts:
        reg_name = r['name'].replace('KABUPATEN ', '').title()
        d['regency_name'] = ('Kabupaten ' + reg_name) if not r['name'].startswith('KOTA') else r['name'].title()
        d['district_name'] = d['name'].title()
        all_districts.append(d)

print(f"-> Found {len(all_districts)} districts in {len(regencies)} regencies.")

all_villages = []
def get_villages_for_district(d):
    v_list = fetch_json(f'https://www.emsifa.com/api-wilayah-indonesia/api/villages/{d["id"]}.json')
    res = []
    for v in v_list:
        res.append((
            v['id'],
            v['name'].title().replace("'", "''"),
            d['id'],
            d['district_name'].replace("'", "''"),
            d['regency_id'],
            d['regency_name'].replace("'", "''"),
            'Lampung'
        ))
    return res

with concurrent.futures.ThreadPoolExecutor(max_workers=10) as executor:
    results = executor.map(get_villages_for_district, all_districts)
    for r in results:
        all_villages.extend(r)

print(f"-> Total {len(all_villages)} villages fetched.")

print("2. Generating SQL file...")
sql_lines = [
    "USE xosid;",
    "CREATE TABLE IF NOT EXISTS `ref_wilayah_lampung` (",
    "    `id` INT AUTO_INCREMENT PRIMARY KEY,",
    "    `kode_desa` VARCHAR(20) NOT NULL UNIQUE,",
    "    `nama_desa` VARCHAR(100) NOT NULL,",
    "    `kode_kec` VARCHAR(20) NOT NULL,",
    "    `nama_kec` VARCHAR(100) NOT NULL,",
    "    `kode_kab` VARCHAR(20) NOT NULL,",
    "    `nama_kab` VARCHAR(100) NOT NULL,",
    "    `provinsi` VARCHAR(50) NOT NULL DEFAULT 'Lampung',",
    "    INDEX (`nama_desa`),",
    "    INDEX (`nama_kec`),",
    "    INDEX (`nama_kab`),",
    "    INDEX (`kode_desa`)",
    ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
    "TRUNCATE TABLE `ref_wilayah_lampung`;",
    "INSERT INTO `ref_wilayah_lampung` (`kode_desa`, `nama_desa`, `kode_kec`, `nama_kec`, `kode_kab`, `nama_kab`, `provinsi`) VALUES"
]

values_list = []
for v in all_villages:
    values_list.append(f"('{v[0]}', '{v[1]}', '{v[2]}', '{v[3]}', '{v[4]}', '{v[5]}', '{v[6]}')")

sql_content = "\n".join(sql_lines) + "\n" + ",\n".join(values_list) + ";\n"

with open("lampung_villages.sql", "w", encoding="utf-8") as f:
    f.write(sql_content)

print("3. Executing SQL into MySQL Docker container...")
subprocess.run(
    "docker exec -i xosid_neural_db mysql -uroot -ppassword xosid < lampung_villages.sql",
    shell=True,
    check=True
)

print("-> SUCCESS! All Lampung villages successfully populated into ref_wilayah_lampung.")
