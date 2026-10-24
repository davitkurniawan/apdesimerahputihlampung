@php
if (!function_exists('getSciFiIcon')) {
    function getSciFiIcon($iconName, $menuName) {
        $iconName = strtolower($iconName);
        $menuName = strtolower($menuName);
        
        if (str_contains($iconName, 'peta') || str_contains($menuName, 'peta')) {
            return 'fa-solid fa-satellite';
        }
        if (str_contains($iconName, 'protected') || str_contains($iconName, 'info') || str_contains($menuName, 'informasi')) {
            return 'fa-solid fa-circle-nodes';
        }
        if (str_contains($iconName, 'stat') || str_contains($menuName, 'pekerjaan') || str_contains($menuName, 'data')) {
            return 'fa-solid fa-network-wired';
        }
        if (str_contains($iconName, 'mandiri') || str_contains($menuName, 'mandiri')) {
            return 'fa-solid fa-user-astronaut';
        }
        if (str_contains($iconName, 'lapak') || str_contains($menuName, 'lapak') || str_contains($menuName, 'pasar') || str_contains($menuName, 'dagang')) {
            return 'fa-solid fa-shop';
        }
        if (str_contains($iconName, 'keuangan') || str_contains($menuName, 'keuangan') || str_contains($menuName, 'uang') || str_contains($menuName, 'dana')) {
            return 'fa-solid fa-microchip';
        }
        if (str_contains($iconName, 'idm') || str_contains($menuName, 'idm') || str_contains($menuName, 'index')) {
            return 'fa-solid fa-gauge-high';
        }
        return 'fa-solid fa-compass';
    }
}

