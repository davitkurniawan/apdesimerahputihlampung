@php
    if (defined('FCPATH') && file_exists(FCPATH . 'xosid_database_helpers.php')) {
        include_once FCPATH . 'xosid_database_helpers.php';
    }
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_preview_active = !empty($_COOKIE['xosid_preview_enterprise']) || !empty($_SESSION['xosid_preview_enterprise']) || (isset($_GET['preview_enterprise']) && $_GET['preview_enterprise'] == '1') || (isset($_GET['switch_paket']) && $_GET['switch_paket'] === 'enterprise') || file_exists(sys_get_temp_dir() . '/xosid_preview_tamanasri.txt') || (defined('FCPATH') && file_exists(FCPATH . 'storage/xosid_preview_active.txt'));
    $is_esensial = ($tenant_paket === 'esensial' && !$is_preview_active);
@endphp
<header class="main-header" style="background: {{ $is_esensial ? '#153E7E' : '#0f172a' }} !important; border-bottom: 1px solid {{ $is_esensial ? 'rgba(255, 255, 255, 0.15)' : 'rgba(6, 182, 212, 0.15)' }}; box-shadow: {{ $is_esensial ? '0 2px 10px rgba(0,0,0,0.04)' : '0 4px 20px rgba(0,0,0,0.2)' }};">
    <!-- XOSID LOGO AREA -->
    <a href="{{ site_url() }}" target="_blank" class="logo" style="background: {{ $is_esensial ? '#153E7E' : 'var(--header-bg, #0f172a)' }} !important; border-right: 1px solid {{ $is_esensial ? 'rgba(255, 255, 255, 0.15)' : 'var(--header-border, rgba(255,255,255,0.05))' }} !important; height: 60px !important; display: flex !important; align-items: center !important; justify-content: flex-start !important; padding: 0 25px !important; text-decoration: none !important;">
        <div style="display: flex; align-items: center; gap: 12px; font-family: 'Outfit', sans-serif;">
            <div style="width: 32px; height: 32px; background: {{ $is_esensial ? '#ffffff' : 'linear-gradient(135deg, #06b6d4 0%, #0891b2 100%)' }}; border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px {{ $is_esensial ? 'rgba(0, 0, 0, 0.1)' : 'rgba(6, 182, 212, 0.4)' }}; border: 1px solid {{ $is_esensial ? 'rgba(255,255,255,0.8)' : 'rgba(255,255,255,0.2)' }};">
                <b style="color: {{ $is_esensial ? '#153E7E' : 'white' }}; font-size: 18px; font-weight: 900;">X</b>
            </div>
            <div class="logo-lg-text" style="display: flex; flex-direction: column; line-height: 1.1;">
                <div style="display: flex; align-items: center; gap: 4px;">
                    <span style="color: #ffffff; font-size: 10px; font-weight: 800; letter-spacing: 2px;">XO</span>
                    <span style="color: #38bdf8; font-size: 10px; font-weight: 800; letter-spacing: 2px;">SID</span>
                </div>
                <div style="font-size: 16px; font-weight: 900; letter-spacing: 1px; text-transform: uppercase;">
                    @if ($is_esensial)
                        <span style="color: #38bdf8; font-weight: 900; letter-spacing: 1.5px;">ESENSIAL</span>
                    @else
                        <span style="color: white;">SUPRA</span><span style="color: #06b6d4;">DESA</span>
                    @endif
                </div>
            </div>
        </div>
    </a>

    <!-- NAVBAR AREA -->
    <nav class="navbar navbar-static-top" role="navigation" style="background: {{ $is_esensial ? '#153E7E' : 'var(--header-bg, #0f172a)' }} !important; height: 60px !important; display: flex !important; align-items: center !important; justify-content: space-between !important; padding: 0 25px !important; border: none !important;">
        <!-- Left Side: Toggle & Title -->
        <div style="display: flex; align-items: center; gap: 15px;">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" style="color: {{ $is_esensial ? '#ffffff' : '#64748b' }}; font-size: 18px; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; width: 35px; height: 35px; border-radius: 8px;" onmouseover="this.style.background='{{ $is_esensial ? 'rgba(255, 255, 255, 0.15)' : 'rgba(6, 182, 212, 0.1)' }}';" onmouseout="this.style.background='transparent';">
                <i class="fa fa-bars"></i>
            </a>
            <div style="height: 20px; width: 1px; background: {{ $is_esensial ? 'rgba(255,255,255,0.2)' : 'rgba(255,255,255,0.05)' }};"></div>
            <span style="color: {{ $is_esensial ? '#ffffff' : '#94a3b8' }}; font-size: 11px; font-weight: 700; letter-spacing: 3px; text-transform: uppercase; font-family: 'Inter', sans-serif;">{{ $is_esensial ? 'XOSID ESENSIAL | SISTEM INFORMASI DESA' : 'XOSID SUPRADESA | MANAGEMENT SYSTEM' }}</span>
        </div>

        <!-- Right Side: User Profile -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav" style="display: flex; align-items: center; margin: 0;">
                <!-- Theme Toggle Button -->
                <li>
                    <a href="javascript:void(0)" id="theme-toggle" style="color: {{ $is_esensial ? '#ffffff' : 'var(--text-muted, #64748b)' }}; font-size: 18px; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; width: 35px; height: 35px; border-radius: 8px; margin-right: 10px;" onmouseover="this.style.background='{{ $is_esensial ? 'rgba(255, 255, 255, 0.15)' : 'rgba(6, 182, 212, 0.1)' }}';" onmouseout="this.style.background='transparent';">
                        <i class="fa fa-sun-o" id="theme-icon"></i>
                    </a>
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="display: flex !important; align-items: center !important; gap: 15px; background: transparent !important; border: none !important; padding: 5px 10px !important; border-radius: 12px; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.1)';" onmouseout="this.style.background='transparent';">
                        <div style="text-align: right; line-height: 1.2;" class="hidden-xs">
                            <div style="font-size: 13px; font-weight: 700; color: #ffffff;">{{ $auth->nama }}</div>
                            <div style="display: inline-block; background: {{ $is_esensial ? 'rgba(255, 255, 255, 0.18)' : 'rgba(6, 182, 212, 0.1)' }}; border: 1px solid {{ $is_esensial ? 'rgba(255, 255, 255, 0.35)' : 'rgba(6, 182, 212, 0.2)' }}; padding: 2px 8px; border-radius: 4px; margin-top: 4px;">
                                <span style="font-size: 8px; color: #ffffff; font-weight: 900; text-transform: uppercase; letter-spacing: 1.5px;">{{ $is_esensial ? 'ADMIN DESA MANDIRI' : 'SUPRA ADMIN ACCESS' }}</span>
                            </div>
                        </div>
                        <div style="position: relative; flex-shrink: 0;">
                            <?php if (isset($auth->foto) && $auth->foto != 'kuser.png' && file_exists(FCPATH . 'assets/files/user_pict/' . $auth->foto)): ?>
                                <img src="<?= base_url('assets/files/user_pict/' . $auth->foto) ?>" style="width: 38px; height: 38px; border: 2px solid {{ $is_esensial ? '#ffffff' : '#06b6d4' }}; border-radius: 12px; object-fit: cover; box-shadow: 0 0 10px {{ $is_esensial ? 'rgba(255, 255, 255, 0.3)' : 'rgba(6, 182, 212, 0.2)' }};" alt="User Image">
                            <?php else: ?>
                                <div style="width: 38px; height: 38px; background: {{ $is_esensial ? '#ffffff' : 'linear-gradient(135deg, #1e293b 0%, #0f172a 100%)' }}; border: 2px solid {{ $is_esensial ? '#ffffff' : '#06b6d4' }}; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; font-weight: 900; font-size: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                    <span style="font-family: 'Outfit', sans-serif;">X</span>
                                </div>
                            <?php endif; ?>
                            <div style="position: absolute; bottom: -3px; right: -3px; width: 12px; height: 12px; background: #22c55e; border: 2px solid {{ $is_esensial ? '#153E7E' : '#0f172a' }}; border-radius: 50%; box-shadow: 0 0 10px #22c55e;"></div>
                        </div>
                    </a>
                    <ul class="dropdown-menu" style="top: 55px !important; right: 0 !important; background: {{ $is_esensial ? '#ffffff' : 'var(--dropdown-bg, #1e293b)' }}; border: 1px solid {{ $is_esensial ? '#e2e8f0' : 'var(--dropdown-border, rgba(255,255,255,0.1))' }}; border-radius: 14px; width: 240px; padding: 8px; box-shadow: 0 20px 50px rgba(0,0,0,0.15); overflow: hidden;">
                        <li style="padding: 15px 15px 10px; border-bottom: 1px solid {{ $is_esensial ? '#f1f5f9' : 'rgba(255,255,255,0.05)' }}; margin-bottom: 8px;">
                            <span style="display: block; font-size: 10px; color: {{ $is_esensial ? '#64748b' : '#64748b' }}; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Terhubung Sebagai</span>
                            <span style="display: block; font-size: 14px; color: {{ $is_esensial ? '#0f172a' : 'white' }}; font-weight: 700;">{{ $auth->nama }}</span>
                        </li>
                        <li>
                            <a href="<?= site_url('man_user/form/' . $_SESSION['user_id']) ?>" style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 600; padding: 12px; border-radius: 10px; display: flex; align-items: center; gap: 12px; transition: all 0.3s;" onmouseover="this.style.background='{{ $is_esensial ? '#f1f5f9' : 'rgba(255, 255, 255, 0.05)' }}'; this.style.color='#153E7E';" onmouseout="this.style.background='transparent'; this.style.color='{{ $is_esensial ? '#334155' : '#94a3b8' }}';">
                                <i class="fa fa-user-circle" style="font-size: 16px;"></i> Pengaturan Profil
                            </a>
                        </li>
                        <li>
                            <a href="{{ site_url('siteman/logout') }}" style="color: #ef4444; font-weight: 600; padding: 12px; border-radius: 10px; display: flex; align-items: center; gap: 12px; transition: all 0.3s;" onmouseover="this.style.background='rgba(239, 68, 68, 0.1)';" onmouseout="this.style.background='transparent';">
                                <i class="fa fa-power-off" style="font-size: 16px;"></i> Logout System
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<style>
    header.main-header {
        z-index: 1200 !important;
        height: 60px !important;
    }

    /* XOSID SIDEBAR/HEADER ENGINE – Desktop (≥768px) */
    @media (min-width: 768px) {
        #sidebar_collapse .main-sidebar,
        body:not(.sidebar-collapse) .main-sidebar {
            width: 280px !important;
            transition: width 0.3s ease-in-out !important;
        }

        #sidebar_collapse .main-header .logo,
        body:not(.sidebar-collapse) .main-header .logo {
            width: 280px !important;
            transition: width 0.3s ease-in-out !important;
        }
        #sidebar_collapse .main-header .navbar,
        body:not(.sidebar-collapse) .main-header .navbar {
            margin-left: 280px !important;
            transition: margin-left 0.3s ease-in-out !important;
        }

        #sidebar_collapse .content-wrapper,
        #sidebar_collapse .main-footer,
        body:not(.sidebar-collapse) .content-wrapper,
        body:not(.sidebar-collapse) .main-footer {
            margin-left: 280px !important;
            transition: margin-left 0.3s ease-in-out !important;
        }

        /* COLLAPSED STATE (76px) */
        #sidebar_collapse.sidebar-collapse .main-sidebar,
        body.sidebar-collapse .main-sidebar {
            width: 76px !important;
        }
        #sidebar_collapse.sidebar-collapse .main-header .logo,
        body.sidebar-collapse .main-header .logo {
            width: 76px !important;
            padding: 0 !important;
            justify-content: center !important;
        }
        #sidebar_collapse.sidebar-collapse .main-header .logo .logo-lg-text,
        body.sidebar-collapse .main-header .logo .logo-lg-text {
            display: none !important;
        }
        #sidebar_collapse.sidebar-collapse .main-header .navbar,
        body.sidebar-collapse .main-header .navbar {
            margin-left: 76px !important;
        }
        #sidebar_collapse.sidebar-collapse .content-wrapper,
        #sidebar_collapse.sidebar-collapse .main-footer,
        body.sidebar-collapse .content-wrapper,
        body.sidebar-collapse .main-footer {
            margin-left: 76px !important;
            left: 76px !important;
        }
    }
