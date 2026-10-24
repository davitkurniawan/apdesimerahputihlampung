@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Manajemen Pengguna
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Manajemen Pengguna</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    @include('admin.layouts.components.konfirmasi_hapus')
    <div class="row">
        <div class="col-md-3">
            @include('admin.pengaturan.pengguna.menu')
        </div>
        <div class="col-md-9">
            <div class="neural-card">
                <div class="box-header with-border" style="border-bottom: 1px solid rgba(255,255,255,0.05) !important; padding: 25px !important; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                    <div class="peak-title-group">
                        <h1 style="font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 800; color: white; margin: 0;">Access Control Intelligence</h1>
                        <p style="color: #94a3b8; font-size: 13px; margin: 0;">Pusat kendali hak akses dan otoritas keamanan sistem XOSID</p>
                    </div>
                    
                    <div style="margin-left: auto; display: flex; gap: 10px;">
                        @if (can('u'))
                            <a href="{{ ci_route('man_user/form') }}" class="btn-xosid btn-xosid-primary">
                                <i data-lucide="user-plus" style="width: 16px;"></i> TAMBAH PENGGUNA
                            </a>
                        @endif
                        @if (can('h'))
                            <button type="button" onclick="deleteAllBox('mainform','{{ ci_route('man_user/delete_all') }}')" class="btn-xosid btn-xosid-danger hapus-terpilih">
                                <i data-lucide="trash-2" style="width: 16px;"></i>
                            </button>
                        @endif
                    </div>
                </div>

                <div class="box-body" style="padding: 25px !important;">
                    <div class="row" style="margin-bottom: 25px; background: rgba(255,255,255,0.02); padding: 20px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.05); display: flex; gap: 15px; align-items: flex-end;">
                        <div style="flex: 1;">
                            <div class="form-group neural-vertical-group" style="margin-bottom: 0;">
                                <label>STATUS AKUN</label>
                                <select id="status" class="form-control select2" name="status">
                                    <option value="">Semua Status</option>
                                    @foreach ($status as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <div class="form-group neural-vertical-group" style="margin-bottom: 0;">
                                <label>GRUP PENGGUNA</label>
                                <select id="group" class="form-control select2" name="group">
                                    <option value="">Semua Grup</option>
                                    @foreach ($user_group as $id => $item)
                                        <option value="{{ $id }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {!! form_open(null, 'id="mainform" name="mainform"') !!}
                    <div class="table-responsive">
                        <table class="table table-bordered neural-table" id="tabeldata">
                            <thead>
                                <tr style="background: rgba(6, 182, 212, 0.1);">
                                    <th style="width: 30px;"><input type="checkbox" id="checkall" /></th>
                                    <th style="width: 40px; color: #06b6d4;">NO</th>
                                    <th style="width: 100px;">AKSI</th>
                                    <th style="color: white;">USERNAME</th>
                                    <th style="color: white;">NAMA LENGKAP</th>
                                    <th style="width: 80px;">STAF</th>
                                    <th style="color: #06b6d4;">GRUP / ROLE</th>
                                    <th style="width: 150px; color: #94a3b8; font-size: 11px;">LOGIN TERAKHIR</th>
                                    <th style="width: 150px; color: #94a3b8; font-size: 11px;">VERIFIKASI</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            if (typeof lucide !== 'undefined') lucide.createIcons();
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('man_user') }}",
                    data: function(req) {
                        req.status = $('#status').val();
                        req.group = $('#group').val();
                    }
                },
                columns: [{
                        data: 'ceklist',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'aksi',
                        class: 'aksi',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'username',
                        name: 'username',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'pamong_status',
                        name: 'pamong_status',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'user_grup.nama',
                        name: 'userGrup.nama',
                        class: 'padat',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'last_login',
                        name: 'last_login',
                        class: 'padat',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'email_verified_at',
                        name: 'email_verified_at',
                        class: 'padat',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [3, 'asc']
                ],
                pageLength: 25,
                createdRow: function(row, data, dataIndex) {
                    if (data.jenis == 0 || data.jenis == 1) {
                        $(row).addClass('select-row');
                    }
                }
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
                TableData.column(7).visible(false);
            }

            $('#status').select2().val(1).trigger('change');

            $('#status').on('select2:select', function(e) {
                TableData.draw();
            });
            $('#group').on('select2:select', function(e) {
                TableData.draw();
            });
        });
    </script>
@endpush
