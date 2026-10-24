/**
 * XOSID Geospasial: Boundary Fetcher Engine (BIG, Emsifa, OSM)
 * Standard module for auto-fetching official village boundary polygons
 */
(function(window, document) {
    'use strict';

    function cleanString(str) {
        return (str || '').toString().trim();
    }

    function calculateCentroid(latlngs) {
        var pts = Array.isArray(latlngs[0]) && Array.isArray(latlngs[0][0]) ? latlngs[0] : latlngs;
        var latSum = 0, lngSum = 0, count = 0;
        for (var i = 0; i < pts.length; i++) {
            var pt = pts[i];
            if (Array.isArray(pt) && pt.length >= 2) {
                latSum += parseFloat(pt[0]);
                lngSum += parseFloat(pt[1]);
                count++;
            }
        }
        return count > 0 ? [latSum / count, lngSum / count] : null;
    }

    window.xosidFetchBoundary = function(options) {
        options = options || {};
        var desaName = cleanString(options.desaName);
        var kecName = cleanString(options.kecName);
        var kabName = cleanString(options.kabName);
        var kodeDesa = cleanString(options.kodeDesa);
        var targetMap = options.map || window.peta_wilayah || window.peta_kantor;
        var isKantor = !!options.isKantor;

        if (!desaName) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nama Desa Tidak Ditemukan',
                    text: 'Pastikan identitas desa (nama desa & kecamatan) telah terisi.'
                });
            } else {
                alert('Nama Desa tidak ditemukan.');
            }
            return;
        }

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Tarik Batas Resmi BIG',
                html: '<div style="font-family:\'Outfit\',sans-serif;text-align:center;">' +
                      '<div style="font-size:14px;color:#475569;margin-bottom:12px;">Mencari data batas resmi untuk:</div>' +
                      '<div style="font-size:16px;font-weight:800;color:#153E7E;background:#eff6ff;padding:8px 12px;border-radius:10px;border:1px solid #bfdbfe;display:inline-block;">' +
                      desaName + (kecName ? ', Kec. ' + kecName : '') +
                      '</div>' +
                      '<div style="font-size:12px;color:#94a3b8;margin-top:12px;">Menghubungi Server Geoservices Badan Informasi Geospasial (BIG)...</div>' +
                      '</div>',
                allowOutsideClick: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });
        }

        // STRATEGY 1: Query BIG ArcGIS REST Service
        var queryUrl = 'https://geoservices.big.go.id/rbi/rest/services/BATASWILAYAH/Administrasi_AR_KelDesa_10K/MapServer/0/query' +
                       '?where=UPPER(NAMOBJ)+LIKE+\'%25' + encodeURIComponent(desaName.toUpperCase()) + '%25\'' +
                       '&outFields=NAMOBJ,WADMKC,WADMKK,WADMPR,KDEPUM,OBJECTID' +
                       '&f=json';

        $.ajax({
            url: queryUrl,
            method: 'GET',
            dataType: 'json',
            timeout: 15000
        }).done(function(data) {
            var feature = null;
            if (data && data.features && data.features.length > 0) {
                // Filter best match with kecamatan or kabupaten
                if (data.features.length === 1) {
                    feature = data.features[0];
                } else {
                    var upperKec = kecName.toUpperCase();
                    var upperKab = kabName.toUpperCase();
                    for (var i = 0; i < data.features.length; i++) {
                        var f = data.features[i];
                        var fKec = (f.attributes && f.attributes.WADMKC ? f.attributes.WADMKC.toUpperCase() : '');
                        var fKab = (f.attributes && f.attributes.WADMKK ? f.attributes.WADMKK.toUpperCase() : '');
                        if ((upperKec && fKec.indexOf(upperKec) !== -1) || (upperKab && fKab.indexOf(upperKab) !== -1)) {
                            feature = f;
                            break;
                        }
                    }
                    if (!feature) {
                        feature = data.features[0];
                    }
                }
            }

            if (feature && feature.geometry && feature.geometry.rings) {
                applyRings(feature.geometry.rings, feature.attributes, 'Badan Informasi Geospasial (BIG)');
            } else {
                fallbackEmsifa();
            }
        }).fail(function() {
            fallbackEmsifa();
        });

        function fallbackEmsifa() {
            if (typeof Swal !== 'undefined') {
                Swal.update({
                    html: '<div style="font-family:\'Outfit\',sans-serif;text-align:center;">' +
                          '<div style="font-size:13px;color:#f59e0b;font-weight:700;margin-bottom:8px;">Server BIG Sedang Padat</div>' +
                          '<div style="font-size:12px;color:#64748b;">Mencoba jalur cadangan (Emsifa / OSM Database)...</div>' +
                          '</div>'
                });
            }

            // STRATEGY 2: Query OpenStreetMap Nominatim GeoJSON
            var q = desaName + (kecName ? ', ' + kecName : '') + (kabName ? ', ' + kabName : '');
            var osmUrl = 'https://nominatim.openstreetmap.org/search?q=' + encodeURIComponent(q) +
                         '&format=geojson&polygon_geojson=1&countrycodes=id';

            $.ajax({
                url: osmUrl,
                method: 'GET',
                dataType: 'json',
                timeout: 10000
            }).done(function(osmData) {
                if (osmData && osmData.features && osmData.features.length > 0) {
                    for (var i = 0; i < osmData.features.length; i++) {
                        var f = osmData.features[i];
                        if (f.geometry && (f.geometry.type === 'Polygon' || f.geometry.type === 'MultiPolygon')) {
                            applyGeoJsonGeometry(f.geometry, { NAMOBJ: desaName, WADMKC: kecName }, 'OpenStreetMap GeoJSON');
                            return;
                        }
                    }
                }
                showNotFound();
            }).fail(function() {
                showNotFound();
            });
        }

        function showNotFound() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'Batas Belum Tersedia di Server Pusat',
                    html: '<div style="font-family:\'Outfit\',sans-serif;text-align:left;font-size:13px;color:#475569;">' +
                          '<p>Data batas digital untuk <b>' + desaName + '</b> belum ditemukan pada indeks pencarian otomatis BIG.</p>' +
                          '<p style="margin-bottom:0;">Anda dapat menggambar batas langsung menggunakan alat poligon di peta, atau mengimpor file <b>GPX / SHP</b> dari bagian menu bawah.</p>' +
                          '</div>'
                });
            } else {
                alert('Data batas poligon tidak ditemukan di server pusat.');
            }
        }

        function applyRings(rings, meta, sourceName) {
            // Convert ESRI rings [ [ [lng, lat], ... ] ] to Leaflet [ [lat, lng], ... ]
            var latlngs = [];
            for (var r = 0; r < rings.length; r++) {
                var ring = rings[r];
                var ringLatLngs = [];
                for (var p = 0; p < ring.length; p++) {
                    var pt = ring[p];
                    ringLatLngs.push([parseFloat(pt[1]), parseFloat(pt[0])]);
                }
                if (ringLatLngs.length > 0) {
                    latlngs.push(ringLatLngs);
                }
            }

            var finalCoords = (latlngs.length === 1) ? latlngs[0] : latlngs;
            renderBoundaryOnMap(finalCoords, meta, sourceName);
        }

        function applyGeoJsonGeometry(geom, meta, sourceName) {
            var latlngs = [];
            if (geom.type === 'Polygon') {
                for (var r = 0; r < geom.coordinates.length; r++) {
                    var ring = geom.coordinates[r];
                    var ringPts = [];
                    for (var p = 0; p < ring.length; p++) {
                        ringPts.push([parseFloat(ring[p][1]), parseFloat(ring[p][0])]);
                    }
                    if (ringPts.length > 0) latlngs.push(ringPts);
                }
                if (latlngs.length === 1) latlngs = latlngs[0];
            } else if (geom.type === 'MultiPolygon') {
                for (var m = 0; m < geom.coordinates.length; m++) {
                    var poly = geom.coordinates[m];
                    var polyRings = [];
                    for (var pr = 0; pr < poly.length; pr++) {
                        var pRing = poly[pr];
                        var pPts = [];
                        for (var pp = 0; pp < pRing.length; pp++) {
                            pPts.push([parseFloat(pRing[pp][1]), parseFloat(pRing[pp][0])]);
                        }
                        if (pPts.length > 0) polyRings.push(pPts);
                    }
                    if (polyRings.length > 0) latlngs.push(polyRings);
                }
            }

            renderBoundaryOnMap(latlngs, meta, sourceName);
        }

        function renderBoundaryOnMap(coords, meta, sourceName) {
            var pathStr = JSON.stringify(coords);
            var pathInput = document.getElementById('path');
            if (pathInput) {
                pathInput.value = pathStr;
            }

            var centroid = calculateCentroid(coords);

            if (isKantor) {
                // If on maps_kantor, update lat/lng coordinates
                if (centroid) {
                    var latEl = document.getElementById('lat');
                    var lngEl = document.getElementById('lng');
                    if (latEl) latEl.value = centroid[0].toFixed(8);
                    if (lngEl) lngEl.value = centroid[1].toFixed(8);

                    if (targetMap) {
                        targetMap.setView(centroid, 16);
                        // Move draggable marker if exists
                        targetMap.eachLayer(function(layer) {
                            if (layer instanceof L.Marker && layer.options && layer.options.draggable) {
                                try { layer.setLatLng(centroid); } catch(e) {}
                            }
                        });
                        if (typeof $ !== 'undefined') {
                            $('#lat, #lng').trigger('change');
                        }
                    }
                }
            } else {
                // If on maps_wilayah, draw polygon on map
                if (targetMap) {
                    // Remove existing drawn polygon layers
                    targetMap.eachLayer(function(layer) {
                        if (layer instanceof L.Polygon && !layer._isBaseOverlay) {
                            try { targetMap.removeLayer(layer); } catch (e) {}
                        }
                    });

                    var color = document.getElementById('warna') ? document.getElementById('warna').value : '#23c2e9';
                    var border = document.getElementById('border') ? document.getElementById('border').value : '#06b6d4';

                    var polyLayer = L.polygon(coords, {
                        color: border || '#06b6d4',
                        fillColor: color || '#23c2e9',
                        fillOpacity: 0.35,
                        weight: 2.5,
                        dashArray: '5, 5'
                    }).addTo(targetMap);

                    targetMap.fitBounds(polyLayer.getBounds(), { padding: [30, 30] });
                }
            }

            if (typeof Swal !== 'undefined') {
                var metaHtml = '';
                if (meta) {
                    metaHtml = '<div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:12px;margin:14px 0;font-size:12.5px;color:#334155;text-align:left;">' +
                               '<div style="margin-bottom:4px;"><b>Objek:</b> ' + (meta.NAMOBJ || desaName) + '</div>' +
                               (meta.WADMKC ? '<div style="margin-bottom:4px;"><b>Kecamatan:</b> ' + meta.WADMKC + '</div>' : '') +
                               (meta.WADMKK ? '<div style="margin-bottom:4px;"><b>Kab/Kota:</b> ' + meta.WADMKK + '</div>' : '') +
                               (meta.KDEPUM ? '<div style="margin-bottom:4px;"><b>Kode Kemendagri:</b> ' + meta.KDEPUM + '</div>' : '') +
                               '<div style="margin-top:6px;padding-top:6px;border-top:1px dashed #cbd5e1;color:#153E7E;font-weight:700;"><i class="fa fa-check-circle"></i> Sumber Data: ' + sourceName + '</div>' +
                               '</div>';
                }

                var successTitle = isKantor ? 'Titik Kantor Berhasil Ditarik!' : 'Batas Wilayah Berhasil Ditarik!';
                var successDesc = isKantor 
                    ? '<p style="color:#475569;font-size:13.5px;margin:0;">Titik pusat koordinat resmi telah berhasil dihitung dan disematkan ke posisi kantor.</p>'
                    : '<p style="color:#475569;font-size:13.5px;margin:0;">Koordinat batas resmi telah berhasil dipasang pada peta.</p>';
                var saveButtonName = isKantor ? 'Simpan Lokasi' : 'Simpan Wilayah';

                Swal.fire({
                    icon: 'success',
                    title: successTitle,
                    html: '<div style="font-family:\'Outfit\',sans-serif;">' +
                          successDesc +
                          metaHtml +
                          '<p style="color:#0f172a;font-weight:700;font-size:13px;margin:8px 0 0 0;">Klik tombol <b>"' + saveButtonName + '"</b> di bagian bawah untuk menyimpan ke basis data desa.</p>' +
                          '</div>',
                    confirmButtonText: 'Terapkan & Selesai',
                    confirmButtonColor: '#153E7E'
                });
            }
        }
    };
})(window, document);
