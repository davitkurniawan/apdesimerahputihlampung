<?php
$files = [
    '/Users/kampungjagoan/Downloads/XOSID/resources/views/admin/layouts/partials/header.blade.php',
    '/Users/kampungjagoan/Downloads/XOSID/storage/xosid_backups/resources/views/admin/layouts/partials/header.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Check if already patched
    if (strpos($content, 'id="theme-toggle"') !== false) {
        echo "Already patched: $file\n";
        continue;
    }
    
    // Add the toggle button
    $search_ul = '<ul class="nav navbar-nav" style="display: flex; align-items: center; margin: 0;">';
    $replace_ul = $search_ul . '
                <!-- Theme Toggle Button -->
                <li>
                    <a href="javascript:void(0)" id="theme-toggle" style="color: var(--text-muted, #64748b); font-size: 18px; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; width: 35px; height: 35px; border-radius: 8px; margin-right: 10px;" onmouseover="this.style.color=\'#06b6d4\'; this.style.background=\'rgba(6, 182, 212, 0.1)\';" onmouseout="this.style.color=\'var(--text-muted, #64748b)\'; this.style.background=\'transparent\';">
                        <i class="fa fa-sun-o" id="theme-icon"></i>
                    </a>
                </li>';
    $content = str_replace($search_ul, $replace_ul, $content);
    
    // Add CSS and JS at the end
    $append = '
<style>
    /* CSS Variables for Themeing */
    :root {
        --header-bg: #0f172a;
        --header-border: rgba(255,255,255,0.05);
        --text-main: white;
        --text-main2: #f8fafc;
        --text-muted: #64748b;
        --text-muted2: #94a3b8;
        --dropdown-bg: #1e293b;
        --dropdown-border: rgba(255,255,255,0.1);
        --hover-bg: rgba(255,255,255,0.03);
        --avatar-bg: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    }

    /* Light Theme Overrides */
    body.light-theme {
        --header-bg: #ffffff;
        --header-border: rgba(0,0,0,0.05);
        --text-main: #0f172a;
        --text-main2: #1e293b;
        --text-muted: #94a3b8;
        --text-muted2: #64748b;
        --dropdown-bg: #ffffff;
        --dropdown-border: rgba(0,0,0,0.1);
        --hover-bg: rgba(0,0,0,0.03);
        --avatar-bg: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const themeToggle = document.getElementById("theme-toggle");
        const themeIcon = document.getElementById("theme-icon");
        const body = document.body;
        
        if (!themeToggle) return;
        
        // Load preference from localStorage
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
';
    
    // Replace the main hardcoded colors with CSS vars
    $content = str_replace('background: #0f172a !important;', 'background: var(--header-bg, #0f172a) !important;', $content);
    $content = str_replace('border-right: 1px solid rgba(255,255,255,0.05) !important;', 'border-right: 1px solid var(--header-border, rgba(255,255,255,0.05)) !important;', $content);
    $content = str_replace('background: #1e293b;', 'background: var(--dropdown-bg, #1e293b);', $content);
    $content = str_replace('border: 1px solid rgba(255,255,255,0.1);', 'border: 1px solid var(--dropdown-border, rgba(255,255,255,0.1));', $content);
    
    // Add to end of file
    $content .= $append;
    
    file_put_contents($file, $content);
    echo "Successfully patched: $file\n";
}
?>
