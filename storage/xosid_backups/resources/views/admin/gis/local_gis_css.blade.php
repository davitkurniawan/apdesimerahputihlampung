<style>
    #map {
        width: 100%;
        height: calc(100vh - 110px) !important;
    }

    /* ZERO-GAP FULL-BLEED GIS MAP FIX - NATURAL ADMINLTE FLOW */
    html body.xosid-theme div.content-wrapper[class],
    html body.fixed div.content-wrapper[class],
    html body div.content-wrapper[class],
    html body .content-wrapper[class] {
        position: relative !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        margin-left: 280px !important;
        margin-bottom: 0 !important;
        margin-right: 0 !important;
        padding: 0 !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        width: auto !important;
        min-height: calc(100vh - 110px) !important;
        background-color: #020617 !important;
        overflow: hidden !important;
    }

    html body.xosid-theme.sidebar-collapse div.content-wrapper[class],
    html body.sidebar-collapse div.content-wrapper[class],
    html body#sidebar_collapse.sidebar-collapse div.content-wrapper[class] {
        position: relative !important;
        left: 0 !important;
        margin-left: 76px !important;
        width: auto !important;
    }

    html body .content-wrapper > section.content {
        position: relative !important;
        padding: 0 !important;
        margin: 0 !important;
        height: calc(100vh - 110px) !important;
        width: 100% !important;
        overflow: hidden !important;
    }

    html body .content-wrapper > section.content .row,
    html body .content-wrapper > section.content .col-md-12,
    html body .content-wrapper > section.content form {
        padding: 0 !important;
        margin: 0 !important;
        height: 100% !important;
        width: 100% !important;
    }

    #map,
    #map .leaflet-container {
        position: relative !important;
        width: 100% !important;
        height: calc(100vh - 110px) !important;
        min-height: calc(100vh - 110px) !important;
        max-height: calc(100vh - 110px) !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
    }

    .leaflet-popup-content {
        height: auto;
        overflow-y: auto;
    }

    /* Scroll only inside Statistik Penduduk & Bantuan cards when list is long */
    .leaflet-popup-content #collapseStatPenduduk .card.card-body,
    .leaflet-popup-content #collapseStatBantuan .card.card-body {
        /* max-height: 50vh;
        overflow-y: auto; */
    }

    table {
        table-layout: fixed;
        white-space: normal !important;
    }

    td {
        word-wrap: break-word;
    }

    .persil {
        min-width: 350px;
    }

    .persil td {
        padding-right: 1rem;
    }
    [id^="collapseStat"] {
        max-height: 200px;
        overflow-y: auto;
        scrollbar-width: thin;
    }

    #map table,
    #map table.title,
    #map img.logo_mandiri,
    #qrcode,
    #qrcode img,
    .leaflet-bottom.leaflet-left #qrcode,
    [leaflet-browser-print-content] {
        display: none !important;
    }

    body.leaflet--printing [leaflet-browser-print-content] {
        display: table !important;
    }

    .logo_mandiri {
        max-width: 80px !important;
        max-height: 80px !important;
        height: auto;
        object-fit: contain;
    }

    /* Strict Leaflet Marker Icon Scaling - Fixes giant village logo overlay on map */
    .leaflet-marker-icon,
    img.leaflet-marker-icon {
        max-width: 32px !important;
        max-height: 32px !important;
        width: 32px !important;
        height: 32px !important;
        object-fit: contain !important;
    }

    /* GIS Page Layout Locking & Prevent Page Scroll Chaining */
    html, body {
        overflow: hidden !important;
        height: 100vh !important;
    }

    .wrapper {
        height: 100vh !important;
        overflow: hidden !important;
    }

    .main-sidebar {
        position: fixed !important;
        top: 0 !important;
        bottom: 0 !important;
        left: 0 !important;
        height: 100vh !important;
        overflow-y: auto !important;
        overscroll-behavior: contain !important;
        -webkit-overflow-scrolling: touch !important;
        z-index: 1030 !important;
    }

    .main-sidebar .sidebar {
        height: 100% !important;
        overflow-y: auto !important;
        overscroll-behavior: contain !important;
    }
</style>
<script>
    (function() {
        function replaceAttribution() {
            var att = document.querySelector('.leaflet-control-attribution');
            if (att) {
                var html = att.innerHTML;
                if (html.indexOf('OpenSID') !== -1) {
                    att.innerHTML = html.replace(/<a[^>]*github\.com\/OpenSID\/OpenSID[^>]*>OpenSID<\/a>/gi, '<a href="https://xosid.id" target="_blank">XOSID Studio</a>')
                                        .replace(/OpenSID/g, 'XOSID Studio');
                }
            }
        }

        function adjustXosidGisLayout() {
            var header = document.querySelector('.main-header');
            var cw = document.querySelector('.content-wrapper');
            var wrapper = document.querySelector('.wrapper');
            if (header && cw) {
                var wrapperTop = wrapper ? wrapper.getBoundingClientRect().top : 0;
                var headerBottom = header.getBoundingClientRect().bottom;
                var marginTopNeeded = Math.max(0, headerBottom - wrapperTop);
                cw.style.setProperty('margin-top', marginTopNeeded + 'px', 'important');
            }
            if (window.peta && typeof window.peta.invalidateSize === 'function') {
                window.peta.invalidateSize();
            }
        }

        setInterval(replaceAttribution, 300);
        setInterval(adjustXosidGisLayout, 300);
        document.addEventListener("DOMContentLoaded", function() {
            replaceAttribution();
            adjustXosidGisLayout();
        });
        window.addEventListener("resize", adjustXosidGisLayout);
    })();
</script>
