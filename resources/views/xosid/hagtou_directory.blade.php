<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direktori BUMDes Terdaftar - Hagtou Nasional</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        title: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .font-title { font-family: 'Outfit', sans-serif; }
        body { font-family: 'Inter', sans-serif; }
        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        /* Custom scrollbar for sidebar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Pulsing/vibrating animated dot for Leaflet pins */
        @keyframes pulse-ring {
            0% { transform: scale(0.33); opacity: 1; }
            80%, 100% { transform: scale(1.3); opacity: 0; }
        }
        @keyframes pulse-dot {
            0%, 100% { transform: scale(0.9); }
            50% { transform: scale(1.15); }
        }
        .pulse-marker-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
        }
        .pulse-marker {
            position: relative;
            width: 14px;
            height: 14px;
            background-color: #ea580c; /* orange-600 */
            border: 2px solid white;
            border-radius: 50%;
            box-shadow: 0 0 8px rgba(234, 88, 12, 0.6);
            animation: pulse-dot 1.2s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite;
        }
        .pulse-marker::before {
            content: '';
            position: absolute;
            top: -8px;
            left: -8px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background-color: rgba(234, 88, 12, 0.4);
            animation: pulse-ring 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
            z-index: -1;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 h-screen flex flex-col overflow-hidden">

    <!-- Header / Navbar -->
    <header class="glass-header sticky top-0 z-40 w-full shrink-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/" class="flex items-center gap-2">
                    <span class="text-2xl font-black font-title tracking-wider text-[#1B3C73]">
                        HAG<span class="text-orange-500">TOU</span>
                    </span>
                    <span class="text-[10px] font-bold bg-[#1B3C73]/10 text-[#1B3C73] px-2 py-0.5 rounded-full uppercase tracking-wider hidden sm:inline-block">NASIONAL</span>
                </a>
            </div>
            
            <nav class="flex items-center gap-8">
                <a href="/" class="text-sm font-semibold text-slate-600 hover:text-orange-500 transition-colors">
                    <i class="fa-solid fa-house mr-1"></i> Beranda Portal
                </a>
                <span class="text-sm font-bold text-orange-500 flex items-center gap-1">
                    <i class="fa-solid fa-map-location-dot"></i> Direktori BUMDes
                </span>
            </nav>
        </div>
    </header>

    <!-- Main Container (Sidebar + Map Split View) -->
    <div class="flex-1 flex flex-col md:flex-row overflow-hidden relative">
        
        <!-- Left Sidebar (Filters, Search, BUMDes List) -->
        <aside class="w-full md:w-[420px] shrink-0 border-r border-slate-200 bg-white flex flex-col h-1/2 md:h-full overflow-hidden shadow-md z-30">
            
            <!-- Sidebar Header / Title -->
            <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                <h1 class="text-xl font-bold font-title text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-hotel text-orange-500"></i> Direktori BUMDes
                </h1>
                <p class="text-xs text-slate-500 mt-1">Jelajahi BUMDes terintegrasi beserta sebaran komoditas produk unggulannya secara nasional.</p>
            </div>

            <!-- Search and Filter Panel -->
            <div class="p-5 border-b border-slate-100 space-y-4">
                <!-- Search bar -->
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                    <input type="text" id="directorySearchInput" oninput="filterBumdesDirectory()" placeholder="Cari BUMDes, kecamatan, atau provinsi..." class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:outline-none focus:border-orange-500/50 transition-colors text-slate-800">
                </div>

                <!-- Product Category Filters (Toggles / Layers) -->
                <div>
                    <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2">Kategori Komoditas (Filter Lapisan)</span>
                    <div class="grid grid-cols-2 gap-2" id="categoryFilterContainer">
                        <button onclick="toggleCategoryFilter('pangan')" id="btn-filter-pangan" class="px-3 py-2 rounded-xl border text-[11px] font-semibold flex items-center gap-1.5 transition-all bg-orange-50 border-orange-200 text-orange-600 shadow-sm">
                            <i class="fa-solid fa-wheat-awn"></i> Pangan / Tani
                        </button>
                        <button onclick="toggleCategoryFilter('kerajinan')" id="btn-filter-kerajinan" class="px-3 py-2 rounded-xl border text-[11px] font-semibold flex items-center gap-1.5 transition-all bg-orange-50 border-orange-200 text-orange-600 shadow-sm">
                            <i class="fa-solid fa-palette"></i> Kerajinan
                        </button>
                        <button onclick="toggleCategoryFilter('olahan')" id="btn-filter-olahan" class="px-3 py-2 rounded-xl border text-[11px] font-semibold flex items-center gap-1.5 transition-all bg-orange-50 border-orange-200 text-orange-600 shadow-sm">
                            <i class="fa-solid fa-mug-hot"></i> Makanan Olahan
                        </button>
                        <button onclick="toggleCategoryFilter('jasa')" id="btn-filter-jasa" class="px-3 py-2 rounded-xl border text-[11px] font-semibold flex items-center gap-1.5 transition-all bg-orange-50 border-orange-200 text-orange-600 shadow-sm">
                            <i class="fa-solid fa-umbrella-beach"></i> Wisata / Jasa
                        </button>
                    </div>
                </div>
            </div>

            <!-- Dynamic BUMDes Card List -->
            <div class="flex-1 overflow-y-auto custom-scrollbar p-5 space-y-3" id="directoryBumdesGrid">
                <!-- Javascript will inject list cards here -->
            </div>
            
            <!-- Sidebar Footer Status -->
            <div class="p-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between text-[10px] text-slate-400 font-bold uppercase tracking-wider shrink-0">
                <span>Total Hasil Saring:</span>
                <span class="text-orange-600 font-black" id="filteredBumdesCount">0 BUMDes</span>
            </div>
        </aside>

        <!-- Right Side: Leaflet Map Container -->
        <main class="flex-1 h-1/2 md:h-full relative z-10">
            <div id="directoryMap" class="w-full h-full"></div>
            
            <!-- Custom map controls overlay -->
            <div class="absolute bottom-5 right-5 z-20 bg-white/95 backdrop-blur-sm border border-slate-200 p-3 rounded-2xl shadow-lg max-w-xs text-[10px] text-slate-500 leading-relaxed font-semibold">
                <span class="block font-bold text-slate-700 text-xs mb-1"><i class="fa-solid fa-circle-info text-orange-500 mr-1"></i> Navigasi Peta</span>
                Gunakan kursor atau cubit layar untuk zoom. Klik penanda lokasi pin untuk melihat profil, kategori produk, dan membuka gerai digital.
            </div>
        </main>

    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Core BUMDes & Products dataset
        const bumdesData = [
            { 
                name: "BUMDes Bumi Daya", 
                village: "Bumi Daya", 
                district: "Palas", 
                regency: "Lampung Selatan", 
                province: "Lampung", 
                subdomain: "demo", 
                status: "Aktif", 
                joined: "12-05-2026", 
                lat: -5.7364, 
                lng: 105.6967,
                categories: ["pangan", "olahan"],
                products: [
                    { name: "Beras Merah Premium Desa 5kg", category: "pangan", price: 75000 },
                    { name: "Kripik Pisang Coklat Lampung", category: "olahan", price: 15000 }
                ]
            },
            { 
                name: "BUMDes Bondo", 
                village: "Bondo", 
                district: "Bangsri", 
                regency: "Jepara", 
                province: "Jawa Tengah", 
                subdomain: "bondo", 
                status: "Aktif", 
                joined: "18-05-2026", 
                lat: -6.4716, 
                lng: 110.7067,
                categories: ["kerajinan", "olahan"],
                products: [
                    { name: "Kerajinan Ukiran Jati Jepara", category: "kerajinan", price: 350000 },
                    { name: "Kopi Robusta Bondo 250g", category: "olahan", price: 45000 }
                ]
            },
            { 
                name: "BUMDes Hajimena", 
                village: "Hajimena", 
                district: "Natar", 
                regency: "Lampung Selatan", 
                province: "Lampung", 
                subdomain: "hajimena", 
                status: "Aktif", 
                joined: "01-06-2026", 
                lat: -5.3582, 
                lng: 105.2155,
                categories: ["pangan", "jasa"],
                products: [
                    { name: "Bibit Alpukat Mentega Unggul", category: "pangan", price: 35000 },
                    { name: "Eko-Wisata Embung Hajimena", category: "jasa", price: 10000 }
                ]
            },
            { 
                name: "BUMDes Sidosari", 
                village: "Sidosari", 
                district: "Natar", 
                regency: "Lampung Selatan", 
                province: "Lampung", 
                subdomain: "sidosari", 
                status: "Aktif", 
                joined: "04-06-2026", 
                lat: -5.3341, 
                lng: 105.1912,
                categories: ["olahan"],
                products: [
                    { name: "Sambal Selera Sidosari 150g", category: "olahan", price: 20000 },
                    { name: "Tempe Keripik Gurih Sidosari", category: "olahan", price: 12000 }
                ]
            },
            { 
                name: "BUMDes Pemanggilan", 
                village: "Pemanggilan", 
                district: "Natar", 
                regency: "Lampung Selatan", 
                province: "Lampung", 
                subdomain: "pemanggilan", 
                status: "Aktif", 
                joined: "10-06-2026", 
                lat: -5.3475, 
                lng: 105.2101,
                categories: ["kerajinan"],
                products: [
                    { name: "Kipas Bambu Tradisional Pemanggilan", category: "kerajinan", price: 8000 },
                    { name: "Anyaman Tikar Mendong", category: "kerajinan", price: 55000 }
                ]
            },
            { 
                name: "BUMDes Natar", 
                village: "Natar", 
                district: "Natar", 
                regency: "Lampung Selatan", 
                province: "Lampung", 
                subdomain: "natar", 
                status: "Aktif", 
                joined: "15-06-2026", 
                lat: -5.3197, 
                lng: 105.1764,
                categories: ["pangan", "jasa"],
                products: [
                    { name: "Pupuk Organik Padat Kompos 10kg", category: "pangan", price: 25000 },
                    { name: "Tiket Wisata Kebun Kelapa Natar", category: "jasa", price: 15000 }
                ]
            }
        ];

        // Active filters state
        const activeCategories = ["pangan", "kerajinan", "olahan", "jasa"];
        let map = null;
        let markersGroup = [];

        document.addEventListener('DOMContentLoaded', () => {
            initMap();
            renderDirectory();
        });

        // Initialize Map
        function initMap() {
            map = L.map('directoryMap').setView([-5.9, 108.0], 7);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
        }

        // Toggle category filter
        function toggleCategoryFilter(category) {
            const index = activeCategories.indexOf(category);
            const btn = document.getElementById(`btn-filter-${category}`);
            
            if (index > -1) {
                // Remove filter
                activeCategories.splice(index, 1);
                btn.className = "px-3 py-2 rounded-xl border text-[11px] font-semibold flex items-center gap-1.5 transition-all border-slate-200 text-slate-500 hover:bg-slate-50 bg-white";
            } else {
                // Add filter
                activeCategories.push(category);
                btn.className = "px-3 py-2 rounded-xl border text-[11px] font-semibold flex items-center gap-1.5 transition-all bg-orange-50 border-orange-200 text-orange-600 shadow-sm";
            }
            
            renderDirectory();
        }

        // Render Sidebar List and Map Markers
        function renderDirectory() {
            const query = document.getElementById('directorySearchInput').value.toLowerCase();
            const listGrid = document.getElementById('directoryBumdesGrid');
            
            // Clear existing markers
            markersGroup.forEach(marker => map.removeLayer(marker));
            markersGroup = [];
            
            let html = '';
            let count = 0;
            const bounds = [];

            bumdesData.forEach(b => {
                // Match search query
                const matchesSearch = b.name.toLowerCase().includes(query) || 
                                      b.district.toLowerCase().includes(query) || 
                                      b.province.toLowerCase().includes(query);
                
                // Match categories (must contain at least one of the selected categories)
                const matchesCategory = b.categories.some(c => activeCategories.includes(c));

                if (matchesSearch && matchesCategory) {
                    count++;
                    const url = `http://${b.subdomain}.localhost:8080/index.php/first`;
                    
                    // Generate category tags HTML
                    let categoryTags = '';
                    b.categories.forEach(c => {
                        let colorClass = 'bg-slate-100 text-slate-600';
                        let icon = 'fa-tag';
                        if (c === 'pangan') { colorClass = 'bg-orange-50 text-orange-600 border border-orange-100'; icon = 'fa-wheat-awn'; }
                        if (c === 'kerajinan') { colorClass = 'bg-indigo-50 text-indigo-600 border border-indigo-100'; icon = 'fa-palette'; }
                        if (c === 'olahan') { colorClass = 'bg-amber-50 text-amber-600 border border-amber-100'; icon = 'fa-mug-hot'; }
                        if (c === 'jasa') { colorClass = 'bg-teal-50 text-teal-600 border border-teal-100'; icon = 'fa-umbrella-beach'; }
                        
                        categoryTags += `
                            <span class="px-2 py-0.5 rounded text-[8px] font-bold ${colorClass} flex items-center gap-1">
                                <i class="fa-solid ${icon}"></i> ${c === 'pangan' ? 'Pangan' : c === 'kerajinan' ? 'Kerajinan' : c === 'olahan' ? 'Olahan' : 'Jasa'}
                            </span>
                        `;
                    });

                    // Build list item
                    html += `
                        <div onclick="focusBumdesLocation(${b.lat}, ${b.lng})" class="bg-slate-50/50 hover:bg-white border border-slate-100 hover:border-slate-200/80 rounded-2xl p-4 flex flex-col justify-between hover:shadow-md cursor-pointer transition-all duration-300 group">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> ${b.status}
                                    </span>
                                    <span class="text-[9px] text-slate-400">Bergabung: ${b.joined}</span>
                                </div>
                                <h4 class="font-bold text-sm text-slate-800 group-hover:text-orange-500 transition-colors">${b.name}</h4>
                                <p class="text-[10px] text-slate-500 mt-1 leading-relaxed">
                                    <i class="fa-solid fa-map-pin text-orange-500/70 mr-1"></i> Desa ${b.village}, Kec. ${b.district}, ${b.regency}, Prov. ${b.province}
                                </p>
                            </div>
                            
                            <!-- Categories & Button -->
                            <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap gap-1.5 items-center justify-between">
                                <div class="flex gap-1">
                                    ${categoryTags}
                                </div>
                                <a href="${url}" target="_blank" onclick="event.stopPropagation();" class="px-3 py-1.5 rounded-lg bg-[#1B3C73] text-[9px] font-bold text-white hover:bg-[#152e5a] transition-all shadow-sm">
                                    Gerai <i class="fa-solid fa-arrow-up-right-from-square text-[7px] ml-0.5"></i>
                                </a>
                            </div>
                        </div>
                    `;

                    // Build Leaflet Marker with pulsing divIcon
                    const pulsingIcon = L.divIcon({
                        className: 'pulse-marker-wrapper',
                        html: '<div class="pulse-marker"></div>',
                        iconSize: [32, 32],
                        iconAnchor: [16, 16],
                        popupAnchor: [0, -8]
                    });
                    const marker = L.marker([b.lat, b.lng], { icon: pulsingIcon }).addTo(map);
                    
                    // Generate product items list for map popup
                    let productListHTML = '';
                    b.products.forEach(p => {
                        if (activeCategories.includes(p.category)) {
                            productListHTML += `
                                <div style="display:flex; justify-content:space-between; align-items:center; font-size:9px; padding:3px 0; border-bottom:1px dashed #f1f5f9;">
                                    <span style="font-weight:500; color:#334155;">${p.name}</span>
                                    <span style="font-weight:700; color:#ea580c;">Rp ${p.price.toLocaleString('id-ID')}</span>
                                </div>
                            `;
                        }
                    });

                    const popupContent = `
                        <div style="font-family: 'Inter', sans-serif; padding: 4px; min-width: 190px;">
                            <h4 style="margin: 0 0 2px; font-weight: 800; font-size: 12px; color: #1E293B;">${b.name}</h4>
                            <p style="margin: 0 0 6px; font-size: 9px; color: #64748B;">Desa ${b.village}, Kec. ${b.district}, ${b.province}</p>
                            
                            <div style="margin-bottom: 8px; border: 1px solid #f1f5f9; border-radius: 6px; padding: 4px 6px; background:#fafafa;">
                                <span style="font-size:8px; font-weight:800; color:#94a3b8; text-transform:uppercase;">Produk Unggulan</span>
                                ${productListHTML || '<div style="font-size:9px; color:#94a3b8;">Tidak ada kategori yang dipilih</div>'}
                            </div>

                            <a href="${url}" target="_blank" style="display: block; padding: 5.5px 8px; background: #1B3C73; color: white; border-radius: 6px; font-size: 9px; font-weight: 800; text-decoration: none; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">Kunjungi Toko Mandiri <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 7px; margin-left: 2px;"></i></a>
                        </div>
                    `;

                    marker.bindPopup(popupContent);
                    markersGroup.push(marker);
                    bounds.push([b.lat, b.lng]);
                }
            });

            if (count === 0) {
                html = `
                    <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                        <i class="fa-solid fa-folder-open text-4xl mb-3 text-slate-300"></i>
                        <span class="text-xs font-semibold">Tidak ada BUMDes yang cocok</span>
                        <span class="text-[10px] text-slate-400 mt-1">Coba ubah filter kategori atau kata pencarian.</span>
                    </div>
                `;
            }

            listGrid.innerHTML = html;
            document.getElementById('filteredBumdesCount').innerText = `${count} BUMDes`;

            // Adjust map view to fit new bounds
            if (bounds.length > 0 && map) {
                map.fitBounds(bounds, { padding: [50, 50] });
            }
        }

        // Focus map to clicked BUMDes
        function focusBumdesLocation(lat, lng) {
            if (map) {
                map.setView([lat, lng], 13, { animate: true, duration: 1 });
                // Find and open popup for marker
                markersGroup.forEach(marker => {
                    if (marker.getLatLng().lat === lat && marker.getLatLng().lng === lng) {
                        setTimeout(() => marker.openPopup(), 1000);
                    }
                });
            }
        }

        // Real-time filter BUMDes from search input
        function filterBumdesDirectory() {
            renderDirectory();
        }
    </script>
</body>
</html>
