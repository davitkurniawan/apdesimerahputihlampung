<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box box-primary box-solid">
    <div class="box-header">
        <h3 class="box-title">
            <i class="fas fa-map-marker-alt mr-1"></i>{{ $judul_widget }}
        </h3>
    </div>
    <div class="box-body">
        <div id="map_wilayah" style="height:200px;"></div>
        <div class="flex mt-5 w-full">
            <a href="https://www.openstreetmap.org/#map=15/{{ $desa['lat'] }}/{{ $desa['lng'] }}" class="btn-cyber-cyan text-sm flex-1 text-center" target="_blank">
                <span class="xosid-btn-dot-cyan"></span>Buka Peta
            </a>
        </div>
    </div>
</div>

<style>
    #map_wilayah {
        border: 2px solid rgba(6, 182, 212, 0.5);
        border-radius: 0.5rem;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.4);
        overflow: hidden;
    }
    #map_wilayah .leaflet-control-attribution { font-size: 0 !important; }
    #map_wilayah .leaflet-control-attribution::after { content: "xoSID Spatial Studio"; font-size: 11px; }
</style>

<script>
    //Jika posisi kantor desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
    @if (!empty($desa['lat']) && !empty($desa['lng']))
        var posisi = [{{ $desa['lat'] }}, {{ $desa['lng'] }}];
        var zoom = {{ $desa['zoom'] ?: 10 }};
    @else
        var posisi = [-1.0546279422758742, 116.71875000000001];
        var zoom = 10;
    @endif

    var options = {
        maxZoom: {{ setting('max_zoom_peta') }},
        minZoom: {{ setting('min_zoom_peta') }},
    };

    //Style polygon
    var style_polygon = {
        stroke: true,
        color: '#FF0000',
        opacity: 1,
        weight: 2,
        fillColor: '#8888dd',
        fillOpacity: 0.5
    };
    var wilayah_desa = L.map('map_wilayah', options).setView(posisi, zoom);

    //Menampilkan BaseLayers Peta
    var baseLayers = getBaseLayers(wilayah_desa, "{{ setting('mapbox_key') }}", "{{ setting('jenis_peta') }}");
    
    // Tambahkan layer satelit sebagai default
    var satLayer = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        attribution: 'Google Satellite'
    });
    satLayer.addTo(wilayah_desa);
    baseLayers['Satelit (Google)'] = satLayer;

    L.control.layers(baseLayers, null, {
        position: 'topright',
        collapsed: true
    }).addTo(wilayah_desa);

    @if (!empty($desa['path']))
        var polygon_desa = {!! $desa['path'] !!};
        var kantor_desa = L.polygon(polygon_desa, style_polygon).bindTooltip("Wilayah {{ ucwords(setting('sebutan_desa')) }}").addTo(wilayah_desa);
        wilayah_desa.fitBounds(kantor_desa.getBounds());
    @endif
</script>