if (!function_exists('getSciFiGlowClass')) {
    function getSciFiGlowClass($iconName, $menuName) {
        $iconName = strtolower($iconName);
        $menuName = strtolower($menuName);
        
        if (str_contains($iconName, 'peta') || str_contains($menuName, 'peta')) {
            return 'glow-cyan';
        }
        if (str_contains($iconName, 'protected') || str_contains($iconName, 'info') || str_contains($menuName, 'informasi')) {
            return 'glow-purple';
        }
        if (str_contains($iconName, 'stat') || str_contains($menuName, 'pekerjaan') || str_contains($menuName, 'data')) {
            return 'glow-pink';
        }
        if (str_contains($iconName, 'mandiri') || str_contains($menuName, 'mandiri')) {
            return 'glow-yellow';
        }
        if (str_contains($iconName, 'lapak') || str_contains($menuName, 'lapak') || str_contains($menuName, 'pasar') || str_contains($menuName, 'dagang')) {
            return 'glow-green';
        }
        if (str_contains($iconName, 'keuangan') || str_contains($menuName, 'keuangan') || str_contains($menuName, 'uang') || str_contains($menuName, 'dana')) {
            return 'glow-cyan';
        }
        if (str_contains($iconName, 'idm') || str_contains($menuName, 'idm') || str_contains($menuName, 'index')) {
            return 'glow-yellow';
        }
        return 'glow-cyan';
    }
}
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="{{ $sebutan_anjungan }}">
    <title>{{ $sebutan_anjungan }}</title>
    <link rel="icon" type="image/png" href="{{ $favicon }}" />
    <link rel="shortcut icon" type="image/png" href="{{ $favicon }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Share+Tech+Mono&display=swap');

        :root {
            --neon-cyan: #00f2fe;
            --neon-blue: #4facfe;
            --neon-purple: #a855f7;
            --neon-pink: #ec4899;
            --neon-green: #10b981;
            --neon-yellow: #fbbf24;
            --neon-red: #ef4444;
            --dark-bg: #030712;
            --card-glass: rgba(10, 15, 30, 0.7);
            --border-glass: rgba(0, 242, 254, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            user-select: none;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--dark-bg);
            color: #e2e8f0;
            overflow: hidden;
            height: 100vh;
            perspective: 1000px;
        }

        /* Quantum Holographic Backdrop */
        .kiosk-quantum-bg {
            position: fixed;
            inset: 0;
            z-index: -10;
            overflow: hidden;
            background: radial-gradient(circle at 50% 50%, #081125 0%, #030712 100%);
        }

        /* Retro Sci-fi Grid Overlay */
        .kiosk-quantum-bg::before {
            content: '';
            position: absolute;
            inset: -100px;
            background-image: 
                linear-gradient(rgba(0, 242, 254, 0.05) 1.5px, transparent 1.5px),
                linear-gradient(90deg, rgba(0, 242, 254, 0.05) 1.5px, transparent 1.5px);
            background-size: 60px 60px;
            background-position: center;
            transform: rotateX(45deg) translateZ(-100px);
            animation: grid-scroll 24s linear infinite;
        }

        @keyframes grid-scroll {
            0% { background-position: 0 0; }
            100% { background-position: 0 60px; }
        }

        .ambient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            pointer-events: none;
            opacity: 0.25;
        }
        .orb-cyan {
            top: -10%; left: 10%; width: 500px; height: 500px;
            background: var(--neon-cyan);
            animation: orb-drift 20s infinite alternate ease-in-out;
        }
        .orb-purple {
            bottom: -15%; right: 5%; width: 600px; height: 600px;
            background: var(--neon-purple);
            animation: orb-drift 25s infinite alternate-reverse ease-in-out;
        }
        @keyframes orb-drift {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(60px, 40px) scale(1.2); }
        }

        /* Film Grain Effect */
        .noise-layer {
            position: fixed;
            inset: 0;
            z-index: -9;
            opacity: 0.04;
            pointer-events: none;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }

        /* Scanline Overlays */
        .scanlines {
            position: fixed;
            inset: 0;
            z-index: 999;
            pointer-events: none;
            background: linear-gradient(
                to bottom,
                rgba(255,255,255,0),
                rgba(255,255,255,0) 50%,
                rgba(0, 242, 254, 0.03) 50%,
                rgba(0, 242, 254, 0.03)
            );
            background-size: 100% 4px;
        }

        /* Main Container Layout */
        .kiosk-frame {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            height: 100vh;
            min-height: 100vh;
            overflow: hidden;
            padding: 24px;
            gap: 20px;
        }

        /* Sci-Fi HUD Header */
        .hud-header {
            background: var(--card-glass);
            border: 1px solid var(--border-glass);
            box-shadow: 0 8px 32px 0 rgba(0, 242, 254, 0.05), inset 0 0 16px rgba(0, 242, 254, 0.05);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: 20px;
            padding: 16px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }
        .hud-header::after {
            content: '';
            position: absolute;
            bottom: 0; left: 10%; right: 10%; height: 1px;
            background: linear-gradient(to right, transparent, var(--neon-cyan), transparent);
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
            text-decoration: none;
        }
        .header-logo-container {
            position: relative;
            width: 64px; height: 64px;
        }
        .header-logo-container img {
            width: 100%; height: 100%;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid rgba(0, 242, 254, 0.3);
            filter: drop-shadow(0 0 8px rgba(0, 242, 254, 0.4));
        }
        .header-logo-container::before {
            content: '';
            position: absolute; inset: -4px;
            border-radius: 16px;
            border: 1px solid rgba(0, 242, 254, 0.2);
            animation: pulse-ring 3s infinite;
        }
        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.15); opacity: 0; }
        }

        .header-title-box h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.6rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 2px;
            background: linear-gradient(to right, #ffffff, var(--neon-cyan));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            margin-bottom: 2px;
            filter: drop-shadow(0 2px 8px rgba(0, 242, 254, 0.3));
        }
        .header-title-box p {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.75rem;
            color: #94a3b8;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .status-dot {
            width: 6px; height: 6px;
            background-color: var(--neon-green);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--neon-green);
            display: inline-block;
            animation: blink 1.5s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 1; }
        }

        /* SVG Circular Radar and Telemetry */
        .header-right {
            display: flex;
            align-items: center;
            gap: 32px;
        }
        .telemetry-block {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.7rem;
            color: var(--neon-cyan);
            text-align: right;
            border-right: 2px solid var(--border-glass);
            padding-right: 16px;
            line-height: 1.4;
        }
        .radar-box {
            position: relative;
            width: 54px; height: 54px;
        }
        .radar-box svg {
            width: 100%; height: 100%;
        }

        /* Sub-Header: Information Ticker */
        .hud-sub-ticker {
            background: rgba(10, 15, 30, 0.4);
            border: 1px solid rgba(0, 242, 254, 0.08);
            border-radius: 12px;
            padding: 8px 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            backdrop-filter: blur(8px);
        }
        .ticker-label {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.725rem;
            font-weight: 800;
            color: var(--neon-cyan);
            background: rgba(0, 242, 254, 0.1);
            border: 1px solid var(--border-glass);
            padding: 4px 10px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .ticker-body {
            flex-grow: 1;
            overflow: hidden;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.8rem;
            color: #94a3b8;
            font-weight: 500;
        }
        .ticker-body marquee {
            vertical-align: middle;
        }

        /* Middle Grid Layout */
        .kiosk-grid-middle {
            flex: 1 1 auto;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 20px;
            min-height: 0;
        }

        /* Left Media Box */
        .media-showcase-panel {
            background: var(--card-glass);
            border: 1px solid var(--border-glass);
            box-shadow: 0 8px 32px 0 rgba(0, 242, 254, 0.04);
            backdrop-filter: blur(24px);
            border-radius: 24px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            height: 100%;
        }
        .showcase-frame {
            flex: 1 1 auto;
            width: 100%;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
            background-color: #000;
        }
        .showcase-frame iframe, .showcase-frame video {
            width: 100%; height: 100%;
            border: none;
        }
        .showcase-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.725rem;
            color: #64748b;
            padding: 0 4px;
        }
        .showcase-telemetry {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .showcase-telemetry span {
            color: var(--neon-cyan);
        }

        /* Right Controller / Navigation Grid */
        .controllers-panel {
            display: flex;
            flex-direction: column;
            gap: 20px;
            height: 100%;
        }

        /* Core Services Card Panel */
        .hud-services-glass {
            background: var(--card-glass);
            border: 1px solid var(--border-glass);
            box-shadow: 0 8px 32px 0 rgba(0, 242, 254, 0.04);
            backdrop-filter: blur(24px);
            border-radius: 24px;
            padding: 24px;
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 16px;
            transform-style: preserve-3d;
            perspective: 800px;
        }

        .quantum-service-btn {
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 18px 28px;
            border-radius: 9999px;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.9rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
            transform: translateZ(10px);
        }
        
        .btn-core-glow {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }
        .quantum-service-btn:hover .btn-core-glow {
            transform: translateX(100%);
        }

        .quantum-service-btn.cyber-green {
            background: rgba(16, 185, 129, 0.05);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
            box-shadow: inset 0 0 12px rgba(16, 185, 129, 0.1);
        }
        .quantum-service-btn.cyber-green:hover {
            background: rgba(16, 185, 129, 0.15);
            border-color: rgba(16, 185, 129, 0.8);
            box-shadow: 0 0 25px rgba(16, 185, 129, 0.35), inset 0 0 12px rgba(16, 185, 129, 0.2);
            color: #ffffff;
            transform: translateZ(20px) scale(1.02);
        }
        .quantum-service-btn.cyber-green i {
            color: #34d399;
            filter: drop-shadow(0 0 8px rgba(52, 211, 153, 0.6));
        }

        .quantum-service-btn.cyber-yellow {
            background: rgba(245, 158, 11, 0.05);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #fbbf24;
            box-shadow: inset 0 0 12px rgba(245, 158, 11, 0.1);
        }
        .quantum-service-btn.cyber-yellow:hover {
            background: rgba(245, 158, 11, 0.15);
            border-color: rgba(245, 158, 11, 0.8);
            box-shadow: 0 0 25px rgba(245, 158, 11, 0.35), inset 0 0 12px rgba(245, 158, 11, 0.2);
            color: #ffffff;
            transform: translateZ(20px) scale(1.02);
        }
        .quantum-service-btn.cyber-yellow i {
            color: #fbbf24;
            filter: drop-shadow(0 0 8px rgba(251, 191, 36, 0.6));
        }

        .quantum-service-btn.cyber-cyan {
            background: rgba(0, 242, 254, 0.05);
            border: 1px solid rgba(0, 242, 254, 0.3);
            color: #22d3ee;
            box-shadow: inset 0 0 12px rgba(0, 242, 254, 0.1);
        }
        .quantum-service-btn.cyber-cyan:hover {
            background: rgba(0, 242, 254, 0.15);
            border-color: rgba(0, 242, 254, 0.8);
            box-shadow: 0 0 25px rgba(0, 242, 254, 0.35), inset 0 0 12px rgba(0, 242, 254, 0.2);
            color: #ffffff;
            transform: translateZ(20px) scale(1.02);
        }
        .quantum-service-btn.cyber-cyan i {
            color: var(--neon-cyan);
            filter: drop-shadow(0 0 8px rgba(0, 242, 254, 0.6));
        }

        .quantum-service-btn.cyber-red {
            background: rgba(239, 68, 68, 0.05);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
            box-shadow: inset 0 0 12px rgba(239, 68, 68, 0.1);
        }
        .quantum-service-btn.cyber-red:hover {
            background: rgba(239, 68, 68, 0.15);
            border-color: rgba(239, 68, 68, 0.8);
            box-shadow: 0 0 25px rgba(239, 68, 68, 0.35), inset 0 0 12px rgba(239, 68, 68, 0.2);
            color: #ffffff;
            transform: translateZ(20px) scale(1.02);
        }
        .quantum-service-btn.cyber-red i {
            color: #f87171;
            filter: drop-shadow(0 0 8px rgba(248, 113, 113, 0.6));
        }

        /* Custom Icon Grid inside Controllers */
        .kiosk-icon-grid {
            background: rgba(10, 15, 30, 0.4);
            border: 1px solid rgba(0, 242, 254, 0.08);
            border-radius: 20px;
            padding: 14px;
            overflow-x: auto;
            white-space: nowrap;
            display: flex;
            gap: 12px;
        }
        /* Custom styled scrollbars */
        .kiosk-icon-grid::-webkit-scrollbar { height: 4px; }
        .kiosk-icon-grid::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); }
        .kiosk-icon-grid::-webkit-scrollbar-thumb { background: rgba(0, 242, 254, 0.2); border-radius: 4px; }

        .hex-icon-btn {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(10, 15, 30, 0.6);
            border: 1px solid rgba(0, 242, 254, 0.15);
            border-radius: 16px;
            padding: 10px 14px;
            min-width: 100px;
            text-decoration: none;
            color: #94a3b8;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .hex-icon-btn img {
            width: 38px; height: 38px;
            margin-bottom: 8px;
            filter: drop-shadow(0 0 6px rgba(0, 242, 254, 0.3));
            transition: transform 0.3s ease;
        }
        .hex-icon-btn i {
            font-size: 26px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }
        .hex-icon-btn i.glow-cyan {
            color: var(--neon-cyan) !important;
            filter: drop-shadow(0 0 8px var(--neon-cyan)) !important;
        }
        .hex-icon-btn i.glow-purple {
            color: var(--neon-purple) !important;
            filter: drop-shadow(0 0 8px var(--neon-purple)) !important;
        }
        .hex-icon-btn i.glow-pink {
            color: var(--neon-pink) !important;
            filter: drop-shadow(0 0 8px var(--neon-pink)) !important;
        }
        .hex-icon-btn i.glow-yellow {
            color: var(--neon-yellow) !important;
            filter: drop-shadow(0 0 8px var(--neon-yellow)) !important;
        }
        .hex-icon-btn i.glow-green {
            color: var(--neon-green) !important;
            filter: drop-shadow(0 0 8px var(--neon-green)) !important;
        }
        .hex-icon-btn span {
            font-family: 'Outfit', sans-serif;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #cbd5e1;
        }
        .hex-icon-btn:hover {
            background: rgba(0, 242, 254, 0.12);
            border-color: rgba(0, 242, 254, 0.6);
            box-shadow: 0 0 15px rgba(0, 242, 254, 0.25);
            transform: translateY(-3px);
            color: #ffffff;
        }
        .hex-icon-btn:hover img {
            transform: scale(1.12);
        }

        /* Integrated Footer Operations Bar */
        .hud-footer-ops {
            background: var(--card-glass);
            border: 1px solid var(--border-glass);
            box-shadow: 0 -8px 32px 0 rgba(0, 242, 254, 0.05);
            backdrop-filter: blur(24px);
            border-radius: 20px;
            padding: 12px 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
        }

        .cyber-ops-card {
            background: rgba(10, 15, 30, 0.6);
            border: 1px solid rgba(0, 242, 254, 0.15);
            padding: 8px 16px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #cbd5e1;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-width: 135px;
            justify-content: center;
        }
        .cyber-ops-card i {
            font-size: 20px;
            color: var(--neon-cyan);
            filter: drop-shadow(0 0 4px rgba(0, 242, 254, 0.4));
            transition: transform 0.3s ease;
        }
        .cyber-ops-card span {
            font-family: 'Outfit', sans-serif;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.2;
            text-align: left;
        }
        .cyber-ops-card:hover {
            background: rgba(0, 242, 254, 0.15);
            border-color: rgba(0, 242, 254, 0.7);
            box-shadow: 0 0 15px rgba(0, 242, 254, 0.3);
            color: #ffffff;
            transform: translateY(-2px);
        }
        .cyber-ops-card:hover i {
            transform: scale(1.2);
            color: #ffffff;
            filter: drop-shadow(0 0 8px rgba(0, 242, 254, 0.8));
        }

        /* Custom Quantum Sci-Fi Modals */
        .modal-content {
            background: rgba(4, 8, 18, 0.95);
            border: 1px solid var(--neon-cyan);
            box-shadow: 0 0 30px rgba(0, 242, 254, 0.2), inset 0 0 15px rgba(0, 242, 254, 0.1);
            backdrop-filter: blur(24px);
            border-radius: 20px;
            overflow: hidden;
        }
        .modal-header {
            border-bottom: 1px solid rgba(0, 242, 254, 0.2);
            padding: 18px 24px;
        }
        .modal-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--neon-cyan);
            font-size: 1.2rem;
            filter: drop-shadow(0 0 8px rgba(0, 242, 254, 0.5));
        }
        .modal-body {
            padding: 24px;
        }
        .modal-footer {
            border-top: 1px solid rgba(0, 242, 254, 0.1);
            padding: 14px 24px;
        }

        /* Sci-Fi Input Form fields */
        .cyber-input {
            background: rgba(10, 15, 30, 0.8);
            border: 1px solid rgba(0, 242, 254, 0.2);
            color: #f1f5f9;
            padding: 12px 18px;
            border-radius: 12px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }
        .cyber-input:focus {
            background: rgba(10, 15, 30, 0.95);
            border-color: var(--neon-cyan);
            box-shadow: 0 0 15px rgba(0, 242, 254, 0.3), inset 0 0 6px rgba(0, 242, 254, 0.1);
            color: #ffffff;
            outline: none;
        }
        .cyber-label {
            font-family: 'Outfit', sans-serif;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #cbd5e1;
            margin-bottom: 8px;
            display: inline-block;
        }

        /* Biometric Fingerprint Visualizer */
        .fingerprint-scanner-box {
            position: relative;
            width: 140px; height: 140px;
            margin: 0 auto 20px;
            border: 2px solid rgba(0, 242, 254, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: rgba(10, 15, 30, 0.6);
            transition: all 0.3s ease;
        }
        .fingerprint-scanner-box svg {
            width: 80px; height: 80px;
            fill: none;
            stroke: var(--neon-cyan);
            stroke-width: 1.5px;
            stroke-linecap: round;
            filter: drop-shadow(0 0 6px var(--neon-cyan));
            transition: all 0.3s ease;
        }
        .scanner-laser-line {
            position: absolute;
            left: 10%; right: 10%; height: 2px;
            background: linear-gradient(90deg, transparent, var(--neon-cyan), transparent);
            box-shadow: 0 0 10px var(--neon-cyan), 0 0 20px var(--neon-cyan);
            opacity: 0;
            pointer-events: none;
        }
        /* Scanning Active states */
        .fingerprint-scanner-box.scanning {
            border-color: var(--neon-cyan);
            box-shadow: 0 0 20px rgba(0, 242, 254, 0.4), inset 0 0 15px rgba(0, 242, 254, 0.2);
        }
        .fingerprint-scanner-box.scanning svg {
            stroke: var(--neon-green);
            filter: drop-shadow(0 0 10px var(--neon-green));
        }
        .fingerprint-scanner-box.scanning .scanner-laser-line {
            opacity: 1;
            animation: laser-sweep 2s infinite ease-in-out;
        }
        @keyframes laser-sweep {
            0%, 100% { top: 20%; }
            50% { top: 80%; }
        }

        .scanning-log {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.75rem;
            color: var(--neon-cyan);
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(0, 242, 254, 0.1);
            padding: 8px 12px;
            border-radius: 8px;
            height: 64px;
            overflow-y: hidden;
            margin-bottom: 20px;
            text-align: left;
            line-height: 1.5;
        }

        /* Quantum Pamong Grid Cards */
        .pamong-cyber-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 16px;
            max-height: 50vh;
            overflow-y: auto;
            padding-right: 8px;
        }
        .pamong-cyber-grid::-webkit-scrollbar { width: 4px; }
        .pamong-cyber-grid::-webkit-scrollbar-thumb { background: rgba(0, 242, 254, 0.2); border-radius: 4px; }

        .pamong-cyber-card {
            background: rgba(10, 15, 30, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 16px;
            text-align: center;
            position: relative;
            transition: all 0.3s ease;
        }
        .pamong-avatar-frame {
            width: 72px; height: 72px;
            margin: 0 auto 12px;
            border-radius: 50%;
            padding: 4px;
            background: linear-gradient(135deg, var(--neon-cyan), var(--neon-blue));
            box-shadow: 0 0 10px rgba(0, 242, 254, 0.3);
            position: relative;
        }
        .pamong-avatar-frame img {
            width: 100%; height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 1.5px solid #000;
        }
        .pamong-avatar-frame::after {
            content: 'ON DUTY';
            position: absolute;
            bottom: -6px; left: 50%;
            transform: translateX(-50%);
            background: var(--neon-green);
            color: #000;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.55rem;
            font-weight: 900;
            padding: 1px 6px;
            border-radius: 4px;
            box-shadow: 0 0 8px var(--neon-green);
        }
        .pamong-cyber-card h6 {
            font-family: 'Outfit', sans-serif;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--neon-cyan);
            margin-bottom: 4px;
        }
        .pamong-cyber-card p {
            font-size: 0.7rem;
            color: #94a3b8;
            margin-bottom: 0;
            font-weight: 500;
            line-height: 1.3;
        }
        .pamong-cyber-card:hover {
            border-color: rgba(0, 242, 254, 0.4);
            box-shadow: 0 0 20px rgba(0, 242, 254, 0.15);
            background: rgba(0, 242, 254, 0.05);
            transform: translateY(-4px);
        }

        /* ========================================
           MOBILE RESPONSIVE OVERRIDES
           Fixes: layout squish, z-index, popup
        ======================================== */
        @media (max-width: 768px) {
            /* Body: allow scrolling on mobile */
            body {
                overflow: auto !important;
                height: auto !important;
                min-height: 100vh;
            }

            /* Reduce background grid perspective — avoids side gaps */
            .kiosk-quantum-bg::before {
                transform: none;
                background-size: 40px 40px;
                animation: none;
            }

            /* Main frame: single column, scrollable, tighter padding */
            .kiosk-frame {
                padding: 10px !important;
                gap: 10px !important;
                height: auto !important;
                min-height: 100dvh;
                overflow: visible !important;
                flex-direction: column;
            }

            /* Header: simpler and compact on mobile */
            .hud-header {
                padding: 10px 14px !important;
                border-radius: 14px !important;
                position: relative;
                z-index: 20;
            }
            /* Hide telemetry + radar on mobile — save space */
            .header-right {
                display: none !important;
            }
            .header-logo-container {
                width: 42px !important;
                height: 42px !important;
            }
            .header-title-box h1 {
                font-size: 0.95rem !important;
                letter-spacing: 0.5px !important;
            }
            .header-title-box p {
                font-size: 0.58rem !important;
            }

            /* Ticker: compact */
            .hud-sub-ticker {
                padding: 6px 10px !important;
                gap: 8px !important;
                border-radius: 10px !important;
            }
            .ticker-label {
                font-size: 0.6rem !important;
                padding: 3px 8px !important;
            }
            .ticker-body {
                font-size: 0.72rem !important;
            }
            .datetime-box {
                display: none;
            }

            /* Grid: stack to single column on mobile */
            .kiosk-grid-middle {
                grid-template-columns: 1fr !important;
                height: auto !important;
                min-height: 0 !important;
            }

            /* Hide the video/media panel on mobile — not useful */
            .media-showcase-panel {
                display: none !important;
            }

            /* Services glass: full width, tighter */
            .controllers-panel {
                height: auto !important;
                gap: 10px !important;
            }
            .hud-services-glass {
                padding: 14px !important;
                gap: 10px !important;
                border-radius: 16px !important;
            }
            .quantum-service-btn {
                padding: 14px 18px !important;
                font-size: 0.78rem !important;
                letter-spacing: 0.5px !important;
                border-radius: 50px !important;
            }

            /* Icon grid: wrap instead of horizontal scroll so items
               are visible and NOT covered by header */
            .kiosk-icon-grid {
                flex-wrap: wrap !important;
                overflow-x: hidden !important;
                white-space: normal !important;
                justify-content: center !important;
                gap: 8px !important;
                padding: 10px !important;
                border-radius: 14px !important;
                position: relative;
                z-index: 15;
            }
            .hex-icon-btn {
                min-width: 76px !important;
                padding: 8px 6px !important;
                border-radius: 12px !important;
            }
            .hex-icon-btn i {
                font-size: 22px !important;
                margin-bottom: 6px !important;
            }
            .hex-icon-btn span {
                font-size: 0.58rem !important;
                letter-spacing: 0.2px !important;
            }

            /* Footer ops: wrap to 2×3 grid on mobile */
            .hud-footer-ops {
                flex-wrap: wrap !important;
                padding: 10px 8px !important;
                gap: 8px !important;
                border-radius: 14px !important;
                justify-content: center !important;
            }
            .cyber-ops-card {
                flex: 0 0 calc(33.33% - 8px) !important;
                min-width: 90px !important;
                max-width: 120px !important;
                padding: 10px 6px !important;
                border-radius: 10px !important;
                gap: 6px !important;
            }
            .cyber-ops-card i {
                font-size: 18px !important;
            }
            .cyber-ops-card span {
                font-size: 0.58rem !important;
                letter-spacing: 0.2px !important;
            }

            /* Activation screen: scrollable + full width on mobile */
            #activation-screen {
                overflow-y: auto !important;
                padding: 16px !important;
                align-items: flex-start !important;
                justify-content: flex-start !important;
            }
            #activation-screen > div {
                max-width: 100% !important;
                width: 100% !important;
                padding: 24px 16px !important;
                border-radius: 16px !important;
                margin-top: 10px !important;
                margin-bottom: 20px !important;
            }
            #activation-code-display {
                font-size: 1.4rem !important;
                letter-spacing: 3px !important;
                padding: 12px !important;
            }

            /* Bootstrap modals: fit mobile screen */
            .modal-dialog {
                margin: 8px !important;
                max-width: calc(100vw - 16px) !important;
            }
            .modal-content {
                border-radius: 14px !important;
            }
            .modal-body {
                padding: 16px !important;
                overflow-y: auto;
                max-height: 70vh;
            }
            .modal-header {
                padding: 14px 16px !important;
            }
            .modal-footer {
                padding: 10px 16px !important;
            }
            .modal-title {
                font-size: 0.95rem !important;
            }

            /* Pamong grid: 2 columns on mobile */
            .pamong-cyber-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                max-height: 55vh !important;
            }
            .pamong-cyber-card {
                padding: 12px 8px !important;
            }
            .pamong-avatar-frame {
                width: 56px !important;
                height: 56px !important;
            }

            /* Fingerprint scanner: smaller on mobile */
            .fingerprint-scanner-box {
                width: 110px !important;
                height: 110px !important;
            }
            .fingerprint-scanner-box svg {
                width: 60px !important;
                height: 60px !important;
            }
        }

        /* Extra small screens (e.g. 360px wide) */
        @media (max-width: 400px) {
            .cyber-ops-card {
                flex: 0 0 calc(50% - 8px) !important;
            }
            .hex-icon-btn {
                min-width: 68px !important;
            }
        }
    </style>
