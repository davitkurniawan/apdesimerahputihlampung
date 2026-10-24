@extends('admin.layouts.index')

@section('title')
    <h1>Neural Widget Manager</h1>
@endsection

@section('content')
@php
    $widgets = \App\Models\Widget::orderBy('urut', 'asc')->get();
@endphp
<style>
    .widget-canvas {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 24px;
        padding: 30px;
        min-height: 500px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .dd-item {
        margin-bottom: 15px;
    }
    .dd-handle {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 16px;
        padding: 20px;
        color: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: grab;
        transition: all 0.3s;
    }
    .dd-handle:hover {
        background: rgba(99, 102, 241, 0.1);
        border-color: rgba(99, 102, 241, 0.3);
    }
    .widget-icon {
        width: 45px; height: 45px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(0,0,0,0.2);
    }
    .widget-system .widget-icon { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .widget-statis .widget-icon { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    
    .dd-dragel .dd-handle {
        background: rgba(15, 23, 42, 0.95);
        box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        border-color: #6366f1;
    }
</style>

<!-- HEADER STATS -->
<div class="row" style="margin-bottom: 30px;">
    <div class="col-md-4 col-sm-6">
        <div class="neural-card" style="padding: 25px; display: flex; align-items: center; min-height: 110px; border-top: 1px solid rgba(6, 182, 212, 0.2); background: rgba(15,23,42,0.6); border-radius: 20px; backdrop-filter: blur(10px);">
            <div class="sidebar-icon-box" style="width: 55px; height: 55px; background: rgba(6, 182, 212, 0.1); border-color: rgba(6, 182, 212, 0.3); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fa fa-cubes fa-lg" style="color: #06b6d4;"></i>
            </div>
            <div style="margin-left: 20px;">
                <div style="color: #94a3b8; font-size: 10px; text-transform: uppercase; letter-spacing: 2px; font-weight: 700; margin-bottom: 5px;">Total Terpasang</div>
                <div style="font-size: 26px; font-weight: 900; color: white; line-height: 1;">{{ $widgets->count() }} <span style="font-size: 13px; color: #64748b; font-weight: 500;">Blok</span></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="widget-canvas">
            <h2 style="font-family: 'Outfit'; color: white; font-weight: 800; margin-top: 0; font-size: 24px;">Sidebar Simulator</h2>
            <p style="color: #94a3b8; margin-bottom: 30px; font-size: 14px;">Susun ulang tata letak widget portal publik dengan menggeser blok di bawah ini ke atas atau ke bawah. Perubahan urutan akan otomatis tersimpan.</p>
            
            <div class="dd" id="widget-nestable">
                <ol class="dd-list">
                    @foreach($widgets as $widget)
                    <li class="dd-item" data-id="{{ $widget->id }}">
                        <div class="dd-handle {{ $widget->jenis_widget == 1 ? 'widget-system' : 'widget-statis' }}">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div class="widget-icon">
                                    <i class="fa {{ $widget->jenis_widget == 1 ? 'fa-microchip' : 'fa-file-code-o' }} fa-lg"></i>
                                </div>
                                <div>
                                    <h4 style="margin: 0; font-weight: 700; font-size: 16px;">{{ $widget->judul }}</h4>
                                    <span style="font-size: 10px; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">
                                        {{ $widget->jenis_widget == 1 ? 'Sistem Cloud Terintegrasi' : 'Widget Statis / Kustom' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div style="display: flex; gap: 8px;">
                                <!-- Toggle Aktif -->
                                <button onclick="window.location.href='{{ ci_route('web_widget.lock', $widget->id) }}'" class="btn btn-sm" style="background: {{ $widget->enabled == 1 ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' }}; color: {{ $widget->enabled == 1 ? '#10b981' : '#ef4444' }}; border: none; border-radius: 8px; width: 32px; height: 32px;" title="{{ $widget->enabled == 1 ? 'Sembunyikan' : 'Tampilkan' }}">
                                    <i class="fa {{ $widget->enabled == 1 ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                </button>
                                
                                <!-- Edit Admin (Hanya Sistem) -->
                                @if($widget->form_admin)
                                <button onclick="window.location.href='{{ ci_route($widget->form_admin) }}'" class="btn btn-sm" style="background: rgba(56, 189, 248, 0.1); color: #38bdf8; border: none; border-radius: 8px; width: 32px; height: 32px;" title="Pengaturan Lanjutan">
                                    <i class="fa fa-sliders"></i>
                                </button>
                                @endif
                                
                                <!-- Edit Form (Hanya Statis) -->
                                @if($widget->jenis_widget != 1)
                                <button onclick="window.location.href='{{ site_url('web_widget/form/'.$widget->id) }}'" class="btn btn-sm" style="background: rgba(255, 255, 255, 0.05); color: #94a3b8; border: none; border-radius: 8px; width: 32px; height: 32px;" title="Edit Konten">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button onclick="if(confirm('Hancurkan Widget Statis ini secara permanen?')) window.location.href='{{ ci_route('web_widget.delete', $widget->id) }}'" class="btn btn-sm" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: none; border-radius: 8px; width: 32px; height: 32px;" title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- PABRIK WIDGET -->
        <div class="neural-card" style="padding: 30px; background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(6, 182, 212, 0.1)); border: 1px solid rgba(99, 102, 241, 0.2); border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #6366f1, #06b6d4); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);">
                <i class="fa fa-puzzle-piece fa-2x" style="color: white;"></i>
            </div>
            <h3 style="color: white; font-family: 'Outfit'; font-weight: 800; margin-top: 0; font-size: 22px;">Pabrik Widget</h3>
            <p style="color: #94a3b8; font-size: 13px; line-height: 1.6; margin-bottom: 25px;">
                Tambahkan komponen kustom HTML, Teks, Banner, atau Gambar ke sidebar Anda dengan mudah. Widget statis juga mendukung penanaman kode pihak ketiga (seperti widget cuaca, live chat, dsb).
            </p>
            <a href="{{ site_url('web_widget/form') }}" class="btn btn-block" style="background: white; color: #0f172a; font-weight: 800; padding: 15px; border-radius: 12px; border: none; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; box-shadow: 0 10px 20px rgba(255,255,255,0.1);">
                <i class="fa fa-plus-circle" style="margin-right: 8px;"></i> Ciptakan Widget Baru
            </a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>
<script>
    $(document).ready(function() {
        // Mencegah klik tombol tertahan aksi drag
        $('.dd-handle button').on('mousedown click', function(e) {
            e.stopPropagation();
        });

        $('#widget-nestable').nestable({
            maxDepth: 1 // Widget flat layout (tidak bersarang)
        }).on('change', function() {
            var data = $(this).nestable('serialize');
            var orderedIds = [];
            
            // Format array menjadi ID datar
            for(var i=0; i<data.length; i++) {
                orderedIds.push(data[i].id);
            }
            
            // Tembakkan ke Neural Engine untuk re-ordering
            $.ajax({
                url: "{{ ci_route('web_widget.tukar') }}",
                type: "POST",
                data: {
                    data: orderedIds,
                    "{{ $this->security->get_csrf_token_name() }}": "{{ $this->security->get_csrf_hash() }}"
                },
                success: function(res) {
                    console.log('Sinkronisasi urutan berhasil.');
                }
            });
        });
    });
</script>
@endpush
