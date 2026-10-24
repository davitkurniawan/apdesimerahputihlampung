<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
}
$amp_active_menu = "peta";
require_once __DIR__ . '/../../../xosid_ajax_db.php';
$conn->select_db('xosid');

// Koordinat referensi pusat Kabupaten/Kota di Provinsi Lampung
$kab_coords = [
    'Kota Bandar Lampung'           => [-5.4297, 105.2625],
    'Bandar Lampung'                => [-5.4297, 105.2625],
    'Kota Metro'                    => [-5.1136, 105.3069],
    'Metro'                         => [-5.1136, 105.3069],
    'Lampung Selatan'               => [-5.5786, 105.5847],
    'Kabupaten Lampung Selatan'     => [-5.5786, 105.5847],
    'Lampung Tengah'                => [-4.8622, 105.2148],
    'Kabupaten Lampung Tengah'      => [-4.8622, 105.2148],
    'Lampung Timur'                 => [-5.1039, 105.6811],
    'Kabupaten Lampung Timur'       => [-5.1039, 105.6811],
    'Lampung Utara'                 => [-4.8142, 104.8519],
    'Kabupaten Lampung Utara'       => [-4.8142, 104.8519],
    'Lampung Barat'                 => [-5.1486, 104.1923],
    'Kabupaten Lampung Barat'       => [-5.1486, 104.1923],
    'Pesisir Barat'                 => [-5.1931, 103.9405],
    'Kabupaten Pesisir Barat'       => [-5.1931, 103.9405],
    'Tulang Bawang'                 => [-4.4567, 105.6568],
    'Kabupaten Tulang Bawang'       => [-4.4567, 105.6568],
    'Tulang Bawang Barat'           => [-4.4371, 105.0487],
    'Kabupaten Tulang Bawang Barat' => [-4.4371, 105.0487],
    'Mesuji'                        => [-4.0412, 105.4042],
    'Kabupaten Mesuji'              => [-4.0412, 105.4042],
    'Pesawaran'                     => [-5.4293, 105.1784],
    'Kabupaten Pesawaran'           => [-5.4293, 105.1784],
    'Pringsewu'                     => [-5.3582, 104.9744],
    'Kabupaten Pringsewu'           => [-5.3582, 104.9744],
    'Tanggamus'                     => [-5.4984, 104.6212],
    'Kabupaten Tanggamus'           => [-5.4984, 104.6212],
    'Way Kanan'                     => [-4.5126, 104.5298],
    'Kabupaten Way Kanan'           => [-4.5126, 104.5298]
];

$desa_list = [];
$res = $conn->query("SELECT DISTINCT nama_desa, kecamatan, kabupaten, status FROM amp_anggota WHERE LOWER(status) = 'approved'");
if (!$res || $res->num_rows === 0) {
    // Fallback: jika belum ada approved, ambil semua yang terdaftar agar peta tidak kosong
    $res = $conn->query("SELECT DISTINCT nama_desa, kecamatan, kabupaten, status FROM amp_anggota WHERE nama_desa IS NOT NULL AND nama_desa != ''");
}

