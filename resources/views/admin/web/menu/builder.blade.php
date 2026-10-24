<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neural Page Builder - {{ $page['judul'] }}</title>
    <!-- Tailwind CSS untuk Layout Independen -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- Quill.js Editor Core -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #f1f5f9; font-family: 'Inter', system-ui, -apple-system, sans-serif; margin: 0; padding: 0; overflow-x: hidden; }
        
        /* Glassmorphism Header */
        .glass-header { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(16px); border-bottom: 1px solid rgba(255,255,255,0.05); }
        
        /* Override Tema Terang Quill ke Mode Gelap Canggih */
        .ql-toolbar.ql-snow { border: none !important; border-bottom: 1px solid rgba(255,255,255,0.05) !important; padding: 20px !important; background: rgba(15, 23, 42, 0.3); border-radius: 24px 24px 0 0; }
        .ql-container.ql-snow { border: none !important; font-size: 17px; font-family: 'Inter', system-ui, sans-serif; line-height: 1.8; }
        .ql-editor { padding: 50px 80px; min-height: calc(100vh - 160px); color: #e2e8f0; }
        
        /* Ikon & Tooltip Mode Gelap */
        .ql-stroke { stroke: #cbd5e1 !important; }
        .ql-fill { fill: #cbd5e1 !important; }
        .ql-picker-label { color: #cbd5e1 !important; }
        .ql-picker-label:hover { color: #38bdf8 !important; }
        .ql-picker-label:hover .ql-stroke { stroke: #38bdf8 !important; }
        .ql-snow .ql-picker-options { background-color: #1e293b; border-color: rgba(255,255,255,0.1); color: #f1f5f9; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); overflow: hidden; }
        .ql-snow .ql-tooltip { background-color: #1e293b; border-color: rgba(255,255,255,0.1); color: #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.6); border-radius: 12px; padding: 15px; }
        .ql-snow .ql-tooltip input[type=text] { background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 8px; padding: 8px 12px; }
        .ql-snow .ql-tooltip a.ql-action::before { color: #38bdf8; font-weight: bold; }
        
        /* Format Teks Khusus di Editor */
        .ql-editor h1 { font-size: 2.5em; font-weight: 800; color: #f8fafc; margin-bottom: 20px; letter-spacing: -1px; }
        .ql-editor h2 { font-size: 2em; font-weight: 700; color: #f1f5f9; margin-top: 30px; margin-bottom: 15px; }
        .ql-editor p { margin-bottom: 20px; }
        .ql-editor a { color: #38bdf8; text-decoration: none; border-bottom: 1px dashed #38bdf8; }
        .ql-editor blockquote { border-left: 4px solid #38bdf8; background: rgba(56, 189, 248, 0.1); padding: 20px; border-radius: 0 12px 12px 0; font-style: italic; color: #94a3b8; }
        .ql-editor img { border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); margin: 20px 0; }
        
        /* Animasi Latar Belakang Lingkaran Cahaya */
        .ambient-light { position: fixed; border-radius: 50%; filter: blur(100px); opacity: 0.15; z-index: -1; pointer-events: none; }
        .light-1 { top: -100px; right: -100px; width: 500px; height: 500px; background: #38bdf8; }
        .light-2 { bottom: -100px; left: -100px; width: 600px; height: 600px; background: #818cf8; }
    </style>
</head>
<body>

<!-- Cahaya Latar Estetik -->
<div class="ambient-light light-1"></div>
<div class="ambient-light light-2"></div>

<!-- Top Navigation Bar -->
<div class="glass-header fixed top-0 w-full z-50 flex justify-between items-center px-8 py-4">
    <div class="flex items-center gap-5">
        <a href="{{ site_url('menu') }}" class="w-11 h-11 rounded-2xl bg-slate-800/80 hover:bg-slate-700 flex items-center justify-center text-slate-400 hover:text-white transition-all duration-300 border border-white/5 hover:border-white/10 shadow-lg">
            <i class="fa fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-3 tracking-tight">
                <i class="fa fa-paint-brush text-emerald-400"></i> Neural Page Builder
            </h1>
            <p class="text-xs text-slate-400 mt-1 font-medium">Sedang Merakit: <span class="text-sky-400 bg-sky-400/10 px-2 py-0.5 rounded-full ml-1">{{ $page['judul'] }}</span></p>
        </div>
    </div>
    
    <div class="flex items-center gap-4">
        <!-- Status Indikator -->
        <div id="save-status" class="text-sm font-medium text-slate-400 flex items-center gap-2 opacity-0 transition-opacity duration-300">
            <i class="fa fa-check-circle text-emerald-400"></i> Tersimpan
        </div>
        
        <button id="btn-save" class="bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 transition-all duration-300 flex items-center gap-2 transform hover:-translate-y-0.5">
            <i class="fa fa-cloud-upload"></i> Publikasikan Konten
        </button>
    </div>
</div>

<!-- Ruang Kerja Editor -->
<div class="max-w-5xl mx-auto mt-28 mb-16 bg-slate-800/40 backdrop-blur-md rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/5 relative">
    <div id="editor">
        {!! $page['isi'] !!}
    </div>
</div>

<!-- Form Tersembunyi untuk Menembus Protokol Pos Standar -->
<form id="form-builder" action="{{ site_url('menu/save_page') }}" method="POST" style="display: none;">
    <input type="hidden" name="{{ $csrf_name }}" value="{{ $csrf_hash }}">
    <input type="hidden" name="id" value="{{ $page['id'] }}">
    <textarea name="isi" id="input-isi"></textarea>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    // Konfigurasi Tools Kuas Sihir
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Mulai ketik cerita, pengumuman, atau visi desa Anda di sini...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['blockquote', 'code-block'],
                ['link', 'image', 'video'],
                ['clean'] // Tombol hapus format
            ]
        }
    });

    // Mesin Penyimpan Sinkron (Anti-Gagal CSRF)
    $('#btn-save').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);
        
        // Animasi Loading
        btn.html('<i class="fa fa-spinner fa-spin"></i> Sedang Mempublikasikan...').prop('disabled', true);
        
        // Sedot isi HTML murni dari dalam kanvas
        var content = document.querySelector('.ql-editor').innerHTML;
        
        // Injeksi ke form dan tembak!
        $('#input-isi').val(content);
        $('#form-builder').submit();
    });
</script>

</body>
</html>