</style>

<style>
    :root {
        --header-bg: {{ $is_esensial ? '#153E7E' : '#0f172a' }};
        --header-border: {{ $is_esensial ? 'rgba(255, 255, 255, 0.15)' : 'rgba(255,255,255,0.05)' }};
        --text-main: white;
        --text-main2: #f8fafc;
        --text-muted: {{ $is_esensial ? '#ffffff' : '#64748b' }};
        --text-muted2: {{ $is_esensial ? '#ffffff' : '#94a3b8' }};
        --dropdown-bg: {{ $is_esensial ? '#ffffff' : '#1e293b' }};
        --dropdown-border: {{ $is_esensial ? '#e2e8f0' : 'rgba(255,255,255,0.1)' }};
        --hover-bg: {{ $is_esensial ? 'rgba(255,255,255,0.1)' : 'rgba(255,255,255,0.03)' }};
        --avatar-bg: {{ $is_esensial ? '#ffffff' : 'linear-gradient(135deg, #1e293b 0%, #0f172a 100%)' }};
    }

    body.light-theme {
        --neural-bg: #f8fafc;
        --neural-card: #ffffff;
        --neural-border: rgba(0, 0, 0, 0.1);
        --text-primary: #0f172a;
        --text-secondary: #334155;
        --text-muted: #64748b;
    }

    body.light-theme .content-wrapper,
    body.light-theme .wrapper,
    body.light-theme .content,
    body.light-theme .main-footer {
        background-color: #f8fafc !important;
        background: #f8fafc !important;
        color: #0f172a !important;
    }

    body.light-theme .box,
    body.light-theme .card,
    body.light-theme .glass-card {
        background-color: #ffffff !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03) !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
        color: #0f172a !important;
    }

    body.light-theme .box-header,
    body.light-theme .card-header {
        color: #0f172a !important;
        border-bottom: 1px solid rgba(0,0,0,0.05) !important;
    }

    body.light-theme .content-wrapper h1, body.light-theme .content-wrapper h2, body.light-theme .content-wrapper h3, 
    body.light-theme .content-wrapper h4, body.light-theme .content-wrapper h5, body.light-theme .content-wrapper h6 {
        color: #0f172a !important;
    }

    body.light-theme .content-wrapper p, body.light-theme .content-wrapper label, body.light-theme .content-wrapper span {
        color: #334155;
    }

    body.light-theme table.table {
        color: #0f172a !important;
    }
    body.light-theme table.table thead th {
        border-bottom: 2px solid rgba(0,0,0,0.1) !important;
        background: #f1f5f9 !important;
    }
    body.light-theme table.table tbody td {
        border-top: 1px solid rgba(0,0,0,0.05) !important;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const themeToggle = document.getElementById("theme-toggle");
        const themeIcon = document.getElementById("theme-icon");
        const body = document.body;
        
        if (!themeToggle) return;
        
        const currentTheme = localStorage.getItem("xosid-theme") || "dark";
        if (currentTheme === "light") {
            body.classList.add("light-theme");
            themeIcon.classList.remove("fa-sun-o");
            themeIcon.classList.add("fa-moon-o");
        }

        themeToggle.addEventListener("click", function(e) {
            e.preventDefault();
            body.classList.toggle("light-theme");
            
            if (body.classList.contains("light-theme")) {
                localStorage.setItem("xosid-theme", "light");
                themeIcon.classList.remove("fa-sun-o");
                themeIcon.classList.add("fa-moon-o");
            } else {
                localStorage.setItem("xosid-theme", "dark");
                themeIcon.classList.remove("fa-moon-o");
                themeIcon.classList.add("fa-sun-o");
            }
        });
    });
</script>