if ($res) {
    while($row = $res->fetch_assoc()) {
        if (!empty($row['nama_desa'])) {
            $kab = trim($row['kabupaten'] ?? '');
            
            // Tentukan basis koordinat kabupaten
            $base_lat = -5.0000;
            $base_lng = 105.2000;
            
            foreach ($kab_coords as $kab_name => $coords) {
                if (stripos($kab, $kab_name) !== false || stripos($kab_name, $kab) !== false) {
                    $base_lat = $coords[0];
                    $base_lng = $coords[1];
                    break;
                }
            }
            
            // Jitter deterministik berdasarkan nama desa agar posisi menyebar rapi di wilayahnya
            $seed = crc32($row['nama_desa'] . ($row['kecamatan'] ?? ''));
            $lat_offset = (($seed % 1000) / 1000 - 0.5) * 0.14; // ±0.07 derajat (~7 km)
            $lng_offset = ((($seed >> 8) % 1000) / 1000 - 0.5) * 0.16; // ±0.08 derajat (~8 km)
            
            $lat = round($base_lat + $lat_offset, 6);
            $lng = round($base_lng + $lng_offset, 6);
            
            $desa_list[] = [
                'nama' => $row['nama_desa'],
                'kec' => $row['kecamatan'] ?? '',
                'kab' => $row['kabupaten'] ?? 'Provinsi Lampung',
                'status' => $row['status'] ?? 'approved',
                'lat' => $lat,
                'lng' => $lng
            ];
        }
    }
}
$desa_json = json_encode($desa_list);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Potensi Desa - APDesi Merah Putih Lampung</title>
    <!-- Favicon APDesi Merah Putih -->
    <link rel="icon" type="image/png" sizes="32x32" href="/public/images/favicon_amp.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/public/images/favicon_amp.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <!-- Leaflet CSS (Open Source, Bebas API Key) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Playfair Display', serif; }
        #map { height: 620px; z-index: 10; border-radius: 1.5rem; }
        @media (max-width: 640px) {
            #map { height: 430px; border-radius: 1rem; }
        }
        .leaflet-popup-content-wrapper { border-radius: 1rem; padding: 0.25rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1); }
        .leaflet-popup-content { margin: 10px 14px; line-height: 1.4; }
        .leaflet-container { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex flex-col justify-between">

    <!-- Navbar -->
    <?php require __DIR__ . "/amp_header_partial.php"; ?>

    <!-- Main Content -->
    <main class="flex-grow">
        <!-- Header Section -->
        <div class="bg-gradient-to-br from-red-800 via-red-900 to-red-950 py-16 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white via-transparent to-transparent"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-700/60 text-white border border-red-500/30 uppercase tracking-widest mb-3 backdrop-blur-sm">
                    🌐 Open GIS Network
                </span>
                <h1 class="text-3xl md:text-5xl font-black text-white font-heading tracking-tight mb-4 drop-shadow-sm">
                    Peta Potensi & Sebaran Desa
                </h1>
                <p class="text-red-100 max-w-2xl mx-auto text-sm md:text-base leading-relaxed">
                    Sebaran desa di wilayah Provinsi Lampung yang tergabung dalam Ekosistem Resmi APDesi Merah Putih.
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 mb-16">
            <!-- Filter & Layer Switcher Bar -->
            <div class="bg-white/95 backdrop-blur-md p-4 rounded-2xl shadow-lg border border-gray-200 mb-4 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pilih Tampilan Peta:</span>
                    <div class="inline-flex p-1 bg-gray-100 rounded-xl text-xs font-bold text-gray-700" id="layerButtons">
                        <button onclick="setBaseLayer('osm')" id="btn-osm" class="px-3 py-1.5 rounded-lg bg-white text-red-700 shadow-sm transition-all flex items-center gap-1.5">
                            🗺️ Standar OSM
                        </button>
                        <button onclick="setBaseLayer('sat')" id="btn-sat" class="px-3 py-1.5 rounded-lg text-gray-600 hover:text-gray-900 transition-all flex items-center gap-1.5">
                            🛰️ Citra Satelit
                        </button>
                        <button onclick="setBaseLayer('hot')" id="btn-hot" class="px-3 py-1.5 rounded-lg text-gray-600 hover:text-gray-900 transition-all flex items-center gap-1.5">
                            ⛰️ Topografi
                        </button>
                    </div>
                </div>

                <!-- Search Village Quick Jump -->
                <div class="flex items-center gap-2 flex-grow sm:flex-grow-0 sm:w-72">
                    <div class="relative w-full">
                        <input type="text" id="desaSearch" placeholder="Cari nama desa..." onkeyup="filterDesaMap(this.value)"
                            class="w-full pl-9 pr-4 py-2 text-xs border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:outline-none">
                        <span class="absolute left-3 top-2.5 text-gray-400 text-xs">🔍</span>
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div class="bg-white p-2 rounded-3xl shadow-xl border border-gray-200 relative overflow-hidden">
                <div id="map"></div>
            </div>
            
            <!-- Quick Stat Counters -->
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-100 text-red-700 rounded-xl flex items-center justify-center text-xl font-bold">📍</div>
                    <div>
                        <div class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Desa Terdata</div>
                        <div class="text-2xl font-black text-gray-900" id="totalDesaCount"><?php echo count($desa_list); ?></div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-xl flex items-center justify-center text-xl font-bold">🗺️</div>
                    <div>
                        <div class="text-xs text-gray-500 font-bold uppercase tracking-wider">Wilayah Cakupan</div>
                        <div class="text-2xl font-black text-gray-900">Provinsi Lampung</div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center text-xl font-bold">🛡️</div>
                    <div>
                        <div class="text-xs text-gray-500 font-bold uppercase tracking-wider">Penyedia Peta</div>
                        <div class="text-2xl font-black text-gray-900">OpenStreetMap Free (No Key)</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 text-center py-6 text-xs text-gray-500">
        &copy; 2026 APDesi Merah Putih Lampung. Powered by xoSID Supradesa.
    </footer>

    <!-- Leaflet JS (Open Source, No API Key Required) -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        let map;
        let markersGroup;
        let baseLayers = {};
        let allMarkers = [];
        const desaList = <?php echo $desa_json; ?>;

        document.addEventListener("DOMContentLoaded", function() {
            // Inisialisasi Peta - Terpusat di Provinsi Lampung
            map = L.map('map', {
                center: [-4.95, 105.15],
                zoom: 8,
                zoomControl: true
            });

            // Definisi 3 Tile Layer 100% BEBAS API KEY:
            // 1. OpenStreetMap Standar
            baseLayers['osm'] = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                subdomains: ['a', 'b', 'c'],
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>'
            });

            // 2. Esri World Imagery (Citra Satelit - Bebas API Key)
            baseLayers['sat'] = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: 'Tiles &copy; Esri &mdash; Sumber: Esri, Maxar, Earthstar Geographics'
            });

            // 3. OpenStreetMap HOT (Topografi / Kontur)
            baseLayers['hot'] = L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors, Humanitarian OpenStreetMap Team'
            });

            // Aktifkan default: OpenStreetMap Standar
            baseLayers['osm'].addTo(map);

            // Layer Group untuk menyimpan marker
            markersGroup = L.featureGroup().addTo(map);

            // Pasang semua marker desa secara instan tanpa fetch external
            renderMarkers(desaList);

            // Invalidate size agar render sempurna
            setTimeout(() => {
                map.invalidateSize();
            }, 250);
        });

        // Ganti Layer Tampilan Peta
        function setBaseLayer(type) {
            Object.values(baseLayers).forEach(layer => {
                if (map.hasLayer(layer)) map.removeLayer(layer);
            });
            baseLayers[type].addTo(map);

            // Update status tombol UI
            ['osm', 'sat', 'hot'].forEach(t => {
                const btn = document.getElementById('btn-' + t);
                if (t === type) {
                    btn.className = "px-3 py-1.5 rounded-lg bg-white text-red-700 shadow-sm transition-all flex items-center gap-1.5";
                } else {
                    btn.className = "px-3 py-1.5 rounded-lg text-gray-600 hover:text-gray-900 transition-all flex items-center gap-1.5";
                }
            });
        }

        // Render Marker ke Peta
        function renderMarkers(items) {
            markersGroup.clearLayers();
            allMarkers = [];

            if (!items || items.length === 0) return;

            items.forEach(desa => {
                // Pin Marker Merah Putih Khusus APDesi
                const markerIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `
                        <div style="position:relative; width:28px; height:28px; display:flex; align-items:center; justify-content:center;">
                            <div style="position:absolute; width:26px; height:26px; border-radius:50%; background:rgba(220,38,38,0.25); animation:ping 2s cubic-bezier(0,0,0.2,1) infinite;"></div>
                            <div style="width:20px; height:20px; border-radius:50%; background:linear-gradient(135deg, #dc2626 50%, #ffffff 50%); border:2px solid #991b1b; box-shadow:0 3px 6px rgba(0,0,0,0.3); display:flex; align-items:center; justify-content:center;">
                                <div style="width:5px; height:5px; border-radius:50%; background:#7f1d1d;"></div>
                            </div>
                        </div>
                    `,
                    iconSize: [28, 28],
                    iconAnchor: [14, 14],
                    popupAnchor: [0, -14]
                });

                const popupHtml = `
                    <div style="min-width:210px; font-family:'Inter', sans-serif;">
                        <div style="display:inline-block; font-size:10px; font-weight:800; color:#991b1b; background:#fee2e2; padding:2px 8px; border-radius:9999px; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px;">
                            ⭐ APDesi Merah Putih
                        </div>
                        <h4 style="margin:0 0 4px 0; font-size:15px; font-weight:800; color:#111827;">${desa.nama}</h4>
                        <div style="font-size:12px; color:#4b5563; line-height:1.4;">
                            <div><strong>Kecamatan:</strong> ${desa.kec || '-'}</div>
                            <div><strong>Kabupaten:</strong> ${desa.kab}</div>
                        </div>
                        <div style="margin-top:8px; padding-top:6px; border-top:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center; font-size:11px;">
                            <span style="color:#059669; font-weight:700;">● Terverifikasi</span>
                            <span style="color:#9ca3af; font-family:monospace;">${desa.lat}, ${desa.lng}</span>
                        </div>
                    </div>
                `;

                const marker = L.marker([desa.lat, desa.lng], { icon: markerIcon })
                    .bindPopup(popupHtml);

                marker.desaData = desa;
                markersGroup.addLayer(marker);
                allMarkers.push(marker);
            });

            // Zoom otomatis mencakup semua titik desa
            if (markersGroup.getLayers().length > 0) {
                map.fitBounds(markersGroup.getBounds(), { padding: [50, 50], maxZoom: 13 });
            }
        }

        // Filter pencarian desa
        function filterDesaMap(keyword) {
            const q = keyword.toLowerCase().trim();
            if (!q) {
                renderMarkers(desaList);
                document.getElementById('totalDesaCount').innerText = desaList.length;
                return;
            }

            const filtered = desaList.filter(d => 
                (d.nama && d.nama.toLowerCase().includes(q)) ||
                (d.kec && d.kec.toLowerCase().includes(q)) ||
                (d.kab && d.kab.toLowerCase().includes(q))
            );

            renderMarkers(filtered);
            document.getElementById('totalDesaCount').innerText = filtered.length;

            if (filtered.length === 1) {
                map.flyTo([filtered[0].lat, filtered[0].lng], 13);
            }
        }
    </script>
</body>
</html>
