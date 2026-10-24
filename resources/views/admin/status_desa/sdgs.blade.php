@extends('admin.layouts.index')

@push('css')
    <style>
        .radius {
            border-radius: 5px;
        }

        .info-box-sdgs {
            border: 1px solid;
            border-radius: 10px;
            background-color: #fff;
            border-color: #d8dbe0;
        }

        .info-box-sdgs-icon {
            width: 120px;
            height: 120px;
        }

        .info-box-sdgs-content {
            padding: 5px 10px;
            margin-left: 130px;
        }

        .info-box-sdgs-icon {
            padding-top: 0;
            background: white;
        }

        .info-box-sdgs-text {
            text-transform: capitalize;
        }

        .info-box-icon-sdgs {
            border-radius: 5px;
        }

        .sdgs-logo {
            width: 120px;
            height: 100px;
        }

        .total-bumds {
            font-size: 32px;
            font-weight: bold;
            font-stretch: normal;
            font-style: normal;
            line-height: normal;
            letter-spacing: normal;
            text-align: left;
            color: #232b39;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .desc-bumds {
            margin-top: 8px;
            font-size: 21px;
            font-weight: normal;
            font-stretch: normal;
            font-style: normal;
            line-height: normal;
            letter-spacing: normal;
            text-align: left;
            color: #5a677d;
        }
    </style>
@endpush

@section('title')
    <h1>
        Status SDGS Desa
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Status SDGS Desa</li>
@endsection

@section('content')

@php
    $has_valid_sdgs = isset($sdgs) && is_object($sdgs) && isset($sdgs->average) && floatval($sdgs->average) > 0;
    
    if (!$has_valid_sdgs) {
        try {
            if (function_exists('xosid_get_db_pdo')) {
                $pdo = xosid_get_db_pdo();
            } else {
                $db_host = 'db';
                $db_user = 'root';
                $db_pass = 'password';
                $db_name = 'xosid_tenant_bumidaya';
                
                $env_path = FCPATH . '.env';
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
            
            if ($pdo) {
                $stmt = $pdo->query("SELECT kode_desa_bps FROM config LIMIT 1");
                $config = $stmt->fetch(PDO::FETCH_ASSOC);
                $kode_desa_bps = $config['kode_desa_bps'] ?? null;
                
                if ($kode_desa_bps) {
                    $cache_file = FCPATH . "desa/cache/sdgs_{$kode_desa_bps}.json";
                    if (file_exists($cache_file)) {
                        $raw = file_get_contents($cache_file);
                        $cache_data = @unserialize($raw);
                        if (!$cache_data) {
                            $cache_data = json_decode($raw, true);
                        }
                        $sdgs_array = $cache_data['data'] ?? $cache_data;
                        if ($sdgs_array) {
                            if (isset($sdgs_array['data']) && is_array($sdgs_array['data'])) {
                                $mapped_data = [];
                                foreach ($sdgs_array['data'] as $idx => $item) {
                                    $mapped_data[] = [
                                        'score' => floatval($item['skor'] ?? $item['score'] ?? 0),
                                        'image' => 'skor-sdgs-' . ($idx + 1) . '.jpg',
                                        'label' => $item['label'] ?? $item['title'] ?? ''
                                    ];
                                }
                                $sdgs_array['data'] = $mapped_data;
                            }
                            $sdgs = json_decode(json_encode($sdgs_array));
                            $sdgs->error_msg = null;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // Ignore
        }
    }
@endphp

    @include('admin.layouts.components.notifikasi')

    @include('admin.status_desa.navigasi')

    <div class="box box-info">
        @if (can('u'))
            <div class="box-header with-border">
                <a class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Perbarui" {!! !cek_koneksi_internet() ? 'disabled title="Perangkat tidak terhubung dengan jaringan"' : 'id="perbarui"' !!}><i class="fa fa-refresh"></i>Perbarui {{ $header }}</a>
            </div>
        @endif
        <div class="box-body">
            @if ($sdgs->error_msg)
                <div class="alert alert-danger">
                    {!! $sdgs->error_msg !!}
                </div>
            @else
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="info-box info-box-sdgs" style="display: flex;justify-content: center;">
                            <span class="info-box-number total-bumds" style="text-align: center;">{{ $sdgs->average }}
                                <span class="info-box-text info-box-sdgs-text desc-bumds" style="text-align: center;">Skor
                                    SDGs
                                    {{ setting('sebutan_desa') }}</span>
                            </span>
                        </div>
                    </div>

                    @foreach ($sdgs->data as $key => $value)
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="info-box info-box-sdgs">
                                <span class="info-box-icon info-box-icon-sdgs">
                                    <img class="sdgs-logo" src="{{ asset("images/sdgs/{$value->image}") }}" alt="{{ $value->image }}">
                                </span>
                                <div class="info-box-content info-box-sdgs-content">
                                    <span class="info-box-number info-box-sdgs-number total-bumds">{{ $value->score }}
                                        <span class="info-box-text info-box-sdgs-text desc-bumds">Nilai</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@if (can('u'))
    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {
                const server_pantau = "{{ config_item('server_pantau') }}";
                const token_pantau = "{{ config_item('token_pantau') }}";
                const kode_desa = "{{ $kode_desa }}";
                const kode_desa_bps = "{{ $kode_desa_bps }}";

                $('#perbarui').click(function(event) {
                    event.preventDefault;
                    location.replace('{{ ci_route('status_desa.perbarui_sdgs') }}');
                });
            });
        </script>
    @endpush
@endif
