@extends('admin.layouts.index')

@push('css')
    <style>
        .radius {
            border-radius: 5px;
        }

        .small-box {
            border-radius: 5px;
            padding-bottom: 27px;
        }

        .small-box .icon {
            top: -5px;
        }

        .small-box:hover {
            transform: scale(1.01);
            transition: 0.3s;
        }
    </style>
@endpush

@section('title')
    <h1>
        Status IDM Desa
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Status IDM Desa</li>
@endsection

@section('content')

@php
    $has_valid_idm = isset($idm) && is_object($idm) && isset($idm->SUMMARIES) && isset($idm->SUMMARIES->SKOR_SAAT_INI) && floatval($idm->SUMMARIES->SKOR_SAAT_INI) > 0;
    
    if (!$has_valid_idm) {
        try {
            $active_tahun = isset($tahun) && !empty($tahun) ? $tahun : date('Y');
            
            $pdo = null;
            if (function_exists('xosid_get_db_pdo')) {
                $pdo = xosid_get_db_pdo();
            }
            if (!$pdo) {
                $db_host = 'db';
                $db_user = 'root';
                $db_pass = 'password';
                $db_name = 'xosid';
                
                $env_path = (defined('FCPATH') ? FCPATH : __DIR__ . '/../../../../') . '.env';
                if (file_exists($env_path)) {
                    $env = file_get_contents($env_path);
                    if (preg_match('/DB_HOST=(.*)/', $env, $m)) $db_host = trim($m[1]);
                    if (preg_match('/DB_USERNAME=(.*)/', $env, $m)) $db_user = trim($m[1]);
                    if (preg_match('/DB_PASSWORD=(.*)/', $env, $m)) $db_pass = trim($m[1]);
                    if (preg_match('/DB_DATABASE=(.*)/', $env, $m)) $db_name = trim($m[1]);
                }
                
                $db_host = trim($db_host, "\"'");
                $db_user = trim($db_user, "\"'");
                $db_pass = trim($db_pass, "\"'");
                $db_name = trim($db_name, "\"'");
                if ($db_host === 'localhost' || $db_host === '127.0.0.1') {
                    $db_host = 'db';
                }
                $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
            }
            
            $config = [];
            if ($pdo) {
                $stmt = $pdo->query("SELECT kode_desa, nama_desa, kode_kecamatan, nama_kecamatan, kode_kabupaten, nama_kabupaten, kode_propinsi, nama_propinsi FROM config LIMIT 1");
                $config = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
            }
            $kode_desa = $config['kode_desa'] ?? null;
            
            $base_paths = [
                defined('FCPATH') ? FCPATH : '',
                __DIR__ . '/../../../../',
                '/var/www/html/'
            ];
            
            $loaded_data = null;
            if ($kode_desa) {
                foreach ($base_paths as $bp) {
                    if (empty($bp)) continue;
                    $cache_file = rtrim($bp, '/') . "/desa/cache/idm_{$active_tahun}_{$kode_desa}.json";
                    if (file_exists($cache_file)) {
                        $raw = file_get_contents($cache_file);
                        $cdata = @unserialize($raw);
                        if (!$cdata) $cdata = json_decode($raw, true);
                        $d = $cdata['data'] ?? $cdata;
                        if (!empty($d) && is_array($d) && (isset($d['SUMMARIES']) || isset($d['ROW']))) {
                            $loaded_data = $d;
                            break;
                        }
                    }
                }
                
                // Fallback to any available year cache for this village
                if (!$loaded_data) {
                    foreach ($base_paths as $bp) {
                        if (empty($bp)) continue;
                        $glob_files = glob(rtrim($bp, '/') . "/desa/cache/idm_*_{$kode_desa}.json");
                        if (!empty($glob_files)) {
                            rsort($glob_files);
                            foreach ($glob_files as $gf) {
                                $raw = file_get_contents($gf);
                                $cdata = @unserialize($raw);
                                if (!$cdata) $cdata = json_decode($raw, true);
                                $d = $cdata['data'] ?? $cdata;
                                if (!empty($d) && is_array($d) && (isset($d['SUMMARIES']) || isset($d['ROW']))) {
                                    $loaded_data = $d;
                                    break 2;
                                }
                            }
                        }
                    }
                }
            }
            
            if ($loaded_data) {
                if (isset($loaded_data['ROW']) && is_array($loaded_data['ROW'])) {
                    foreach ($loaded_data['ROW'] as &$row) {
                        if (isset($row['PROV'])) $row['PROVINSI'] = $row['PROV'];
                        if (isset($row['KAB'])) $row['KABUPATEN'] = $row['KAB'];
                    }
                }
                $idm = json_decode(json_encode($loaded_data));
                $idm->error_msg = null;
            } else {
                // Ensure $idm is always a complete, non-null safe object
                $idm = (object)[
                    'SUMMARIES' => (object)[
                        'SKOR_SAAT_INI' => 0.0,
                        'STATUS' => 'BELUM TERSEDIA',
                        'SKOR_MINIMAL' => 0.0,
                        'TARGET_STATUS' => '-'
                    ],
                    'IDENTITAS' => [
                        (object)[
                            'nama_provinsi' => $config['nama_propinsi'] ?? '',
                            'nama_kab_kota' => $config['nama_kabupaten'] ?? '',
                            'nama_kecamatan' => $config['nama_kecamatan'] ?? '',
                            'nama_desa' => $config['nama_desa'] ?? ''
                        ]
                    ],
                    'ROW' => [],
                    'error_msg' => 'Data IDM Tahun ' . $active_tahun . ' belum tersedia di server Kemendesa untuk desa ini. Silakan klik tombol <b>Perbarui</b> di atas untuk mengambil data langsung dari Kemendesa.'
                ];
            }
        } catch (\Throwable $e) {
            $idm = (object)[
                'SUMMARIES' => (object)[
                    'SKOR_SAAT_INI' => 0.0,
                    'STATUS' => 'BELUM TERSEDIA',
                    'SKOR_MINIMAL' => 0.0,
                    'TARGET_STATUS' => '-'
                ],
                'IDENTITAS' => [
                    (object)[
                        'nama_provinsi' => '',
                        'nama_kab_kota' => '',
                        'nama_kecamatan' => '',
                        'nama_desa' => ''
                    ]
                ],
                'ROW' => [],
                'error_msg' => 'Data IDM Tahun ' . (isset($tahun) ? $tahun : date('Y')) . ' belum dapat dimuat. Silakan klik tombol <b>Perbarui</b> di atas.'
            ];
        }
    }
