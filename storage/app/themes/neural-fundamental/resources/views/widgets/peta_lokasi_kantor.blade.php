<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box box-primary box-solid">
    <div class="box-header">
        <h3 class="box-title">
            <i class="fas fa-map-marker-alt mr-1"></i>{{ $judul_widget }}
        </h3>
    </div>
    <div class="box-body">
        <div id="map_canvas" style="height:200px;"></div>
        <div class="flex flex-row gap-3 mt-5 w-full">
            <a href="https://www.openstreetmap.org/#map=15/{{ $desa['lat'] }}/{{ $desa['lng'] }}" target="_blank" class="btn-cyber-cyan text-sm flex-1 text-center">
                <span class="xosid-btn-dot-cyan"></span>Buka Peta
            </a>
            <button class="btn-cyber-pink text-sm flex-1 text-center" data-bs-toggle="modal" data-bs-target="#detail">
                <span class="xosid-btn-dot-pink"></span>Detail
            </button>
        </div>
        <!-- Modal -->
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="detail" tabindex="-1" aria-labelledby="detailLabel" aria-hidden="true">
            <div class="modal-dialog relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                        <h5 class="text-xl font-medium leading-normal text-gray-800" id="detailLabel">Detail {{ ucwords(setting('sebutan_desa')) }}</h5>
                        <button type="button" class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body relative p-4 content">
                        <table class="max-w-full text-xs lg:text-sm table-auto w-full">
                            <tbody>
                                <tr>
                                    <td>Alamat</td>
                                    <td>:</td>
                                    <td>{{ $desa['alamat_kantor'] }}</td>
                                </tr>
                                <tr>
                                    <td>{{ ucwords(setting('sebutan_desa')) }}</td>
                                    <td>:</td>
                                    <td>{{ $desa['nama_desa'] }}</td>
                                </tr>
                                <tr>
                                    <td>{{ ucwords(setting('sebutan_kecamatan')) }}</td>
                                    <td>:</td>
                                    <td>{{ $desa['nama_kecamatan'] }}</td>
                                </tr>
                                <tr>
                                    <td>{{ ucwords(setting('sebutan_kabupaten')) }}</td>
                                    <td>:</td>
                                    <td>{{ $desa['nama_kabupaten'] }}</td>
                                </tr>
                                <tr>
                                    <td>Kodepos</td>
                                    <td>:</td>
                                    <td>{{ $desa['kode_pos'] }}</td>
                                </tr>
                                <tr>
                                    <td>Telepon</td>
                                    <td>:</td>
                                    <td>{{ $desa['telepon'] }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <td>{{ $desa['email_desa'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #map_canvas {
        border: 2px solid rgba(6, 182, 212, 0.5);
        border-radius: 0.5rem;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.4);
        overflow: hidden;
    }
    #map_canvas .leaflet-control-attribution { font-size: 0 !important; }
    #map_canvas .leaflet-control-attribution::after { content: "xoSID Spatial Studio"; font-size: 11px; }
    
    .pulse-icon {
        width: 20px;
        height: 20px;
        background-color: #06b6d4;
        border-radius: 50%;
        border: 2px solid white;
        animation: pulse-animation 2s infinite;
    }
    
    @keyframes pulse-animation {
        0% { box-shadow: 0 0 0 0 rgba(6, 182, 212, 0.7); }
        70% { box-shadow: 0 0 0 15px rgba(6, 182, 212, 0); }
        100% { box-shadow: 0 0 0 0 rgba(6, 182, 212, 0); }
    }
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

    var lokasi_kantor = L.map('map_canvas', options).setView(posisi, zoom);

    //Menampilkan BaseLayers Peta
    var baseLayers = getBaseLayers(lokasi_kantor, "{{ setting('mapbox_key') }}", "{{ setting('jenis_peta') }}");
    
    // Tambahkan layer satelit sebagai default
    var satLayer = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        attribution: 'Google Satellite'
    });
    satLayer.addTo(lokasi_kantor);
    baseLayers['Satelit (Google)'] = satLayer;

    L.control.layers(baseLayers, null, {
        position: 'topright',
        collapsed: true
    }).addTo(lokasi_kantor);

    //Jika posisi kantor desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
    @if (!empty($desa['lat']) && !empty($desa['lng']))
        var customIcon = L.divIcon({
            className: 'custom-pulse-marker',
            html: '<div class="pulse-icon"></div>',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });
        var kantor_desa = L.marker(posisi, {icon: customIcon}).addTo(lokasi_kantor);
    @endif
</script>
