@extends('admin.layouts.index')

@php
if (!function_exists('render_neural_menus')) {
    function render_neural_menus($menus) {
        if (empty($menus)) return '';
        $html = '<ol class="dd-list" style="list-style: none; padding: 0;">';
        foreach ($menus as $menu) {
            $html .= '<li class="dd-item" data-id="' . $menu['id'] . '" style="margin-bottom: 15px; list-style: none;">';
            $html .= '<div style="background: rgba(51, 65, 85, 0.4); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 12px 20px; display: flex; align-items: center; transition: all 0.3s ease; position: relative; min-height: 60px;">';
            
            // DRAG HANDLE (ONLY THIS ICON IS DRAGGABLE)
            $html .= '<div class="dd-handle" style="cursor: move; padding: 10px; margin-right: 15px; color: #475569; background: transparent; border: none;">';
            $html .= '<i class="fa fa-bars" style="font-size: 18px;"></i>';
            $html .= '</div>';

            $html .= '<i class="fa fa-' . htmlspecialchars($menu['ikon'] ?: 'circle') . '" style="color: #38bdf8; margin-right: 15px; font-size: 18px;"></i>';
            $html .= '<div style="display: flex; flex-direction: column; flex-grow: 1;">';
            $html .= '<span style="color: #f1f5f9; font-weight: 600; font-size: 15px;">' . htmlspecialchars($menu['nama']) . '</span>';
            $html .= '<span style="color: #475569; font-size: 11px; font-family: monospace;">/' . htmlspecialchars($menu['link']) . '</span>';
            $html .= '</div>';
            
            $html .= '<div class="neural-actions" style="display: flex; gap: 10px; z-index: 100;">';
            
            // NEURAL PAGE BUILDER LINK
            if ($menu['tipe'] == 'page') {
                $slug = str_replace('page/', '', $menu['link']);
                $url = site_url('menu/builder/'.$slug);
                $html .= '<button onclick="window.location.href=\''.$url.'\'" class="btn-icon" style="background: linear-gradient(135deg, #10b981, #059669); border: none; color: white; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.4); cursor: pointer;" title="Buka Studio Kanvas"><i class="fa fa-paint-brush"></i></button>';
            }

            $html .= '<button class="btn-icon btn-edit-menu" type="button" data-id="'.$menu['id'].'" data-nama="'.htmlspecialchars($menu['nama']).'" data-link="'.htmlspecialchars($menu['link']).'" data-ikon="'.htmlspecialchars($menu['ikon']).'" style="background: rgba(255,255,255,0.05); border: none; color: #94a3b8; width: 32px; height: 32px; border-radius: 8px; cursor: pointer;"><i class="fa fa-pencil"></i></button>';
            $html .= '<button class="btn-icon btn-delete-menu" type="button" data-id="'.$menu['id'].'" style="background: rgba(239, 68, 68, 0.1); border: none; color: #ef4444; width: 32px; height: 32px; border-radius: 8px; cursor: pointer;"><i class="fa fa-trash"></i></button>';
            $html .= '</div>';
            $html .= '</div>';
            
            if (!empty($menu['children'])) {
                $html .= render_neural_menus($menu['children']);
            }
            $html .= '</li>';
        }
        $html .= '</ol>';
        return $html;
    }
}
@endphp

