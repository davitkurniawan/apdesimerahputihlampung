<script>
    // Select2 - Cari Nama Desa di API Wilayah XOSID Lokal

    $.fn.select2.defaults.set('language', {
        errorLoading: function() {
            return 'Hasil tidak dapat dimuat. <a href="' + SITE_URL + 'api/wilayah/caridesa" target="_blank">Lihat Url</a>';
        },
        loadingMore: function() {
            return "Memuat hasil lainnya…"
        },
        noResults: function() {
            return 'Tidak ada hasil yang ditemukan. <a href="' + SITE_URL + 'api/wilayah/caridesa" target="_blank">Lihat Url</a>';
        },
        searching: function() {
            return "Mencari…"
        }
    });

    $('.select-nama-desa').select2({
        ajax: {
            url: function() {
                return SITE_URL + 'api/wilayah/caridesa';
            },
            dataType: 'json',
            data: function(params) {
                return {
                    q: params.term || '',
                    page: params.page || 1,
                };
            },
            processResults: function(data) {
                return {
                    results: data.results,
                    pagination: data.pagination,
                }
            },
            cache: true
        },
        placeholder: '--  Cari Nama Desa --',
        minimumInputLength: 0,
        allowClear: true,
        escapeMarkup: function(markup) {
            return markup;
        },
        templateSelection: function(state) {
            if (!state.id) {
                return state.text;
            }
            // State text berformat: "Nama Desa - Kecamatan - Kabupaten - Provinsi"
            // Kita hanya ambil bagian Nama Desa saja (bagian sebelum tanda minus pertama)
            var parts = state.text.split(' - ');
            return parts[0];
        }
    });
</script>
