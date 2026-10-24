<!-- WIDGET PRAKIRAAN CUACA LOKAL OTOMATIS -->
<div class="xosid-glass rounded-3xl p-6 border border-white/5 hover:border-cyan-500/30 transition-all duration-300 relative overflow-hidden group">
    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 via-transparent to-transparent pointer-events-none"></div>
    
    <div class="flex items-center justify-between mb-4">
        <span class="text-slate-500 text-[9px] uppercase font-bold tracking-widest">CUACA LOKAL</span>
        <span class="flex items-center gap-1 text-[9px] font-bold text-cyan-400 bg-cyan-400/10 px-2 py-0.5 rounded-full uppercase">
            <span class="w-1.5 h-1.5 bg-cyan-400 rounded-full animate-pulse"></span> LIVE
        </span>
    </div>

    <!-- Location & Status -->
    <div class="space-y-1">
        <h4 class="text-sm font-bold text-white flex items-center gap-1.5">
            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-cyan-400"></i>
            Desa {{ $desa['nama_desa'] ?? 'Bumi Daya' }}
        </h4>
        <p class="text-[10px] text-slate-500">Kecamatan {{ $desa['nama_kecamatan'] ?? 'Kedaton' }}</p>
    </div>

    <!-- Loader -->
    <div id="weather-loader" class="py-8 flex flex-col items-center justify-center space-y-2">
        <div class="w-6 h-6 border-2 border-cyan-400/20 border-t-cyan-400 rounded-full animate-spin"></div>
        <span class="text-[10px] text-slate-500 font-mono">[CONNECTING WEATHER STATION]</span>
    </div>

    <!-- Weather Content (Initially Hidden) -->
    <div id="weather-content" class="hidden py-4 space-y-4">
        <div class="flex items-center justify-between">
            <div id="weather-icon-placeholder" class="w-16 h-16 flex items-center justify-center bg-white/2 rounded-2xl border border-white/5 group-hover:border-cyan-400/30 transition-all duration-300">
                <!-- Icon will be inserted here -->
            </div>
            <div class="text-right">
                <span id="weather-temp" class="text-3xl font-black text-white leading-none">0°C</span>
                <span id="weather-desc" class="block text-xs font-bold text-slate-300 mt-1">Cerah</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 pt-3 border-t border-white/5 text-[10px]">
            <div class="p-2.5 rounded-xl bg-white/2 border border-white/5 flex items-center gap-2">
                <i data-lucide="wind" class="w-4 h-4 text-cyan-400"></i>
                <div>
                    <span class="block text-slate-500 font-bold uppercase text-[8px]">Angin</span>
                    <span id="weather-wind" class="text-white font-bold">0 km/j</span>
                </div>
            </div>
            <div class="p-2.5 rounded-xl bg-white/2 border border-white/5 flex items-center gap-2">
                <i data-lucide="clock" class="w-4 h-4 text-cyan-400"></i>
                <div>
                    <span class="block text-slate-500 font-bold uppercase text-[8px]">Zona Waktu</span>
                    <span id="weather-time" class="text-white font-bold">WIB</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const lat = parseFloat("{{ $desa['lat'] ?? '-5.3971' }}") || -5.3971;
        const lng = parseFloat("{{ $desa['lng'] ?? '105.2668' }}") || 105.2668;
        
        const weatherCodes = {
            0: { text: "Cerah", icon: "sun" },
            1: { text: "Cerah Berawan", icon: "cloud-sun" },
            2: { text: "Sebagian Berawan", icon: "cloud-sun" },
            3: { text: "Mendung", icon: "cloud" },
            45: { text: "Berkabut", icon: "cloud" },
            48: { text: "Kabut Rime", icon: "cloud" },
            51: { text: "Gerimis Ringan", icon: "cloud-drizzle" },
            53: { text: "Gerimis Sedang", icon: "cloud-drizzle" },
            55: { text: "Gerimis Lebat", icon: "cloud-drizzle" },
            61: { text: "Hujan Ringan", icon: "cloud-rain" },
            63: { text: "Hujan Sedang", icon: "cloud-rain" },
            65: { text: "Hujan Lebat", icon: "cloud-rain" },
            80: { text: "Hujan Shower Ringan", icon: "cloud-rain" },
            81: { text: "Hujan Shower Sedang", icon: "cloud-rain" },
            82: { text: "Hujan Shower Lebat", icon: "cloud-rain" },
            95: { text: "Badai Petir", icon: "cloud-lightning" },
            96: { text: "Badai Petir & Es Ringan", icon: "cloud-lightning" },
            99: { text: "Badai Petir & Es Lebat", icon: "cloud-lightning" }
        };

        const loader = document.getElementById("weather-loader");
        const content = document.getElementById("weather-content");

        fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current_weather=true&timezone=auto`)
            .then(res => res.json())
            .then(data => {
                if (data && data.current_weather) {
                    const current = data.current_weather;
                    const temp = Math.round(current.temperature);
                    const wind = current.windspeed;
                    const code = current.weathercode;
                    
                    const info = weatherCodes[code] || { text: "Cerah Berawan", icon: "cloud-sun" };

                    // Set values
                    document.getElementById("weather-temp").innerText = `${temp}°C`;
                    document.getElementById("weather-desc").innerText = info.text;
                    document.getElementById("weather-wind").innerText = `${wind} km/j`;

                    // Set dynamic time zone label based on longitude
                    let tzLabel = "WIB";
                    if (lng >= 135) tzLabel = "WIT";
                    else if (lng >= 120) tzLabel = "WITA";
                    document.getElementById("weather-time").innerText = tzLabel;

                    // Set Icon
                    document.getElementById("weather-icon-placeholder").innerHTML = `<i data-lucide="${info.icon}" class="w-10 h-10 text-cyan-400"></i>`;
                    
                    // Render icons
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }

                    // Toggle visibility
                    loader.classList.add("hidden");
                    content.classList.remove("hidden");
                } else {
                    throw new Error("Invalid weather payload");
                }
            })
            .catch(err => {
                console.error("Weather fetch failed:", err);
                loader.innerHTML = `
                    <i data-lucide="cloud-off" class="w-8 h-8 text-slate-500 mx-auto"></i>
                    <span class="text-[10px] text-slate-500 font-mono mt-1">[STATION OFFLINE]</span>
                `;
                if (window.lucide) window.lucide.createIcons();
            });
    });
</script>