@section('content')
<div style="background: radial-gradient(circle at top right, #1e293b, #0f172a); min-height: calc(100vh - 100px); padding: 35px; border-radius: 24px; box-shadow: 0 15px 50px rgba(0,0,0,0.5); margin: 20px; border: 1px solid rgba(255,255,255,0.05);">
    <!-- HEADER SECTION -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 25px;">
        <div>
            <h1 style="color: #f8fafc; font-size: 32px; font-weight: 800; margin: 0; letter-spacing: -0.5px; display: flex; align-items: center;">
                <div style="background: rgba(56, 189, 248, 0.1); padding: 12px; border-radius: 15px; margin-right: 15px; border: 1px solid rgba(56, 189, 248, 0.2);">
                    <i class="fa fa-braille" style="color: #38bdf8; font-size: 24px;"></i>
                </div>
                {{ $header }}
            </h1>
            <p style="color: #94a3b8; margin-top: 10px; font-size: 15px; margin-bottom: 0;">{{ $description }}</p>
        </div>
        <div>
            <button id="btn-buka-modal" class="btn" style="background: linear-gradient(135deg, #38bdf8, #2563eb); color: white; border: none; padding: 14px 28px; border-radius: 14px; font-weight: 700; font-size: 15px; box-shadow: 0 10px 30px rgba(37, 99, 235, 0.4); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); letter-spacing: 0.5px;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 35px rgba(37, 99, 235, 0.6)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(37, 99, 235, 0.4)'">
                <i class="fa fa-plus" style="margin-right: 8px;"></i> Tambah Menu Baru
            </button>
        </div>
    </div>

    <div class="row">
        <!-- VISUAL BUILDER AREA -->
        <div class="col-lg-8 col-md-7" style="margin-bottom: 30px;">
            <div style="background: rgba(30, 41, 59, 0.5); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.05); border-radius: 24px; padding: 35px; box-shadow: inset 0 2px 10px rgba(255,255,255,0.02), 0 20px 50px rgba(0,0,0,0.3); min-height: 400px;">
                <div id="neural-tree-container">
                    @if(empty($menus))
                        <div style="text-align: center; padding: 60px 20px;">
                            <div style="font-size: 48px; color: #334155; margin-bottom: 20px;">
                                <i class="fa fa-folder-open-o"></i>
                            </div>
                            <h3 style="color: #f8fafc; font-weight: 700;">Belum Ada Menu</h3>
                            <p style="color: #64748b;">Klik tombol 'Tambah Menu Baru' untuk memulai arsitektur navigasi Anda.</p>
                        </div>
                    @else
                        <!-- LIST MENU START -->
                        <div class="dd" id="nestable">
                            {!! render_neural_menus($menus) !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- PREVIEW & TIPS -->
        <div class="col-lg-4 col-md-5">
            <div style="background: linear-gradient(135deg, rgba(56, 189, 248, 0.15), rgba(37, 99, 235, 0.15)); border: 1px solid rgba(56, 189, 248, 0.3); border-radius: 24px; padding: 30px; margin-bottom: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.2);">
                <h4 style="color: #38bdf8; font-weight: 700; margin-bottom: 15px;">🚀 Neural Tips</h4>
                <p style="color: #94a3b8; font-size: 13px; line-height: 1.6;">
                    Geser menu ke arah <strong>kanan</strong> untuk menjadikannya Submenu. Anda dapat mengatur kedalaman navigasi tanpa batas. 
                    <br><br>
                    Perubahan disimpan secara otomatis (Auto-Save) untuk menjaga alur kerja Anda tetap lancar.
                </p>
            </div>

            <div style="background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(255,255,255,0.05); border-radius: 24px; padding: 25px;">
                <h4 style="color: #f8fafc; font-weight: 700; margin-bottom: 20px;">Status Sistem</h4>
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                    <span style="color: #94a3b8;">Legacy Menu</span>
                    <span class="label label-warning" style="border-radius: 20px;">Aktif (Backup)</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #94a3b8;">Neural Engine</span>
                    <span class="label label-success" style="border-radius: 20px; background: #10b981 !important;">Ready</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .dd-handle:hover {
        background: rgba(51, 65, 85, 0.6) !important;
        border-color: #38bdf8 !important;
        transform: translateX(5px);
    }
    .btn-icon:hover {
        background: rgba(255,255,255,0.1) !important;
        color: #f1f5f9 !important;
    }
</style>

<!-- NEURAL MODAL: TAMBAH KOMPONEN -->
<div class="modal fade" id="modal-tambah-neural" tabindex="-1" role="dialog" style="z-index: 99999;">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px;">
        <div class="modal-content" style="background: #1e293b; border: 1px solid rgba(255,255,255,0.1); border-radius: 24px; box-shadow: 0 25px 50px rgba(0,0,0,0.5);">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.05); padding: 25px;">
                <h4 class="modal-title" style="color: #f8fafc; font-weight: 800; font-size: 20px;"><i class="fa fa-cube" style="color: #38bdf8; margin-right: 10px;"></i> Buat Komponen Baru</h4>
            </div>
            <div class="modal-body" style="padding: 25px;">
                {!! form_open(null, 'id="form-neural-tambah"') !!}
                    <input type="hidden" name="neural_action" value="add_menu">
                    
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="color: #94a3b8; font-size: 12px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">Jenis Komponen</label>
                        <select name="tipe" id="tipe-komponen" class="form-control" style="background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255,255,255,0.1); color: #f1f5f9; border-radius: 12px; padding: 12px; height: auto;">
                            <option value="custom">Navigasi Biasa (Menu / Tautan Bebas)</option>
                            <option value="page">Halaman Baru (Page Builder) 📄</option>
                            <option value="section">Label Grup (Tanpa Tautan) 🏷️</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="color: #94a3b8; font-size: 12px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">Nama / Judul</label>
                        <input type="text" name="nama" class="form-control" required placeholder="Misal: Profil Desa" style="background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255,255,255,0.1); color: #f1f5f9; border-radius: 12px; padding: 12px; height: auto;">
                    </div>

                    <div class="form-group input-link-grup" style="margin-bottom: 20px;">
                        <label style="color: #94a3b8; font-size: 12px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">Tautan (Link)</label>
                        <input type="text" name="link" class="form-control" placeholder="URL Lengkap (Contoh: https://google.com)" style="background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255,255,255,0.1); color: #f1f5f9; border-radius: 12px; padding: 12px; height: auto;">
                        <small style="color: #64748b; margin-top: 5px; display: block;">*Bisa diisi link eksternal atau link internal, biarkan kosong untuk menu induk biasa.</small>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="color: #94a3b8; font-size: 12px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">Nama Ikon (Opsional)</label>
                        <input type="text" name="ikon" class="form-control" placeholder="Misal: home, user, file" style="background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255,255,255,0.1); color: #f1f5f9; border-radius: 12px; padding: 12px; height: auto;">
                    </div>
                {!! form_close() !!}
            </div>
            <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.05); padding: 20px 25px;">
                <button type="button" class="btn" data-dismiss="modal" style="background: transparent; color: #94a3b8; font-weight: 600; border: none;">Batal</button>
                <button type="button" id="btn-simpan-neural" class="btn" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; padding: 10px 25px; border-radius: 10px; font-weight: 700; box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);">Simpan Komponen</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>