</head>
<body>
    <div class="kiosk-quantum-bg">
        <div class="ambient-orb orb-cyan"></div>
        <div class="ambient-orb orb-purple"></div>
        <div class="noise-layer"></div>
        <div class="scanlines"></div>
    </div>

    <div class="kiosk-frame" id="element">
        <!-- HUD Header -->
        <div class="hud-header">
            <a href="#" class="header-left" onclick="playClick();">
                <div class="header-logo-container">
                    <img src="{{ $logo_url }}" alt="logo">
                </div>
                <div class="header-title-box">
                    <h1>{{ $sebutan_anjungan }}</h1>
                    <p><span class="status-dot"></span> {{ $nama_lengkap_desa }}</p>
                </div>
            </a>
            <div class="header-right">
                <div class="telemetry-block">
                    SYSTEM STATUS: ACTIVE<br>
                    GPS: {{ $sebutan_pemerintah }}<br>
                    SIGNAL: STABLE 99.8%
                </div>
                <div class="radar-box">
                    <!-- Circular active vector HUD radar -->
                    <svg viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="rgba(0, 242, 254, 0.15)" stroke-width="2" />
                        <circle cx="50" cy="50" r="30" fill="none" stroke="rgba(0, 242, 254, 0.1)" stroke-width="1" />
                        <circle cx="50" cy="50" r="15" fill="none" stroke="rgba(0, 242, 254, 0.1)" stroke-width="1" />
                        <!-- Radar Sweep Sweep line -->
                        <line x1="50" y1="50" x2="50" y2="5" stroke="var(--neon-cyan)" stroke-width="1.5" stroke-linecap="round">
                            <animateTransform 
                                attributeName="transform" 
                                type="rotate" 
                                from="0 50 50" 
                                to="360 50 50" 
                                dur="4s" 
                                repeatCount="indefinite" />
                        </line>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Information Ticker -->
        <div class="hud-sub-ticker">
            <div class="ticker-label">
                <i class="fa-solid fa-satellite-dish"></i> BROADCAST
            </div>
            <div class="ticker-body">
                <marquee onmouseover="this.stop()" onmouseout="this.start()">{{ $teks_berjalan }}</marquee>
            </div>
            <div class="datetime-box" style="font-family: 'Share Tech Mono', monospace; font-size: 0.85rem; color: var(--neon-cyan); letter-spacing: 0.5px; border-left: 2px solid var(--border-glass); padding-left: 16px;">
                <span id="tanggal"></span> <span id="thistime" style="font-weight: 800; font-size: 0.95rem; margin-left: 8px;"></span>
            </div>
        </div>

        <!-- Middle Content Grid -->
        <div class="kiosk-grid-middle">
            <!-- Left Showcase Panel -->
            <div class="media-showcase-panel">
                <div class="showcase-frame">
                    @if ($anjungan_profil == 3 && $anjungan_youtube)
                        <iframe
                            id="yt-video"
                            src="https://www.youtube.com/embed/{{ $anjungan_youtube }}?autoplay=1&controls=1&mute=1&rel=0&enablejsapi=1"
                            allow="autoplay; encrypted-media"
                            allowfullscreen></iframe>
                    @elseif ($anjungan_profil == 2 && $anjungan_video)
                        <video autoplay loop muted>
                            <source src="{{ $anjungan_video }}" type="video/mp4">
                        </video>
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center" style="color: #64748b;">
                            <i class="fa-solid fa-photo-film" style="font-size: 54px; margin-bottom: 16px; color: var(--neon-cyan); opacity: 0.5;"></i>
                            <p style="font-family: 'Share Tech Mono', monospace; font-size: 0.85rem;">QUANTUM MEDIA FEED UNAVAILABLE</p>
                        </div>
                    @endif
                </div>
                <div class="showcase-footer">
                    <div class="showcase-telemetry">
                        FEED: <span>ONLINE</span> | RES: <span>1080P</span> | HZ: <span>60</span>
                    </div>
                    <div>SECURE KIOSK TERMINAL v4.2</div>
                </div>
            </div>

            <!-- Right Controller Operations -->
            <div class="controllers-panel">
                <!-- Glowing Service glass container -->
                <div class="hud-services-glass" id="services-card">
                    <a href="/index.php/anjungan-mandiri/surat" class="quantum-service-btn cyber-green" onmouseenter="playHoverChime();" onclick="playClick();">
                        <div class="btn-core-glow"></div>
                        <span><i class="fa-solid fa-file-signature me-3"></i> Pelayanan Surat</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                    
                    <a href="/index.php/anjungan-mandiri/permohonan" class="quantum-service-btn cyber-yellow" onmouseenter="playHoverChime();" onclick="playClick();">
                        <div class="btn-core-glow"></div>
                        <span><i class="fa-solid fa-print me-3"></i> Lacak Permohonan</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>

                    <a href="/index.php/anjungan-mandiri/beranda" class="quantum-service-btn cyber-cyan" onmouseenter="playHoverChime();" onclick="playClick();">
                        <div class="btn-core-glow"></div>
                        <span><i class="fa-solid fa-rotate-left me-3"></i> Reset Terminal</span>
                        <i class="fa-solid fa-arrows-spin"></i>
                    </a>

                    <a href="#" onclick="playClick(); if(confirm('Keluar dari Kiosk Publik?')) window.location.href='/index.php';" class="quantum-service-btn cyber-red" onmouseenter="playHoverChime();">
                        <div class="btn-core-glow"></div>
                        <span><i class="fa-solid fa-power-off me-3"></i> Matikan Layanan</span>
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>

                <!-- Custom Horizontal App Slider inside Grid -->
                @if (count($menu) > 0)
                <div class="kiosk-icon-grid">
                    @foreach ($menu as $item)
                        <a class="hex-icon-btn popup-link" data-url="{{ $item->link }}" onmouseenter="playHoverChime();" onclick="playClick();">
                            <i class="{{ getSciFiIcon($item->icon, $item->nama) }} {{ getSciFiGlowClass($item->icon, $item->nama) }}"></i>
                            <span>{{ $item->nama }}</span>
                        </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Footer Operations Bar -->
        <div class="hud-footer-ops">
            <a class="cyber-ops-card" onmouseenter="playHoverChime();" onclick="playClick();" data-bs-toggle="modal" data-bs-target="#biometricModal">
                <i class="fa-solid fa-fingerprint"></i>
                <span>Absensi<br>Pamong</span>
            </a>
            <a class="cyber-ops-card" onmouseenter="playHoverChime();" onclick="playClick();" data-bs-toggle="modal" data-bs-target="#guestbookModal">
                <i class="fa-solid fa-book-open"></i>
                <span>Buku<br>Tamu</span>
            </a>
            <a class="cyber-ops-card" onmouseenter="playHoverChime();" onclick="playClick();" data-bs-toggle="modal" data-bs-target="#aparaturModal">
                <i class="fa-solid fa-users-viewfinder"></i>
                <span>Aparatur<br>Desa</span>
            </a>
            <a href="/index.php/anjungan-mandiri/beranda" class="cyber-ops-card" onmouseenter="playHoverChime();" onclick="playClick();">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Layanan<br>Mandiri</span>
            </a>
            <div class="cyber-ops-card" id="openfull" onclick="openFullscreen(); playClick();" onmouseenter="playHoverChime();">
                <i class="fa-solid fa-expand"></i>
                <span>Layar<br>Penuh</span>
            </div>
            <div class="cyber-ops-card" id="exitfull" onclick="closeFullscreen(); playClick();" onmouseenter="playHoverChime();" style="display:none;">
                <i class="fa-solid fa-compress"></i>
                <span>Perkecil<br>Layar</span>
            </div>
        </div>
    </div>

    <!-- Activation required Screen (Overlay) -->
    <div class="kiosk-frame flex-column align-items-center justify-content-center" id="activation-screen" style="display: none; padding: 40px; text-align: center; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 99999; background: rgba(4, 8, 18, 0.95);">
        <div class="hud-services-glass" style="max-width: 500px; padding: 40px; border-radius: 24px; box-shadow: 0 0 30px rgba(0, 242, 254, 0.15); background: rgba(10, 15, 30, 0.6); border: 1px solid rgba(0, 242, 254, 0.15);">
            <div class="d-flex justify-content-center mb-4">
                <div class="pamong-avatar-frame" style="width: 80px; height: 80px; box-shadow: 0 0 20px rgba(0, 242, 254, 0.4); display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--neon-cyan), var(--neon-blue)); border-radius: 50%;">
                    <img src="/assets/images/xosid_logo.png" alt="logo" style="width: 50px; height: 50px; object-fit: contain;">
                </div>
            </div>
            <h2 style="font-family: 'Outfit', sans-serif; font-weight: 900; text-transform: uppercase; color: var(--neon-cyan); letter-spacing: 1.5px; filter: drop-shadow(0 0 8px rgba(0,242,254,0.4)); margin-bottom: 8px;">Aktivasi Gawai Diperlukan</h2>
            <p style="font-size: 0.85rem; color: #94a3b8; margin-bottom: 24px;">Silakan daftarkan perangkat kiosk ini di Dashboard Admin Desa.</p>
            
            <div class="scanning-log" style="font-size: 1.8rem; font-weight: 900; letter-spacing: 4px; color: var(--neon-yellow); background: rgba(0, 0, 0, 0.4); border: 1px solid rgba(250, 204, 21, 0.2); padding: 16px; border-radius: 12px; height: auto; text-align: center; margin-bottom: 24px; font-family: 'Share Tech Mono', monospace; line-height: 1.2;" id="activation-code-display">
                LOADING...
            </div>
            
            <div style="font-family: 'Share Tech Mono', monospace; font-size: 0.75rem; color: var(--neon-cyan);">
                <span class="status-dot" style="background: var(--neon-cyan); animation: pulse 1.5s infinite; display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 6px;"></span> MENUNGGU AKTIVASI DARI DASHBOARD ADMIN...
            </div>
        </div>
    </div>

    <!-- Modal 1: Absensi Biometrik Hologram -->
    <div class="modal fade" id="biometricModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-fingerprint me-2"></i> Pemindaian Biometrik</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="playErrorSound();"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-4 d-flex flex-column text-start">
                        <label class="cyber-label" for="pamong-select">Pilih Aparatur Desa</label>
                        <select class="cyber-input" id="pamong-select" style="width: 100%; border-radius: 12px; font-family: 'Plus Jakarta Sans', sans-serif;">
                            <option value="">-- Pilih Nama Anda --</option>
                            @foreach ($pamong as $data_pamong)
                                <option value="{{ $data_pamong['id'] }}">
                                    {{ $data_pamong['nama'] }} ({{ $data_pamong['jabatan'] }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <p style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 24px;">Tekan dan Tahan ikon sidik jari di bawah untuk memverifikasi kehadiran.</p>
                    
                    <div class="fingerprint-scanner-box" id="scanner-pad" onmousedown="startScanning();" onmouseup="stopScanning();" onmouseleave="stopScanning();" ontouchstart="startScanning();" ontouchend="stopScanning();">
                        <svg viewBox="0 0 100 100">
                            <path d="M50 15c-18 0-32 14-32 32 0 10 4 19 11 25.5M50 25c-12 0-22 10-22 22 0 8.5 4 16 10 20.5M50 35c-6.5 0-12 5.5-12 12 0 6 3 11 8.5 13M50 45c-1.5 0-2.5 1-2.5 2.5v10" />
                            <path d="M50 7.5C28 7.5 10 25.5 10 47.5M50 82.5c2 0 3.5 1.5 3.5 3.5S52 89.5 50 89.5M40 76.5c0-1.5 1-2.5 2.5-2.5s2.5 1 2.5 2.5M60 76.5c0-1.5-1-2.5-2.5-2.5s-2.5 1-2.5 2.5M30 67.5c0 1.5-1 2.5-2.5 2.5S25 69 25 67.5s1-2.5 2.5-2.5M70 67.5c0 1.5-1 2.5-2.5 2.5s-2.5-1.5-2.5-2.5 1-2.5 2.5-2.5" />
                        </svg>
                        <div class="scanner-laser-line"></div>
                    </div>

                    <div class="scanning-log" id="scanner-log-feed">
                        [SYS]: PEMINDAI MEMORI BIOMETRIK AKTIF...
                    </div>

                    <div id="pin-confirmation-section" style="display:none; margin-top:20px;">
                        <div class="mb-3 d-flex flex-column text-start">
                            <label class="cyber-label" for="pamong-pin-input">PIN Keamanan / NIK</label>
                            <input type="password" class="cyber-input text-center" id="pamong-pin-input" placeholder="••••••" style="font-size: 1.5rem; letter-spacing: 6px; border-radius: 12px;" maxlength="20">
                            <div style="font-size:0.65rem; color:#64748b; margin-top:4px;">*Jika PIN kosong di database, gunakan NIK Anda atau default '123456' untuk uji coba.</div>
                        </div>
                        <button type="button" class="btn btn-sm w-100" onclick="submitAttendance();"
                            style="background:rgba(0, 242, 254, 0.15); border:1px solid rgba(0, 242, 254, 0.4); color:var(--neon-cyan); font-weight:700; text-transform:uppercase; font-size:0.8rem; border-radius:12px; padding: 10px 16px;">
                            Konfirmasi Kehadiran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 2: Form Buku Tamu Hologram -->
    <div class="modal fade" id="guestbookModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-feather-pointed me-2"></i> Lembaran Buku Tamu Digital</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="playErrorSound();"></button>
                </div>
                <form id="guestbook-form" onsubmit="submitGuestbook(event);">
                    <div class="modal-body">
                        <div class="mb-3 d-flex flex-column">
                            <label class="cyber-label" for="guest-name">Nama Pengunjung</label>
                            <input type="text" class="cyber-input" id="guest-name" placeholder="Masukkan nama lengkap Anda" required autocomplete="off">
                        </div>
                        <div class="mb-3 d-flex flex-column">
                            <label class="cyber-label" for="guest-address">Alamat Asal</label>
                            <input type="text" class="cyber-input" id="guest-address" placeholder="Contoh: RT 02 / RW 04" required autocomplete="off">
                        </div>
                        <div class="mb-3 d-flex flex-column">
                            <label class="cyber-label" for="guest-need">Maksud & Keperluan</label>
                            <textarea class="cyber-input" id="guest-need" rows="3" placeholder="Tuliskan tujuan kunjungan Anda" required autocomplete="off"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm" data-bs-dismiss="modal" onclick="playErrorSound();"
                            style="background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); color:#f87171; font-weight:700; text-transform:uppercase; font-size:0.7rem; border-radius:8px; padding: 6px 16px;">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-sm"
                            style="background:rgba(0, 242, 254, 0.15); border:1px solid rgba(0, 242, 254, 0.4); color:var(--neon-cyan); font-weight:700; text-transform:uppercase; font-size:0.7rem; border-radius:8px; padding: 6px 16px;">
                            Kirim & Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal 3: Aparatur Roster Roster -->
    <div class="modal fade" id="aparaturModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-users me-2"></i> Struktur Aparatur Pemerintah Desa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="playErrorSound();"></button>
                </div>
                <div class="modal-body">
                    <div class="pamong-cyber-grid">
                        @if (count($pamong) > 0)
                            @foreach ($pamong as $data)
                                <div class="pamong-cyber-card" onmouseenter="playHoverChime();">
                                    <div class="pamong-avatar-frame">
                                        <img src="{{ $data['foto'] }}" alt="{{ $data['nama'] }}">
                                    </div>
                                    <h6>{{ $data['jabatan'] }}</h6>
                                    <p>{{ $data['nama'] }}</p>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center w-100 py-4">
                                <p style="color:#94a3b8; font-family: 'Share Tech Mono', monospace; font-size: 0.85rem;">[NO RECOGNIZED OFFICERS FOUND IN DATABANK]</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Web Audio API Synthesizer Context
        let audioCtx = null;

        function initAudio() {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
        }

        // Play Ascending Chime for Hovers
        function playHoverChime() {
            try {
                initAudio();
                if (audioCtx.state === 'suspended') audioCtx.resume();
                
                const time = audioCtx.currentTime;
                // Oscillator 1
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                
                osc.type = 'sine';
                osc.frequency.setValueAtTime(800, time);
                osc.frequency.exponentialRampToValueAtTime(1400, time + 0.12);
                
                gain.gain.setValueAtTime(0.02, time);
                gain.gain.exponentialRampToValueAtTime(0.001, time + 0.12);
                
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                
                osc.start(time);
                osc.stop(time + 0.12);
            } catch (e) {
                // Ignore audio fails if blocked by browser autoplay rules
            }
        }

        // Play Crisp Click sound on tap
        function playClick() {
            try {
                initAudio();
                if (audioCtx.state === 'suspended') audioCtx.resume();

                const time = audioCtx.currentTime;
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(400, time);
                osc.frequency.setValueAtTime(120, time + 0.04);
                
                gain.gain.setValueAtTime(0.08, time);
                gain.gain.exponentialRampToValueAtTime(0.001, time + 0.08);
                
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                
                osc.start(time);
                osc.stop(time + 0.08);
            } catch (e) {}
        }

        // Play error/close tone
        function playErrorSound() {
            try {
                initAudio();
                if (audioCtx.state === 'suspended') audioCtx.resume();

                const time = audioCtx.currentTime;
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(320, time);
                osc.frequency.linearRampToValueAtTime(180, time + 0.15);
                
                gain.gain.setValueAtTime(0.04, time);
                gain.gain.exponentialRampToValueAtTime(0.001, time + 0.15);
                
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                
                osc.start(time);
                osc.stop(time + 0.15);
            } catch (e) {}
        }

        // Play Absen Scan complete ping
        function playSuccessPing() {
            try {
                initAudio();
                if (audioCtx.state === 'suspended') audioCtx.resume();

                const time = audioCtx.currentTime;
                const osc1 = audioCtx.createOscillator();
                const osc2 = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(523.25, time); // C5
                osc1.frequency.setValueAtTime(659.25, time + 0.08); // E5
                osc1.frequency.setValueAtTime(783.99, time + 0.16); // G5
                osc1.frequency.setValueAtTime(1046.50, time + 0.24); // C6
                
                osc2.type = 'triangle';
                osc2.frequency.setValueAtTime(1046.50, time);
                
                gain.gain.setValueAtTime(0.06, time);
                gain.gain.exponentialRampToValueAtTime(0.001, time + 0.5);
                
                osc1.connect(gain);
                osc2.connect(gain);
                gain.connect(audioCtx.destination);
                
                osc1.start(time);
                osc2.start(time);
                osc1.stop(time + 0.5);
                osc2.stop(time + 0.5);
            } catch (e) {}
        }

        // Clock & Date Updates
        function updateClock() {
            var now = new Date();
            var days = ['MINGGU','SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU'];
            var months = ['JANUARI','FEBRUARI','MARET','APRIL','MEI','JUNI','JULI','AGUSTUS','SEPTEMBER','OKTOBER','NOVEMBER','DESEMBER'];
            document.getElementById('tanggal').textContent = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
            document.getElementById('thistime').textContent = now.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit', second: '2-digit'});
        }
        updateClock();
        setInterval(updateClock, 1000);

        // Fullscreen Controls
        function openFullscreen() {
            var el = document.documentElement;
            if (el.requestFullscreen) el.requestFullscreen();
            else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
            else if (el.msRequestFullscreen) el.msRequestFullscreen();
            document.getElementById('openfull').style.display = 'none';
            document.getElementById('exitfull').style.display = 'inline-flex';
        }
        function closeFullscreen() {
            if (document.exitFullscreen) document.exitFullscreen();
            else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
            else if (document.msExitFullscreen) document.msExitFullscreen();
            document.getElementById('openfull').style.display = 'inline-flex';
            document.getElementById('exitfull').style.display = 'none';
        }

        // Popup Window Opener for Menu Grid items
        // On mobile: navigate directly (window.open blocked / off-screen)
        // On desktop: open floating popup
        document.querySelectorAll('.popup-link').forEach(function(el) {
            el.addEventListener('click', function(e) {
                e.preventDefault();
                var url = this.getAttribute('data-url');
                if (!url) return;
                var fullUrl = '/index.php/' + url;
                if (window.innerWidth <= 768) {
                    // Mobile: navigate to page directly
                    window.location.href = fullUrl;
                } else {
                    // Desktop: floating popup window
                    window.open(fullUrl, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=200,width=700,height=650');
                }
            });
        });

        // 3D Tilt Hover effect on Services Cards
        const card = document.getElementById('services-card');
        if (card) {
            card.addEventListener('mousemove', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const xc = rect.width / 2;
                const yc = rect.height / 2;
                const tiltX = (yc - y) / 14;
                const tiltY = (x - xc) / 14;
                this.style.transform = `rotateX(${tiltX}deg) rotateY(${tiltY}deg)`;
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'rotateX(0deg) rotateY(0deg)';
            });
        }

        // --- KIOSK ACTIVATION LOGIC ---
        let activationPollInterval = null;
        function checkActivationState() {
            const token = localStorage.getItem('kiosk_token');
            const activationScreen = document.getElementById('activation-screen');
            const mainKioskElement = document.getElementById('element');

            if (!token) {
                if (mainKioskElement) mainKioskElement.style.display = 'none';
                if (activationScreen) activationScreen.style.display = 'flex';
                fetchActivationCode();
            } else {
                if (mainKioskElement) mainKioskElement.style.display = 'flex';
                if (activationScreen) activationScreen.style.display = 'none';
            }
        }

        function fetchActivationCode() {
            fetch('/api/kiosk/get-code')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success' && data.code) {
                        const display = document.getElementById('activation-code-display');
                        if (display) display.textContent = data.code;
                        startActivationPolling(data.code);
                    } else {
                        const display = document.getElementById('activation-code-display');
                        if (display) display.textContent = 'GALAT PEMBUATAN KODE';
                    }
                })
                .catch(err => {
                    console.error(err);
                    const display = document.getElementById('activation-code-display');
                    if (display) display.textContent = 'MASALAH KONEKSI';
                });
        }

        function startActivationPolling(code) {
            if (activationPollInterval) clearInterval(activationPollInterval);
            activationPollInterval = setInterval(() => {
                fetch('/api/kiosk/check-activation?code=' + encodeURIComponent(code))
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success' && data.activated && data.device_token) {
                            clearInterval(activationPollInterval);
                            localStorage.setItem('kiosk_token', data.device_token);
                            window.location.reload();
                        }
                    })
                    .catch(err => console.error(err));
            }, 3000);
        }

        // Run check on DOM load
        document.addEventListener('DOMContentLoaded', checkActivationState);

        // --- Simulated Biometric Attendance Verification logic ---
        let scanningTimer = null;
        let scanProgress = 0;
        const scannerPad = document.getElementById('scanner-pad');
        const scannerLog = document.getElementById('scanner-log-feed');
        const pamongSelect = document.getElementById('pamong-select');
        const pinSection = document.getElementById('pin-confirmation-section');
        const pinInput = document.getElementById('pamong-pin-input');
        const biometricModalEl = document.getElementById('biometricModal');

        // Reset modal states when closed/opened
        if (biometricModalEl) {
            biometricModalEl.addEventListener('show.bs.modal', function () {
                if (pamongSelect) pamongSelect.value = '';
                if (pinInput) pinInput.value = '';
                if (pinSection) pinSection.style.display = 'none';
                if (scannerLog) scannerLog.innerHTML = '[SYS]: PEMINDAI MEMORI BIOMETRIK AKTIF...';
                scanProgress = 0;
            });
        }

        if (pinInput) {
            pinInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    submitAttendance();
                }
            });
        }

        function startScanning() {
            if (!pamongSelect || !pamongSelect.value) {
                if (scannerLog) scannerLog.innerHTML = "[SYS]: <span style='color:var(--neon-red);'>PILIH APARATUR DESA TERLEBIH DAHULU!</span>";
                playErrorSound();
                return;
            }

            initAudio();
            playHoverChime();
            scannerPad.classList.add('scanning');
            scanProgress = 0;
            if (scannerLog) scannerLog.innerHTML = "[SYS]: INITIATING SECURE BIOMETRIC PORT SCAN...<br>[SYS]: SCANNING DIGITAL SPLINE INDEX...";
            
            scanningTimer = setInterval(() => {
                scanProgress += 20;
                playHoverChime();
                if (scanProgress === 20) {
                    if (scannerLog) scannerLog.innerHTML += "<br>[SYS]: RESOLVING CARRIER IP & GATEWAY LINK...";
                } else if (scanProgress === 60) {
                    if (scannerLog) scannerLog.innerHTML += "<br>[SYS]: COMPARATIVE HASH ALIGNMENT [OK]...";
                } else if (scanProgress === 100) {
                    clearInterval(scanningTimer);
                    scannerPad.classList.remove('scanning');
                    playSuccessPing();
                    if (scannerLog) scannerLog.innerHTML = "[SYS]: <span style='color:var(--neon-green);'>SUCCESS! VERIFIKASI BIOMETRIK DISETUJUI.</span><br>[SYS]: HARAP MASUKKAN PIN PENGAMAN ANDA DI BAWAH.";
                    if (pinSection) pinSection.style.display = 'block';
                    if (pinInput) pinInput.focus();
                }
            }, 500);
        }

        function stopScanning() {
            if (scanningTimer) {
                clearInterval(scanningTimer);
                scannerPad.classList.remove('scanning');
                if (scanProgress < 100) {
                    playErrorSound();
                    if (scannerLog) scannerLog.innerHTML = "[SYS]: <span style='color:var(--neon-red);'>ERROR: PENGGUNA MELEPAS JARI. SCAN BATAL!</span>";
                }
            }
        }

        function submitAttendance() {
            const pamongId = pamongSelect ? pamongSelect.value : '';
            const pin = pinInput ? pinInput.value : '';
            const deviceToken = localStorage.getItem('kiosk_token');

            if (!pamongId) {
                alert('Harap pilih nama aparatur desa.');
                return;
            }
            if (!pin) {
                alert('Harap masukkan PIN atau NIK Keamanan Anda.');
                return;
            }

            if (scannerLog) scannerLog.innerHTML = "[SYS]: MENGIRIM DATA ABSENSI KE SERVER...";
            
            const formData = new FormData();
            formData.append('pamong_id', pamongId);
            formData.append('pin', pin);
            formData.append('device_token', deviceToken);

            fetch('/api/kiosk/absen', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    playSuccessPing();
                    if (scannerLog) scannerLog.innerHTML = `[SYS]: <span style='color:var(--neon-green);'>SUKSES! ${data.message}</span><br>[SYS]: Waktu: ${data.time}`;
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(biometricModalEl);
                        if (modal) modal.hide();
                    }, 2000);
                } else {
                    playErrorSound();
                    if (scannerLog) scannerLog.innerHTML = `[SYS]: <span style='color:var(--neon-red);'>GAGAL: ${data.message}</span>`;
                }
            })
            .catch(err => {
                console.error(err);
                playErrorSound();
                if (scannerLog) scannerLog.innerHTML = `[SYS]: <span style='color:var(--neon-red);'>GAGAL: Kesalahan koneksi jaringan.</span>`;
            });
        }

        // Guestbook Submit Logic
        function submitGuestbook(event) {
            event.preventDefault();
            playSuccessPing();
            const name = document.getElementById('guest-name').value;
            alert('Halo ' + name + ', Terima kasih! Kehadiran Anda di Buku Tamu telah diverifikasi.');
            
            const modalEl = document.getElementById('guestbookModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
            
            document.getElementById('guestbook-form').reset();
        }
    </script>
</body>
</html>