@endphp

    @include('admin.layouts.components.notifikasi')

    @include('admin.status_desa.navigasi')

    <div class="box box-info">
        <div class="box-header with-border">
            {!! form_open(ci_route('status_desa'), 'class="form-inline" id="mainform" name="mainform"') !!}
            <label for="tahun">IDM Tahun </label>
            <select class="form-control input-sm" name="tahun" onchange="$('#mainform').submit()">
                <option value="" disabled>Pilih Tahun</option>
                @foreach (tahun(\App\Models\SettingAplikasi::TAHUN_IDM_MIN) as $thn)
                    <option value="{{ $thn }}" @selected($tahun === $thn)>{{ $thn }}</option>
                @endforeach
            </select>
            @if (can('u'))
                <a class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Perbarui" {!! cek_koneksi_internet() == false || (!empty($idm->error_msg) && $idm->error_msg === 'Periksa koneksi internet Anda.') ? 'disabled title="Perangkat tidak terhubung dengan jaringan"' : 'href="' . ci_route('status_desa.perbarui_idm', $tahun) . '"' !!}><i class="fa fa-refresh"></i>Perbarui</a>
                @if (empty($idm->error_msg) && !empty($idm->ROW))
                    <a class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Simpan" href="{{ ci_route('status_desa.simpan', $tahun) }}"><i class="fa fa-check-circle"></i>Simpan</a>
                @endif
            @endif
            </form>
        </div>
        <div class="box-body">
            @if (!empty($idm->error_msg) || empty($idm->SUMMARIES) || empty($idm->ROW))
                <div class="alert alert-info" style="border-radius: 8px;">
                    <i class="fa fa-info-circle"></i> {!! !empty($idm->error_msg) ? $idm->error_msg : 'Data IDM untuk tahun ini belum tersedia. Silakan klik tombol <b>Perbarui</b> di atas untuk mengambil data dari Kemendesa.' !!}
                </div>
            @else
                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        <div class="row">
                            <div class="col-lg-6 col-xs-12">
                                <div class="small-box bg-blue radius">
                                    <div class="inner">
                                        <h3>{{ number_format($idm->SUMMARIES->SKOR_SAAT_INI ?? 0, 4) }}</h3>
                                        <p>SKOR IDM SAAT INI</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <div class="small-box bg-yellow radius">
                                    <div class="inner">
                                        <h3>{{ $idm->SUMMARIES->STATUS ?? '-' }}</h3>
                                        <p>STATUS IDM</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion-ios-pulse-strong"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <div class="small-box bg-red radius">
                                    <div class="inner">
                                        <h3>{{ number_format($idm->SUMMARIES->SKOR_MINIMAL ?? 0, 4) }}</h3>
                                        <p>SKOR IDM MINIMAL</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-ios-pie"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <div class="small-box bg-green radius">
                                    <div class="inner">
                                        <h3>{{ $idm->SUMMARIES->TARGET_STATUS ?? '-' }}</h3>
                                        <p>TARGET STATUS</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-arrow-graph-up-right"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dataTable table-hover">
                                        <tbody>
                                            <tr>
                                                <td width="30%">PROVINSI</td>
                                                <td width="1">:</td>
                                                <td>{{ $idm->IDENTITAS[0]->nama_provinsi ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>KABUPATEN</td>
                                                <td> : </td>
                                                <td>{{ $idm->IDENTITAS[0]->nama_kab_kota ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ strtoupper(setting('sebutan_kecamatan')) }}</td>
                                                <td> : </td>
                                                <td>{{ $idm->IDENTITAS[0]->nama_kecamatan ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ strtoupper(setting('sebutan_desa')) }}</td>
                                                <td> : </td>
                                                <td>{{ $idm->IDENTITAS[0]->nama_desa ?? '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-xs-12">
                        <figure class="highcharts-figure">
                            <div id="container"></div>
                        </figure>
                    </div>
                </div>

                <div class="row">
                    <hr>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
                                <thead class="bg-gray color-palette">
                                    <tr>
                                        <th rowspan="2" class="padat">NO</th>
                                        <th rowspan="2">INDIKATOR IDM</th>
                                        <th rowspan="2">SKOR</th>
                                        <th rowspan="2">KETERANGAN</th>
                                        <th rowspan="2" nowrap>KEGIATAN YANG DAPAT DILAKUKAN</th>
                                        <th rowspan="2">+NILAI</th>
                                        <th colspan="6" class="text-center">YANG DAPAT MELAKSANAKAN KEGIATAN</th>
                                    </tr>
                                    <tr>
                                        <th>PUSAT</th>
                                        <th>PROVINSI</th>
                                        <th>KABUPATEN</th>
                                        <th>DESA</th>
                                        <th>CSR</th>
                                        <th>LAINNYA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (($idm->ROW ?? []) as $data)
                                        <tr class="{{ empty($data->NO) && (print 'judul') }} ">
                                            <td class="text-center">{{ $data->NO ?? '' }}</td>
                                            <td style="min-width: 150px;">{{ $data->INDIKATOR ?? '' }}</td>
                                            <td class="padat">{{ $data->SKOR ?? '' }}</td>
                                            <td style="min-width: 250px;">{{ $data->KETERANGAN ?? '' }}</td>
                                            <td>{{ $data->KEGIATAN ?? '' }}</td>
                                            <td class="padat">{{ $data->NILAI ?? '' }}</td>
                                            <td>{{ $data->PUSAT ?? '' }}</td>
                                            <td>{{ $data->PROV ?? '' }}</td>
                                            <td>{{ $data->KAB ?? '' }}</td>
                                            <td>{{ $data->DESA ?? '' }}</td>
                                            <td>{{ $data->CSR ?? '' }}</td>
                                            <td>{{ $data->LAINNYA ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection

@if (empty($idm->error_msg) && !empty($idm->ROW) && isset($idm->ROW[35]) && isset($idm->ROW[48]) && isset($idm->ROW[52]))
    @push('scripts')
        @include('admin.layouts.components.asset_highcharts')
        <script>
            $(document).ready(function() {

                var tahun = {{ $tahun ?? date('Y') }};
                var iks = {{ $idm->ROW[35]->SKOR ?? 0 }};
                var ike = {{ $idm->ROW[48]->SKOR ?? 0 }};
                var ikl = {{ $idm->ROW[52]->SKOR ?? 0 }};

                Highcharts.chart('container', {
                    chart: {
                        type: 'pie',
                        options3d: {
                            enabled: true,
                            alpha: 45
                        }
                    },
                    title: {
                        text: 'Indeks Desa Membangun (IDM) ' + tahun
                    },
                    subtitle: {
                        text: 'SKOR : IKS, IKE, IKL'
                    },

                    plotOptions: {
                        series: {
                            colorByPoint: true
                        },
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            showInLegend: true,
                            depth: 45,
                            innerSize: 70,
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.y:,.2f} / {point.percentage:.1f} %'
                            }
                        }
                    },
                    series: [{
                        name: 'SKOR',
                        shadow: 1,
                        border: 1,
                        data: [
                            ['IKS', iks],
                            ['IKE', ike],
                            ['IKL', ikl]
                        ]
                    }]
                });
            });
        </script>
    @endpush
@endif