<style>
    .nestable-drag-layer { z-index: 99999 !important; }
</style>
<script>
    $(document).ready(function() {
        $('#modal-tambah-neural').appendTo("body");

        // Pemanggil Handal Modal (Tambah)
        $('#btn-buka-modal').on('click', function() {
            $('#edit-id-input').remove();
            $('#form-neural-tambah')[0].reset();
            $('#tipe-komponen').val('custom').trigger('change');
            $('#form-neural-tambah').find('input[name="neural_action"]').val('add_menu');
            $('#modal-tambah-neural .modal-title').html('<i class="fa fa-cube" style="color: #38bdf8; margin-right: 10px;"></i> Buat Komponen Baru');
            $('#modal-tambah-neural').modal('show');
        });

        // Efek Cerdas Dropdown Tipe
        $('#tipe-komponen').on('change', function() {
            if($(this).val() == 'custom') {
                $('.input-link-grup').slideDown();
            } else {
                $('.input-link-grup').slideUp();
                $('input[name="link"]').val('');
            }
        });

        // Buka Modal untuk Edit (Gunakan delegasi document agar lebih kuat)
        $(document).on('click', '.btn-edit-menu', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).data('id');
            $('#edit-id-input').remove();
            $('#form-neural-tambah').append('<input type="hidden" name="id" id="edit-id-input" value="'+id+'">');
            $('#form-neural-tambah').find('input[name="neural_action"]').val('edit_menu');
            $('#form-neural-tambah').find('input[name="nama"]').val($(this).data('nama'));
            $('#form-neural-tambah').find('input[name="link"]').val($(this).data('link'));
            $('#form-neural-tambah').find('input[name="ikon"]').val($(this).data('ikon'));
            $('#modal-tambah-neural .modal-title').html('<i class="fa fa-pencil" style="color: #38bdf8; margin-right: 10px;"></i> Edit Komponen');
            $('#modal-tambah-neural').modal('show');
        });

        // Hapus Komponen (Gunakan SweetAlert2 agar lebih stabil dan Neural)
        $(document).on('click', '.btn-delete-menu', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            var btn = $(this);
            var menuId = btn.data('id');
            var menuName = btn.closest('.dd-item').find('span:first').text();

            Swal.fire({
                title: 'Hancurkan Komponen?',
                text: "Menu '" + menuName + "' dan semua sub-menu di dalamnya akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: 'rgba(255,255,255,0.1)',
                confirmButtonText: 'YA, HAPUS!',
                cancelButtonText: 'BATAL',
                background: '#1e293b',
                color: '#f1f5f9',
                backdrop: 'rgba(15, 23, 42, 0.8)'
            }).then((result) => {
                if (result.isConfirmed) {
                    var li = btn.closest('.dd-item');
                    li.css('opacity', '0.5');
                    
                    $.ajax({
                        url: "{{ site_url('menu') }}",
                        type: "GET",
                        data: { 
                            neural_action: 'delete_menu', 
                            id: menuId
                        },
                        success: function(res) {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: 'Komponen telah berhasil dimusnahkan.',
                                icon: 'success',
                                background: '#1e293b',
                                color: '#f1f5f9',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            li.slideUp(300, function(){ $(this).remove(); });
                        },
                        error: function(xhr) {
                            var msg = 'Gagal menghubungi Neural Engine.';
                            if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                            else if(xhr.status == 403) msg = 'Sesi keamanan kadaluarsa atau akses ditolak.';
                            else if(xhr.status == 404) msg = 'Jalur (Route) tidak ditemukan.';
                            else msg += ' (Status: ' + xhr.status + ')';
                            
                            Swal.fire({
                                title: 'Kegagalan Sistem',
                                text: msg,
                                icon: 'error',
                                background: '#1e293b',
                                color: '#f1f5f9'
                            });
                            li.css('opacity', '1');
                        }
                    });
                }
            });
        });

        // Simpan Data
        $('#btn-simpan-neural').on('click', function() {
            var btn = $(this);
            var form = $('#form-neural-tambah');
            if(!form.find('input[name="nama"]').val()) { alert('Nama tidak boleh kosong!'); return; }
            
            var originalText = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);
            
            $.ajax({
                url: "{{ site_url('menu') }}",
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    $('#modal-tambah-neural').modal('hide');
                    $('body').css('opacity', '0.5');
                    setTimeout(function() { window.location.reload(); }, 300);
                },
                error: function() {
                    alert('Gagal menghubungi Neural Engine. Periksa koneksi atau sesi login Anda.');
                    btn.html(originalText).prop('disabled', false);
                }
            });
        });

        // Inisialisasi Mesin Sihir Nestable (Drag & Drop)
        $('#nestable').nestable({
            maxDepth: 5,
            collapsedClass: 'dd-collapsed'
        }).on('change', function() {
            var data = window.JSON.stringify($('#nestable').nestable('serialize'));
            
            // Toast Notification
            $('.neural-toast').remove();
            var toast = $('<div class="neural-toast" style="position:fixed; bottom:30px; right:30px; background:rgba(15, 23, 42, 0.9); backdrop-filter:blur(10px); border:1px solid rgba(56, 189, 248, 0.3); color:#f8fafc; padding:15px 25px; border-radius:15px; z-index:999999; box-shadow:0 15px 30px rgba(0,0,0,0.5); font-weight:700; display:flex; align-items:center; gap:10px;"><i class="fa fa-spinner fa-spin text-info"></i> Auto-Saving...</div>');
            $('body').append(toast);
            
            $.ajax({
                url: "{{ site_url('menu') }}",
                type: "POST",
                data: { 
                    neural_action: 'update_order', 
                    menus: data,
                    [csrfParam]: csrfVal
                },
                success: function(res) {
                    toast.html('<i class="fa fa-check-circle" style="color:#10b981; font-size:20px;"></i> <span style="color:#10b981;">Struktur Tersimpan</span>').delay(2000).fadeOut(function(){ $(this).remove(); });
                }
            });
        });
        
        // Mencegah terdrag saat mengklik tombol di dalam handle
        $('.dd-handle .btn-icon').on('mousedown click', function(e) {
            e.stopPropagation();
        });
    });
</script>
@endpush
@endsection
