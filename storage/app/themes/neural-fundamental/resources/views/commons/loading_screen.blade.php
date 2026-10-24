<div id="neural-loading-screen">
    <div id="neural-loading-overlay" class="fixed inset-0 z-[9999] flex flex-col justify-center items-center bg-slate-950" style="transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1);">
        
        <!-- Ambient Glow -->
        <div class="absolute w-64 h-64 bg-cyan-500/20 rounded-full blur-[80px] animate-pulse"></div>
        <div class="absolute w-64 h-64 bg-purple-500/20 rounded-full blur-[80px] animate-pulse" style="animation-delay: 1s;"></div>

        <div class="relative flex items-center justify-center w-32 h-32">
            <!-- Outer Ring (Dashed, spins slow) -->
            <div class="absolute inset-0 rounded-full border border-cyan-500/30" style="border-style: dashed; animation: spinSlow 8s linear infinite;"></div>
            
            <!-- Middle Ring (Gradient, spins fast, morphs) -->
            <div class="absolute inset-2 rounded-full border-2 border-transparent border-t-cyan-400 border-r-purple-500" style="animation: spinMorph 2s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;"></div>
            
            <!-- Inner Ring (Solid, spins reverse) -->
            <div class="absolute inset-4 rounded-full border border-purple-500/50 border-b-transparent border-l-transparent" style="animation: spinReverse 3s linear infinite;"></div>

            <!-- Central Logo Sphere -->
            <div class="relative w-16 h-16 bg-slate-900/80 backdrop-blur-md rounded-full flex justify-center items-center shadow-[0_0_25px_rgba(6,182,212,0.4)] border border-cyan-500/30 z-10" style="animation: breathe 3s ease-in-out infinite;">
                <span class="text-3xl font-black italic tracking-tighter text-transparent bg-clip-text bg-gradient-to-br from-cyan-300 to-cyan-500" style="text-shadow: 0 0 15px rgba(6,182,212,0.6), 0 0 30px rgba(6,182,212,0.4);">XO</span>
            </div>
        </div>
        
        <!-- Animated Loading Text -->
        <div class="mt-8 text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400 font-semibold tracking-widest text-sm uppercase animate-pulse flex items-center gap-1">
            <span>M</span><span>e</span><span>m</span><span>u</span><span>a</span><span>t</span>
            <span class="text-cyan-400 animate-bounce" style="animation-delay: 0.1s;">.</span>
            <span class="text-cyan-400 animate-bounce" style="animation-delay: 0.2s;">.</span>
            <span class="text-cyan-400 animate-bounce" style="animation-delay: 0.3s;">.</span>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        // Beri sedikit delay agar animasi masuknya sempat terlihat
        setTimeout(function() {
            var loader = document.getElementById('neural-loading-overlay');
            if (loader) {
                loader.style.opacity = '0';
                loader.style.transform = 'scale(1.1)';
                setTimeout(function() {
                    var screen = document.getElementById('neural-loading-screen');
                    if (screen) screen.style.display = 'none';
                }, 800); // Waktu yang sama dengan transisi opacity
            }
        }, 1200);
    });
</script>

<style>
    @keyframes spinSlow {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    @keyframes spinMorph {
        0% { transform: rotate(0deg) scale(0.9); border-radius: 50%; }
        50% { transform: rotate(180deg) scale(1.1); border-radius: 40%; }
        100% { transform: rotate(360deg) scale(0.9); border-radius: 50%; }
    }
    @keyframes spinReverse {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(-360deg); }
    }
    @keyframes breathe {
        0%, 100% { transform: scale(1); box-shadow: 0 0 15px rgba(6,182,212,0.3); }
        50% { transform: scale(1.05); box-shadow: 0 0 35px rgba(6,182,212,0.6); }
    }
</style>