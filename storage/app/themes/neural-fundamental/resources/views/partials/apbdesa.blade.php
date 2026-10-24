@php defined('BASEPATH') OR exit('No direct script access allowed'); @endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 my-12 w-full">
    @foreach ($data_widget as $subdata_name => $subdatas)
        <div class="shadow bg-slate-900/60 rounded-2xl overflow-hidden border border-white/5 shadow-2xl backdrop-blur-md transition-all duration-300 hover:border-cyan-500/30 hover:shadow-cyan-500/10 hover:shadow-lg">
            <h3 class="bg-gradient-to-r from-cyan-950/60 to-indigo-950/60 text-white px-5 py-4 text-sm font-bold uppercase tracking-wider border-b border-white/5 flex items-center gap-2">
                <i class="fas fa-chart-line text-cyan-400"></i>
                {{ \Illuminate\Support\Str::of($subdatas['laporan'])->when(setting('sebutan_desa') != 'desa', function (\Illuminate\Support\Stringable $string) {
                    return $string->replace('Des', \Illuminate\Support\Str::of(setting('sebutan_desa'))->substr(0, 1)->ucfirst());
                }) }}
            </h3>
            <div class="px-5 py-5 text-xs lg:text-sm space-y-4">
                @foreach ($subdatas as $key => $subdata)
                    @if (is_array($subdata) && $subdata['judul'] != null && $key != 'laporan' && ($subdata['realisasi'] != 0 || $subdata['anggaran'] != 0))
                        <div class="space-y-1.5">
                            <span class="text-xs font-bold text-slate-300">
                                {{ \Illuminate\Support\Str::of($subdata['judul'])->title()->whenEndsWith('Desa', function (\Illuminate\Support\Stringable $string) {
                                        if (!in_array($string, ['Dana Desa'])) {
                                            return $string->replace('Desa', setting('sebutan_desa'));
                                        }
                                    })->title() }}
                            </span>
                            <div class="text-xs flex justify-between text-slate-400 font-semibold">
                                <span>{{ rupiah24($subdata['realisasi']) }}</span>
                                <span class="text-slate-500">{{ rupiah24($subdata['anggaran']) }}</span>
                            </div>
                            <div class="w-full bg-slate-950/60 h-3.5 rounded-full overflow-hidden border border-white/5 p-[1px]">
                                <div class="bg-gradient-to-r from-cyan-500 to-purple-600 h-full text-[9px] font-black text-white text-center flex items-center justify-center rounded-full transition-all duration-500 shadow-[0_0_10px_rgba(6,182,212,0.5)]" style="width: {{ $subdata['persen'] }}%">
                                    {{ $subdata['persen'] }}%
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach
</div>
