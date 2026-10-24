@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.asset_validasi')

@section('content')
    @php
        $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
        $is_esensial = ($tenant_paket === 'esensial');

        // XOSID: Tentukan $form_action dengan fallback jika dari controller kosong
        if (empty($form_action)) {
            $ci = get_instance();
            $segment = $ci->uri->segment(2);
            $active_id = $ci->uri->segment(3);
            $active_parent = $ci->uri->segment(4);

            if (strpos($segment, 'ajax_kantor_rt_maps') !== false) {
                $form_action = site_url("wilayah/update_kantor_map/rt/{$active_id}/{$active_parent}");
            } elseif (strpos($segment, 'ajax_kantor_rw_maps') !== false) {
                $form_action = site_url("wilayah/update_kantor_map/rw/{$active_id}/{$active_parent}");
            } elseif (strpos($segment, 'ajax_kantor_dusun_maps') !== false) {
                $form_action = site_url("wilayah/update_kantor_map/dusun/{$active_id}");
            } else {
                $form_action = site_url("identitas_desa/update_maps/kantor");
            }
        }
    @endphp

    <style>
        @if ($is_esensial)
        /* ESENSIAL SPATIAL STUDIO THEME */
        html, body, .wrapper, .content-wrapper {
            background: #f8fafc !important;
            overflow: hidden !important;
        }

        .spatial-editor-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 70px;
            background: #ffffff;
            border-bottom: 1px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 10000;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.05);
        }

        .spatial-editor-body {
            position: fixed;
            top: 70px;
            left: 0;
            width: 100%;
            height: calc(100vh - 110px);
            padding: 10px 25px;
            overflow: hidden;
            z-index: 9998;
            background: #f1f5f9 url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'%3E%3Cpath d='M0 0h40v40H0V0zm1 1h38v38H1V1z' fill='%23000000' fill-opacity='0.02'/%3E%3C/svg%3E");
            display: flex;
            gap: 20px;
        }

        .spatial-editor-main {
            flex: 1;
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid #cbd5e1;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
        }

        .spatial-editor-sidebar {
            width: 340px;
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid #cbd5e1;
            display: flex;
            flex-direction: column;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
        }

        /* ESENSIAL BUTTONS */
        .btn-neural {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 25px;
            border-radius: 12px;
            font-weight: 800;
            font-family: 'Outfit', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: 1px solid transparent;
            position: relative;
            z-index: 1;
            text-decoration: none !important;
        }
        .btn-neural-cyan {
            background: #153E7E !important;
            color: #ffffff !important;
            border: 1.5px solid #153E7E !important;
            box-shadow: 0 4px 14px rgba(21, 62, 126, 0.25) !important;
        }
        .btn-neural-cyan:hover {
            background: #0d2750 !important;
            border-color: #0d2750 !important;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(21, 62, 126, 0.35) !important;
        }
        .btn-neural-secondary {
            background: #ffffff !important;
            color: #153E7E !important;
            border: 1.5px solid #153E7E !important;
            box-shadow: 0 2px 6px rgba(21, 62, 126, 0.08) !important;
        }
        .btn-neural-secondary:hover {
            background: #eff6ff !important;
            color: #153E7E !important;
            transform: translateY(-2px);
        }
        .btn-neural-danger {
            background: #fee2e2 !important;
            color: #dc2626 !important;
            border: 1px solid #fecaca !important;
        }
        .btn-neural-danger:hover {
            background: #fecaca !important;
            color: #b91c1c !important;
            transform: translateY(-2px);
        }

        .leaflet-bar, .leaflet-control-layers {
            border: none !important;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12) !important;
            border-radius: 12px !important;
            overflow: hidden;
            background: none !important;
        }
        
        .leaflet-bar a, .leaflet-pm-toolbar a, .leaflet-control-layers-toggle {
            background-color: #ffffff !important;
            color: #153E7E !important;
            border: 1px solid #cbd5e1 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 44px !important;
            height: 44px !important;
            margin-bottom: 8px !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08) !important;
        }

        .leaflet-bar a i, .leaflet-pm-toolbar a i, .leaflet-bar a span, .leaflet-pm-toolbar a span {
            color: #153E7E !important;
            font-weight: 900 !important;
            font-size: 18px !important;
        }

        .leaflet-bar a:hover, .leaflet-pm-toolbar a:hover {
            background-color: #eff6ff !important;
            border-color: #153E7E !important;
            color: #153E7E !important;
            transform: scale(1.08);
            z-index: 100;
        }

        .leaflet-control-layers {
            background: #ffffff !important;
            color: #0f172a !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 20px !important;
            padding: 15px !important;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15) !important;
        }

        .leaflet-control-layers-selector {
            appearance: none; -webkit-appearance: none; width: 20px; height: 20px;
            border: 2px solid #cbd5e1; border-radius: 6px;
            background: #ffffff; margin-right: 15px !important;
            cursor: pointer; position: relative; vertical-align: middle;
            transition: all 0.3s ease;
        }

        .leaflet-control-layers-selector:checked {
            border-color: #153E7E;
            box-shadow: 0 0 10px rgba(21, 62, 126, 0.3) !important;
            background: #153E7E !important;
        }

        .leaflet-control-layers-selector:checked::after {
            content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 8px; height: 8px; background: #ffffff; border-radius: 50%;
        }

        .leaflet-control-layers-separator {
            border-top: 1px solid #e2e8f0 !important;
            margin: 12px -15px !important;
            height: 0;
        }

        .leaflet-control-layers-list span {
            color: #0f172a !important; font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 14px;
            letter-spacing: 0.5px;
        }

        .form-control-neural {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
            border-radius: 12px !important;
            height: 45px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 700;
        }
        .form-control-neural:focus {
            border-color: #153E7E !important;
            background: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(21, 62, 126, 0.12) !important;
            outline: none !important;
        }
        .spatial-editor-sidebar label {
            color: #334155;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            display: block;
        }

        #exportGPX {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 10px !important;
            padding: 10px 25px !important;
            border-radius: 12px !important;
            background: #ffffff !important;
            color: #153E7E !important;
            border: 1.5px solid #153E7E !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 1.5px !important;
            height: 45px !important;
            width: 100% !important;
            position: relative !important;
            overflow: visible !important;
            text-decoration: none !important;
            box-shadow: 0 2px 6px rgba(21, 62, 126, 0.08) !important;
            text-align: center !important;
            float: none !important;
        }
        #exportGPX i, #exportGPX > :first-child {
            position: static !important;
            width: auto !important;
            height: auto !important;
            line-height: 1 !important;
            border: none !important;
            font-size: 16px !important;
            margin: 0 !important;
            padding: 0 !important;
            background: none !important;
            color: #153E7E !important;
            display: inline-block !important;
        }
        #exportGPX:hover {
            background: #eff6ff !important;
            color: #153E7E !important;
            transform: translateY(-2px);
        }
        @else
        /* ENTERPRISE DARK SPATIAL STUDIO */
        html, body, .wrapper, .content-wrapper {
            background: #060910 !important;
            overflow: hidden !important;
        }

        /* XOSID SPATIAL EDITOR HEADER */
        .spatial-editor-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 70px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(6, 182, 212, 0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 10000;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .spatial-editor-body {
            position: fixed;
            top: 70px;
            left: 0;
            width: 100%;
            height: calc(100vh - 110px);
            padding: 10px 25px;
            overflow: hidden;
            z-index: 9998;
            background: #060910 url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'%3E%3Cpath d='M0 0h40v40H0V0zm1 1h38v38H1V1z' fill='%23ffffff' fill-opacity='0.015'/%3E%3C/svg%3E");
            display: flex;
            gap: 20px;
        }

        .spatial-editor-main {
            flex: 1;
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid rgba(6, 182, 212, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .spatial-editor-sidebar {
            width: 340px;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(6, 182, 212, 0.15);
            display: flex;
            flex-direction: column;
            padding: 25px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        /* NEURAL BUTTONS */
        .btn-neural {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 25px;
            border-radius: 12px;
            font-weight: 800;
            font-family: 'Outfit';
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: 1px solid transparent;
            position: relative;
            z-index: 1;
            text-decoration: none !important;
        }
        .btn-neural-cyan {
            background: rgba(6, 182, 212, 0.1);
            color: #06b6d4;
            border-color: rgba(6, 182, 212, 0.3);
        }
        .btn-neural-cyan:hover {
            background: #06b6d4;
            color: #060910;
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.4);
        }
        .btn-neural-secondary {
            background: rgba(15, 23, 42, 0.6);
            color: #94a3b8;
            border-color: rgba(255,255,255,0.1);
        }
        .btn-neural-secondary:hover {
            background: white;
            color: #060910;
            box-shadow: 0 0 20px rgba(255,255,255,0.2);
        }
        .btn-neural-danger {
            background: rgba(244, 63, 94, 0.1);
            color: #f43f5e;
            border-color: rgba(244, 63, 94, 0.3);
        }
        .btn-neural-danger:hover {
            background: #f43f5e;
            color: white;
            box-shadow: 0 0 25px rgba(244, 63, 94, 0.4);
        }

        /* [V123] UNIVERSAL SPATIAL STUDIO STANDARD */
        .leaflet-bar, .leaflet-control-layers {
            border: none !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5) !important;
            border-radius: 12px !important;
            overflow: hidden;
            background: none !important;
        }
        
        .leaflet-bar a, .leaflet-pm-toolbar a, .leaflet-control-layers-toggle {
            background-color: rgba(255, 255, 255, 0.9) !important;
            color: #334155 !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 44px !important;
            height: 44px !important;
            margin-bottom: 8px !important;
            border-radius: 12px !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important;
        }

        .leaflet-bar a i, .leaflet-pm-toolbar a i, .leaflet-bar a span, .leaflet-pm-toolbar a span {
            color: #334155 !important;
            font-weight: 900 !important;
            font-size: 18px !important;
        }

        .leaflet-bar a:hover, .leaflet-pm-toolbar a:hover {
            background-color: #ffffff !important;
            color: #06b6d4 !important;
            transform: scale(1.08);
            z-index: 100;
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.4) !important;
        }

        /* UNIVERSAL LAYER CONTROL (V123) */
        .leaflet-control-layers {
            background: #060910 !important;
            color: #f1f5f9 !important;
            border: 1px solid #06b6d4 !important;
            border-radius: 20px !important;
            padding: 15px !important;
            backdrop-filter: blur(25px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.8) !important;
        }

        .leaflet-control-layers-toggle {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23334155' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolygon points='12 2 2 7 12 12 22 7 12 2'/%3E%3Cpolyline points='2 17 12 22 22 17'/%3E%3Cpolyline points='2 12 12 17 22 12'/%3E%3C/svg%3E") !important;
            background-size: 24px 24px !important;
            background-position: center !important;
        }

        .leaflet-control-layers-selector {
            appearance: none; -webkit-appearance: none; width: 20px; height: 20px;
            border: 2px solid rgba(6, 182, 212, 0.5); border-radius: 6px;
            background: rgba(255, 255, 255, 0.05); margin-right: 15px !important;
            cursor: pointer; position: relative; vertical-align: middle;
            transition: all 0.3s ease;
        }

        .leaflet-control-layers-selector:checked {
            border-color: #06b6d4;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.8) !important;
            background: rgba(6, 182, 212, 0.3) !important;
        }

        .leaflet-control-layers-selector:checked::after {
            content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 10px; height: 10px; background: #06b6d4; border-radius: 50%;
            box-shadow: 0 0 10px #06b6d4;
        }

        .leaflet-control-layers-separator {
            border-top: 1px solid rgba(255,255,255,0.2) !important;
            margin: 12px -15px !important;
            height: 0;
        }

        .leaflet-control-layers-list span {
            color: white !important; font-family: 'Outfit'; font-weight: 600; font-size: 14px;
            letter-spacing: 0.5px;
        }
        
        .leaflet-control-layers-group {
            margin-bottom: 5px;
        }

        /* CUSTOM SIDEBAR FORM FIELD STYLES */
        .form-control-neural {
            background: rgba(0, 0, 0, 0.4) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #f1f5f9 !important;
            border-radius: 12px !important;
            height: 45px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        .form-control-neural:focus {
            border-color: #06b6d4 !important;
            background: rgba(6, 182, 212, 0.05) !important;
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.15) !important;
            outline: none !important;
        }
        .spatial-editor-sidebar label {
            color: #64748b;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            display: block;
        }

        /* GPX BUTTON NEURAL ACCENT OVERRIDE */
        #exportGPX {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 10px !important;
            padding: 10px 25px !important;
            border-radius: 12px !important;
            background: rgba(6, 182, 212, 0.1) !important;
            color: #06b6d4 !important;
            border: 1px solid rgba(6, 182, 212, 0.3) !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 1.5px !important;
            height: 45px !important;
            width: 100% !important;
            position: relative !important;
            overflow: visible !important;
            text-decoration: none !important;
            box-shadow: none !important;
            text-align: center !important;
            float: none !important;
        }
        #exportGPX i, #exportGPX > :first-child {
            position: static !important;
            width: auto !important;
            height: auto !important;
            line-height: 1 !important;
            border: none !important;
            font-size: 16px !important;
            margin: 0 !important;
            padding: 0 !important;
            background: none !important;
            color: #06b6d4 !important;
            display: inline-block !important;
        }
        #exportGPX:hover {
            background: #06b6d4 !important;
            color: #060910 !important;
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.4) !important;
        }
        #exportGPX:hover i {
            color: #060910 !important;
        }
        @endif

        /* HIERARCHICAL PIN STYLES */
        .xosid-identity-marker { background: transparent; border: none; }
        .marker-container { position: relative; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; }
        .marker-pulse {
            position: absolute; width: 100%; height: 100%; border-radius: 50%;
            background: rgba(21, 62, 126, 0.4); animation: xosid-pulse 2s infinite;
        }
        .marker-core {
            position: relative; width: 16px; height: 16px; background: #153E7E;
            border-radius: 50%; border: 3px solid #ffffff; z-index: 2; box-shadow: 0 0 15px rgba(21, 62, 126, 0.5);
        }
        .xosid-marker-rw .marker-pulse { background: rgba(99, 102, 241, 0.4); }
        .xosid-marker-rw .marker-core { background: #6366f1; box-shadow: 0 0 15px #6366f1; }
        .xosid-marker-rt .marker-pulse { background: rgba(245, 158, 11, 0.4); }
        .xosid-marker-rt .marker-core { background: #f59e0b; box-shadow: 0 0 15px #f59e0b; }
        .xosid-marker-reference .marker-pulse { background: rgba(79, 70, 229, 0.4); }
        .xosid-marker-reference .marker-core { background: #4f46e5; box-shadow: 0 0 15px #4f46e5; }
        .marker-label {
            position: absolute; top: 100%; left: 50%; transform: translateX(-50%);
            background: rgba(15, 23, 42, 0.9); color: white; padding: 4px 10px;
            border-radius: 6px; font-size: 10px; font-weight: 800; white-space: nowrap;
            margin-top: 2px; border: 1px solid rgba(255,255,255,0.1);
            font-family: 'Outfit'; letter-spacing: 0.5px; border-left: 3px solid #153E7E;
        }
        .xosid-marker-rw .marker-label { border-left: 3px solid #6366f1; }
        .xosid-marker-rt .marker-label { border-left: 3px solid #f59e0b; }
        .xosid-marker-reference .marker-label { border-left: 3px solid #4f46e5; }

        @keyframes xosid-pulse { 0% { transform: scale(0.5); opacity: 1; } 100% { transform: scale(2.5); opacity: 0; } }

        /* XOSID SPATIAL EDITOR - FOCUS MODE (V139) */
        .main-header, .main-sidebar, .main-footer, .breadcrumb, .control-sidebar, #control-sidebar, 
        #katadesa-toggle, .katadesa-bubble, .katadesa-chat-panel, .floating-help, .btn-float,
        .col-md-3, .col-sm-4, .box-info:not(.neural-card) {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
            height: 0 !important;
            padding: 0 !important;
        }
        /* Neural Header Adjustment */
        .content-header {
            background: transparent !important;
            padding: 10px 25px 0 !important;
            border: none !important;
        }
        .content-wrapper, .col-md-9, .col-sm-8 { 
            margin-left: 0 !important; 
            padding: 0 !important; 
            background: {{ $is_esensial ? '#f8fafc' : '#060910' }} !important; 
            width: 100% !important;
        }

        /* SCROLLBAR FOR SIDEBAR */
        .spatial-editor-sidebar ::-webkit-scrollbar {
            width: 6px;
        }
        .spatial-editor-sidebar ::-webkit-scrollbar-track {
            background: transparent;
        }
        .spatial-editor-sidebar ::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.1);
            border-radius: 3px;
        }
        .spatial-editor-sidebar ::-webkit-scrollbar-thumb:hover {
            background: rgba(21, 62, 126, 0.3);
        }

        /* ADJUST AI FAB POSITION FOR SPATIAL EDITOR */
        .katadesa-ai-fab {
            bottom: 60px !important;
            z-index: 100001 !important;
        }
        .katadesa-ai-panel {
            bottom: 140px !important;
            z-index: 100000 !important;
        }
    </style>

    <!-- XOSID SPATIAL EDITOR HEADER -->
    <div class="spatial-editor-header">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="background: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; color: {{ $is_esensial ? '#ffffff' : '#060910' }}; font-family: 'Outfit'; font-weight: 900; padding: 6px 14px; border-radius: 10px; font-size: 14px; letter-spacing: 1px;">XOSID</div>
            <div style="height: 25px; width: 1px; background: {{ $is_esensial ? '#cbd5e1' : 'rgba(255,255,255,0.1)' }};"></div>
            <div>
                <div style="color: {{ $is_esensial ? '#153E7E' : '#64748b' }}; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px;">SPATIAL OFFICE STUDIO</div>
                <div style="color: {{ $is_esensial ? '#0f172a' : 'white' }}; font-weight: 800; font-size: 16px; font-family: 'Outfit';">Kantor {{ $nama_wilayah }}</div>
            </div>
        </div>
        
        <div style="display: flex; align-items: center; gap: 30px;">
            <div style="display: flex; align-items: center; gap: 10px; background: {{ $is_esensial ? '#ecfdf5' : 'transparent' }}; padding: {{ $is_esensial ? '6px 14px' : '0' }}; border-radius: 20px; border: {{ $is_esensial ? '1px solid #a7f3d0' : 'none' }};">
                <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; box-shadow: 0 0 10px #10b981;"></div>
                <span style="color: {{ $is_esensial ? '#047857' : '#94a3b8' }}; font-size: 11px; font-weight: 800; letter-spacing: 1px;">SPATIAL ENGINE ACTIVE</span>
            </div>
            <div class="spatial-editor-exit">
                @php 
                    $exit_link = (strpos(request()->url(), 'identitas_desa') !== false) ? site_url('identitas_desa') : site_url("wilayah/ajax_wilayah_" . ($wil_ini['level_nama'] ?? 'dusun') . "_maps/" . ($wil_ini['parent_id'] ?? '0') . "/" . ($wil_ini['id'] ?? '0'));
                    // Fallback
                    if (empty($wil_ini['level_nama']) && strpos(request()->url(), 'identitas_desa') === false) {
                        $exit_link = site_url('identitas_desa');
                    }
                @endphp
                <a href="{{ $exit_link }}" class="btn-neural btn-neural-secondary">
                    <i class="fa fa-power-off"></i> EXIT EDITOR
                </a>
            </div>
        </div>
    </div>

    @include('admin.layouts.components.notifikasi')

    <div class="spatial-editor-body">
        <div class="spatial-editor-main">
            <div id="tampil-map" style="width: 100%; height: 100%;">
                <input type="hidden" name="zoom" id="zoom" value="{{ $wil_ini['zoom'] }}" />
                <input type="hidden" name="map_tipe" id="map_tipe" value="{{ $wil_ini['map_tipe'] }}" />
                <input type="hidden" name="id" id="id" value="{{ $wil_ini['id'] }}" />
            </div>
        </div>

        <div class="spatial-editor-sidebar">
            <form action="{{ $form_action }}" method="POST" enctype="multipart/form-data" id="validasi" novalidate style="height: 100%; display: flex; flex-direction: column; margin: 0;">
                <input type="hidden" name="<?= get_instance()->security->get_csrf_token_name(); ?>" value="<?= get_instance()->security->get_csrf_hash(); ?>">
                <input type="hidden" name="id" id="id" value="{{ $wil_ini['id'] }}" />
                <input type="hidden" name="zoom" id="zoom" value="{{ $wil_ini['zoom'] }}" />
                <input type="hidden" name="map_tipe" id="map_tipe" value="{{ $wil_ini['map_tipe'] }}" />

                <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                    <div>
                        <h4 style="color: {{ $is_esensial ? '#0f172a' : 'white' }}; font-weight: 900; font-family: 'Outfit'; font-size: 12px; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                            <i class="fa fa-compass" style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }};"></i> PARAMETER GEOSPASIAL
                        </h4>

                        @if(empty($wil_ini['lat']) || empty($wil_ini['lng']))
                        <div style="background: {{ $is_esensial ? '#fffbeb' : 'rgba(245, 158, 11, 0.1)' }}; border: 1px solid {{ $is_esensial ? '#fde68a' : 'rgba(245, 158, 11, 0.2)' }}; border-radius: 16px; padding: 15px; margin-bottom: 25px;">
                            <div style="color: {{ $is_esensial ? '#b45309' : '#f59e0b' }}; font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 5px;">
                                <i class="fa fa-exclamation-triangle"></i> KOORDINAT KOSONG
                            </div>
                            <div style="color: {{ $is_esensial ? '#92400e' : '#94a3b8' }}; font-size: 11px; font-weight: 600; line-height: 1.4;">Tentukan koordinat dengan menyeret penanda (Pin) di peta atau masukkan secara manual di bawah ini.</div>
                        </div>
                        @endif

                        <div class="form-group neural-vertical-group">
                            <label for="lat">Latitude (Garis Lintang)</label>
                            <input type="text" class="required form-control-neural" name="lat" id="lat" value="{{ $wil_ini['lat'] }}" placeholder="Contoh: -5.123456" style="width: 100%;" />
                        </div>

                        <div class="form-group neural-vertical-group" style="margin-top: 20px;">
                            <label for="lng">Longitude (Garis Bujur)</label>
                            <input type="text" class="required form-control-neural" name="lng" id="lng" value="{{ $wil_ini['lng'] }}" placeholder="Contoh: 105.123456" style="width: 100%;" />
                        </div>

                        <!-- Integrasi Fitur Tambahan (Ambil dari Pantau & Ekspor GPX) -->
                        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid {{ $is_esensial ? '#e2e8f0' : 'rgba(255,255,255,0.05)' }}; display: flex; flex-direction: column; gap: 10px;">
                            <label style="color: {{ $is_esensial ? '#475569' : '#64748b' }};">FITUR LAINNYA</label>
                            
                            @if (can('u') && class_exists(\Modules\BatasWilayah\Services\BatasWilayahService::class))
                                <a
                                    href="#"
                                    data-href="{{ ci_route('identitas_desa.generate_boundary', 'kantor') }}"
                                    class="btn-neural btn-neural-secondary"
                                    style="justify-content: center; height: 45px; font-size: 11px;"
                                    title="Ambil koordinat kantor dari server pantau"
                                    data-toggle="modal"
                                    data-target="#confirm-status"
                                    data-body="Ambil koordinat kantor {{ $nama_wilayah }} (lat/lng) dari server pantau secara otomatis?"
                                ><i class="fa fa-map-marker"></i> AMBIL DARI PANTAU</a>
                            @endif

                            @include('admin.layouts.components.buttons.ekspor_gpx')
                        </div>
                    </div>

                    <div style="margin-top: auto; padding-top: 25px; display: flex; flex-direction: column; gap: 15px;">
                        @if (can('u'))
                            <button type="submit" class="btn-neural btn-neural-cyan" style="width: 100%; height: 50px; justify-content: center;">
                                <i class="fa fa-save"></i> SIMPAN KOORDINAT
                            </button>
                        @endif
                        <button type="button" class="btn-neural btn-neural-danger" id="reset-peta" style="width: 100%; height: 45px; justify-content: center;">
                            <i class="fa fa-refresh"></i> RESET POSISI
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- SPATIAL FOOTER BRANDING -->
    <div style="position: fixed; bottom: 0; left: 0; width: 100%; height: 40px; background: {{ $is_esensial ? '#ffffff' : 'rgba(15, 23, 42, 0.95)' }}; border-top: 1px solid {{ $is_esensial ? '#cbd5e1' : 'rgba(6, 182, 212, 0.2)' }}; display: flex; align-items: center; justify-content: space-between; padding: 0 30px; z-index: 10000; box-shadow: 0 -4px 15px rgba(15, 23, 42, 0.04);">
        <div style="font-size: 10px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: {{ $is_esensial ? '#475569' : '#94a3b8' }};">
            <i class="fa fa-shield" style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; margin-right: 8px;"></i> XOSID SPATIAL STUDIO &bull; <span style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; font-weight: 900;">SPATIAL ENGINE ACTIVE</span>
        </div>
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="font-size: 11px; font-weight: 700; color: {{ $is_esensial ? '#475569' : '#94a3b8' }};">Kantor: {{ $nama_wilayah }}</div>
        </div>
    </div>
@endsection

@include('admin.layouts.components.asset_peta')
@include('admin.layouts.components.konfirmasi', ['periksa_data' => true])

@push('scripts')
    <script>
        // XOSID TOTAL ALIGNMENT BOOTSTRAPPER (V121)
        window.peta_kantor = null;

        function initXosidSpatialStudio() {
            if (window.peta_kantor) return;
            const mapContainer = document.getElementById('tampil-map');
            if (!mapContainer) return;

            try {
                var lat = {{ $wil_ini['lat'] ?? ($wil_atas['lat'] ?? config('app.map.point.lat')) }};
                var lng = {{ $wil_ini['lng'] ?? ($wil_atas['lng'] ?? config('app.map.point.lng')) }};
                var zoom = {{ $wil_ini['zoom'] ?? ($wil_atas['zoom'] ?? config('app.map.zoom')) }};
                var posisi = [lat, lng];
                var isReference = {{ empty($wil_ini['lat']) ? 'true' : 'false' }};

                window.peta_kantor = L.map('tampil-map', {
                    ...pengaturan_peta,
                    attributionControl: false
                }).setView(posisi, zoom);

                const peta = window.peta_kantor;

                // [V157] CLEAN UNIFIED TOOLBAR (TOP-LEFT)
                const neuralHUDToolbar = L.Control.extend({
                    options: { position: 'topleft' },
                    onAdd: function() {
                        const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control neural-custom-control neural-unified-hud');
                        div.style.border = 'none';
                        div.style.background = 'none';
                        div.style.display = 'flex';
                        div.style.flexDirection = 'column';
                        div.style.gap = '10px';

                        div.innerHTML = `
                            <div class="leaflet-bar" style="border-radius: 10px; overflow: hidden; border: 2px solid rgba(0,0,0,0.1); box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <a href="#" onclick="window.peta_kantor.zoomIn(); return false;" title="Zoom In" style="background: white !important; margin: 0; border: none; border-bottom: 1px solid rgba(0,0,0,0.1); width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;"><i class="fa fa-plus" style="color: #334155; font-size: 20px;"></i></a>
                                <a href="#" onclick="window.peta_kantor.zoomOut(); return false;" title="Zoom Out" style="background: white !important; margin: 0; border: none; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;"><i class="fa fa-minus" style="color: #334155; font-size: 20px;"></i></a>
                            </div>
                            
                            <div class="leaflet-bar" style="border: 2px solid rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <a href="#" onclick="window.peta_kantor.locate({setView: true, maxZoom: 16}); return false;" title="Locate Me" style="background: white !important; margin: 0; border: none; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;"><i class="fa fa-map-marker" style="color: #334155; font-size: 22px;"></i></a>
                            </div>

                            <div class="leaflet-bar" style="border: 2px solid rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <a href="#" onclick="window.print(); return false;" title="Print Map" style="background: white !important; margin: 0; border: none; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;"><i class="fa fa-print" style="color: #334155; font-size: 20px;"></i></a>
                            </div>
                        `;
                        return div;
                    }
                });
                peta.addControl(new neuralHUDToolbar());

                // Remove standard zoom
                peta.zoomControl.remove();

                var baseLayers = {
                    "Satellite": L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {}).addTo(peta),
                    "Street": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {})
                };

                // [V160] LIVE INJECTION ENGINE - PERSISTENT LAYER GROUPS
                window.xosid_desa_group = L.layerGroup().addTo(peta);
                window.xosid_dusun_group = L.layerGroup();
                window.xosid_rw_group = L.layerGroup();
                window.xosid_rt_group = L.layerGroup();

                // 1. Menampilkan overlayLayers Peta Semua Wilayah
                var marker_desa = [];
                var marker_dusun = [];
                var marker_rw = [];
                var marker_rt = [];

                // OVERLAY WILAYAH DESA
                @if (!empty($desa['path']))
                    set_marker_desa(marker_desa, {!! json_encode($desa, JSON_THROW_ON_ERROR) !!}, "{{ ucwords(setting('sebutan_desa')) . ' ' . $desa['nama_desa'] }}", "{{ favico_desa() }}");
                    marker_desa.filter(l => l).forEach(l => {
                        L.geoJSON(l, {
                            style: function(f) { return f.properties.style; },
                            pointToLayer: function(f, ll) { return L.marker(ll, { icon: f.properties.style }); }
                        }).addTo(window.xosid_desa_group);
                    });
                @endif

                // OVERLAY WILAYAH DUSUN
                @if (!empty($dusun_gis))
                    set_marker_multi(marker_dusun, '{!! addslashes(json_encode($dusun_gis, JSON_THROW_ON_ERROR)) !!}', '{{ ucwords(setting('sebutan_dusun')) }}', 'dusun', "{{ favico_desa() }}");
                    marker_dusun.filter(l => l).forEach(l => {
                        L.geoJSON(l, {
                            style: function(f) { return f.properties.style; }
                        }).addTo(window.xosid_dusun_group);
                    });
                @endif

                // OVERLAY WILAYAH RW
                @if (!empty($rw_gis))
                    set_marker(marker_rw, '{!! addslashes(json_encode($rw_gis, JSON_THROW_ON_ERROR)) !!}', 'RW', 'rw', "{{ favico_desa() }}");
                    marker_rw.filter(l => l).forEach(l => {
                        L.geoJSON(l, {
                            style: function(f) { return f.properties.style; }
                        }).addTo(window.xosid_rw_group);
                    });
                @endif

                // OVERLAY WILAYAH RT
                @if (!empty($rt_gis))
                    set_marker(marker_rt, '{!! addslashes(json_encode($rt_gis, JSON_THROW_ON_ERROR)) !!}', 'RT', 'rt', "{{ favico_desa() }}");
                    marker_rt.filter(l => l).forEach(l => {
                        L.geoJSON(l, {
                            style: function(f) { return f.properties.style; }
                        }).addTo(window.xosid_rt_group);
                    });
                @endif

                var overlayLayers = {
                    "Peta Wilayah Desa": window.xosid_desa_group,
                    "Peta Wilayah Dusun": window.xosid_dusun_group,
                    "Peta Wilayah RW": window.xosid_rw_group,
                    "Peta Wilayah RT": window.xosid_rt_group
                };

                // [V140] REFINED ANTI-RESURRECTION (PRESERVE NEURAL TOOLS)
                function purgeLegacyControls() {
                    $(".leaflet-top.leaflet-left .leaflet-control:not(.leaflet-pm-toolbar):not(.neural-custom-control):not(.leaflet-control-layers)").remove();
                    $(".leaflet-top.leaflet-right .leaflet-control:not(.leaflet-pm-toolbar):not(.neural-custom-control):not(.leaflet-control-layers)").remove();
                    
                    $(".leaflet-control-layers:not(:last), .leaflet-control-scale:not(:last), .leaflet-control-attribution:not(:last)").remove();
                    
                    $(".leaflet-control-layers-toggle").each(function() {
                        if (!$(this).parent().hasClass('leaflet-control-layers') && $(this).closest(".leaflet-bottom.leaflet-right").length === 0) {
                            $(this).closest(".leaflet-control").remove();
                        }
                    });
                }

                // Run purge loop
                for (let i = 0; i < 8; i++) { setTimeout(purgeLegacyControls, i * 600); }

                var baseCtrl = L.control.layers(baseLayers, overlayLayers, { position: 'topleft' }).addTo(peta);
                
                // Style the layer toggle to match the square theme
                setTimeout(() => {
                    $(".leaflet-control-layers").css({
                        'border': '2px solid rgba(0,0,0,0.1)',
                        'border-radius': '10px',
                        'overflow': 'hidden',
                        'box-shadow': '0 4px 15px rgba(0,0,0,0.1)',
                        'background': 'white',
                        'margin-bottom': '0'
                    });
                    $(".leaflet-control-layers-toggle").css({
                        'background-color': 'white',
                        'width': '44px',
                        'height': '44px',
                        'border': 'none',
                        'border-radius': '0'
                    });
                }, 100);
                var scaleCtrl = L.control.scale({ position: 'bottomleft' }).addTo(peta);
                var attrCtrl = L.control.attribution({ position: 'bottomright', prefix: 'XOSID STUDIO' }).addTo(peta);

                peta._xosid_controls = [baseCtrl, scaleCtrl, attrCtrl];

                // [V132] ROBUST BOUNDARY SYNC
                peta.on('overlayadd', function(e) {
                    if (e.name === "Peta Wilayah Desa" && window.xosid_desa_group.getLayers().length === 0) {
                        console.log("XOSID [V132]: Syncing Village Boundary...");
                        var fallbackPath = @json($desa['path'] ?? ($wil_atas['path'] ?? null));
                        if (fallbackPath) {
                            try {
                                var rawPath = (typeof fallbackPath === 'string') ? JSON.parse(fallbackPath) : fallbackPath;
                                var layerBoundary = L.polygon(rawPath, { color: '#f59e0b', weight: 5, dashArray: '15, 15', fillOpacity: 0.15, fillColor: '#f59e0b', interactive: false });
                                window.xosid_desa_group.addLayer(layerBoundary);
                                console.log("XOSID [V132]: Boundary Synced from Memory");
                            } catch(err) { console.error("XOSID [V132]: Sync Error", err); }
                        } else {
                            $.getJSON("{{ site_url('identitas_desa/ajax_peta') }}", function(res) {
                                if (res && res.path) {
                                    var rPath = (typeof res.path === 'string') ? JSON.parse(res.path) : res.path;
                                    var lb = L.polygon(rPath, { color: '#f59e0b', weight: 5, dashArray: '15, 15', fillOpacity: 0.15, fillColor: '#f59e0b', interactive: false });
                                    window.xosid_desa_group.addLayer(lb);
                                }
                            });
                        }
                    }
                });

                // IDENTITY SYNC
                var currentUrl = window.location.href;
                var markerClass = 'xosid-identity-marker';
                if (isReference) { markerClass += ' xosid-marker-reference'; }
                else if (currentUrl.indexOf('rt_maps') > -1) { markerClass += ' xosid-marker-rt'; }
                else if (currentUrl.indexOf('rw_maps') > -1) { markerClass += ' xosid-marker-rw'; }

                var subName = "{!! addslashes(($wil_ini['rt'] && $wil_ini['rt'] != '0' ? 'RT '.$wil_ini['rt'] : ($wil_ini['rw'] && $wil_ini['rw'] != '0' ? 'RW '.$wil_ini['rw'] : ($wil_ini['dusun'] ?? $nama_wilayah)))) !!}";
                if (isReference) subName += ' (Ref)';

                var neuralIcon = L.divIcon({
                    className: markerClass,
                    html: '<div class="marker-container"><div class="marker-pulse"></div><div class="marker-core"></div><div class="marker-label">'+subName+'</div></div>',
                    iconSize: [40, 40], iconAnchor: [20, 20]
                });

                var mainMarker = L.marker(posisi, { icon: neuralIcon, draggable: true, opacity: isReference ? 0.6 : 1.0 }).addTo(peta);

                function updateInputs(ll) {
                    document.getElementById('lat').value = ll.lat.toFixed(8);
                    document.getElementById('lng').value = ll.lng.toFixed(8);
                }

                mainMarker.on('dragend', function() { updateInputs(mainMarker.getLatLng()); });
                peta.on('click', function(e) { mainMarker.setLatLng(e.latlng); updateInputs(e.latlng); });

                @if (can('u'))
                    // Export/Import Peta dari file GPX
                    eximGpxPoint(peta);
                @endif

                // Reset peta type point
                resetPoint(peta, posisi, zoom);

                // Force Lucide icons generation
                if (window.lucide) {
                    lucide.createIcons();
                }

                setTimeout(() => {
                    const els = ['.content-wrapper', '.main-header', '.wrapper'];
                    els.forEach(s => { const e = document.querySelector(s); if (e) { e.style.marginLeft = '0px'; e.style.width = '100%'; } });
                    peta.invalidateSize();
                }, 500);

            } catch(e) { console.error("XOSID Alignment Error:", e); }
        }

        // [V136] NUCLEAR BOOTSTRAPPER (HIGH-PRIORITY PURGE)
        const bootInterval = setInterval(() => {
            const mapContainer = document.getElementById('tampil-map');
            if (mapContainer) {
                // Perform Hard Removal of Legacy Elements
                const targetSelectors = ['.main-header', '.main-sidebar', '.navbar-static-top', 'header', '.main-footer', 'footer'];
                targetSelectors.forEach(sel => {
                    document.querySelectorAll(sel).forEach(el => {
                        el.style.display = 'none';
                        el.remove();
                    });
                });

                // Force Layout Expansion
                const layouts = ['.wrapper', '.content-wrapper', '.content'];
                layouts.forEach(sel => {
                    document.querySelectorAll(sel).forEach(el => {
                        el.style.marginLeft = '0px !important';
                        el.style.padding = '0px !important';
                        el.style.width = '100% !important';
                        el.style.minHeight = '100vh !important';
                    });
                });

                initXosidSpatialStudio();
                clearInterval(bootInterval);
            }
        }, 50);
    </script>
    <script src="{{ asset('js/leaflet.filelayer.js') }}"></script>
    <script src="{{ asset('js/togeojson.js') }}"></script>
@endpush
