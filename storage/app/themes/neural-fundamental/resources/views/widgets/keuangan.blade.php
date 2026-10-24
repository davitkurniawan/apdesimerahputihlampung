@if (!empty($widget_keuangan['tahun']) && !is_null($widget_keuangan['tahun']))
    @include('theme::commons.asset_highcharts')
    <!-- widget Statistik Keuangan Neural -->
    <style type="text/css">
        .graph,
        .graph-sub {
            padding: 0 12px;
            padding-top: 4px;
        }

        .graph-sub {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            color: #cbd5e1;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
            padding-top: 12px;
            padding-bottom: 2px;
        }

        .graph {
            padding-top: 4px;
        }

        .graph-not-available {
            text-align: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 11px;
            color: #64748b;
            padding: 10px 0;
        }

        #graph-legend {
            padding: 0;
            padding-bottom: 12px;
        }

        .highcharts-container,
        svg:not(:root) {
            overflow: visible !important;
            position: absolute;
        }

        .highcharts-tooltip>span {
            background: rgba(15, 23, 42, 0.95) !important;
            border: 1px solid rgba(6, 182, 212, 0.4) !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5) !important;
            padding: 8px 12px !important;
            color: #ffffff !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: 11px !important;
        }
    </style>
    
    <div class="box box-primary box-solid">
        <div class="box-header">
            <h3 class="box-title">
                <i class="fa fa-chart-bar mr-1"></i>{{ $judul_widget }}
            </h3>
        </div>
        <div class="box-body">
            <div id="widget-keuangan-container">
                <div class="text-center relative">
                    <!-- Dropdown Selector Glassmorphism -->
                    <div class="flex justify-center mb-4 z-50">
                        <div x-data="{
                            open: false,
                            toggle() {
                                if (this.open) {
                                    return this.close()
                                }
                        
                                this.open = true
                            },
                            close(focusAfter) {
                                this.open = false
                        
                                focusAfter && focusAfter.focus()
                            }
                        }" x-on:keydown.escape.prevent.stop="close($refs.button)" x-on:focusin.window="! $refs.panel.contains($event.target) && close()" x-id="['dropdown-button']" class="relative inline-block">
                            <!-- Button -->
                            <button x-ref="button" x-on:click="toggle()" :aria-expanded="open" :aria-controls="$id('dropdown-button')" type="button" class="btn-cyber-cyan py-1 px-3 text-[10px]">
                                <span><i class="fas fa-bars mr-1"></i>Menu APBDes</span>
                            </button>

                            <!-- Panel Cyber -->
                            <div
                                x-ref="panel"
                                x-show="open"
                                x-transition.origin.top
                                x-on:click.outside="close($refs.button)"
                                :id="$id('dropdown-button')"
                                style="display: none;"
                                class="absolute left-1/2 -translate-x-1/2 mt-2 bg-[#070a13] border border-cyan-500/30 shadow-[0_0_15px_rgba(6,182,212,0.3)] rounded-xl overflow-hidden z-[9999] min-w-[200px]"
                            >
                                <ul class="divide-y divide-slate-800 text-center text-xs font-semibold">
                                    @foreach ($widget_keuangan['tahun'] as $key)
                                        <li class="bg-slate-900 px-3 py-2 text-[10px] text-cyan-400 uppercase tracking-wider font-bold border-b border-cyan-500/20">Tahun {{ $key }}</li>
                                        <li><a href="#!" class="py-2.5 px-4 block text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-300 transition" @click="open = false" onclick="gantiTipe('pelaksanaan'); gantiTahun('{{ $key }}')">Pelaksanaan APBDes</a></li>
                                        <li><a href="#!" class="py-2.5 px-4 block text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-300 transition" @click="open = false" onclick="gantiTipe('pendapatan'); gantiTahun('{{ $key }}')">Pendapatan APBDes</a></li>
                                        <li><a href="#!" class="py-2.5 px-4 block text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-300 transition" @click="open = false" onclick="gantiTipe('belanja'); gantiTahun('{{ $key }}')">Belanja APBDes</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="text-base font-bold text-white tracking-tight pt-2 bg-gradient-to-r from-cyan-400 to-indigo-300 bg-clip-text text-transparent inline-block">Pelaksanaan APBDes</h3>
                    <p id="grafik-tahun" class="text-xs text-slate-500 font-semibold"></p>
                </div>
                
                <div id="grafik-container" class="mt-4 space-y-4">
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var rawData = {!! $widget_keuangan['data'] !!};
        var year = "{{ $widget_keuangan['tahun_terbaru'] }}";
        let tipe = "pelaksanaan"

        function displayChart(tahun, tipe) {
            resetContainer();
            switch (tipe) {
                case "pelaksanaan":
                    var judulGrafik = 'Pelaksanaan APBDes';
                    var tipeGrafik = 'res_pelaksanaan';
                    break;

                case "belanja":
                    var judulGrafik = 'Belanja APBDes';
                    var tipeGrafik = 'res_belanja';
                    break;

                case "pendapatan":
                    var judulGrafik = 'Pendapatan APBDes';
                    var tipeGrafik = 'res_pendapatan';
                    break;
            }
            var chartData = rawData[tahun][tipeGrafik];
            $("#widget-keuangan-container h3").text(judulGrafik);
            $("#grafik-container").append("<div id='graph-legend' class='graph'></div>");
            Highcharts.chart("graph-legend", {
                chart: {
                    type: 'bar',
                    margin: 0,
                    backgroundColor: "rgba(0,0,0,0)",
                    spacing: [0, 0, 0, 0],
                    height: 20
                },

                title: {
                    text: ''
                },

                subtitle: {
                    text: '',
                },

                xAxis: {
                    visible: false,
                    categories: [''],
                },

                tooltip: {
                    enabled: false
                },

                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true
                        },
                    },

                    series: {
                        pointPadding: 0,
                        groupPadding: 0,
                        dataLabels: {
                            align: 'right',
                            inside: true,
                            shadow: false,
                            color: '#ffffff',
                            style: { textOutline: 'none', font: '9px Outfit' }
                        },
                        grouping: false,
                    },
                },

                credits: {
                    enabled: false
                },

                yAxis: {
                    visible: false
                },

                exporting: {
                    enabled: false
                },

                legend: {
                    padding: 0,
                    margin: 0,
                    verticalAlign: 'middle',
                    maxHeight: 50,
                    itemStyle: {
                        color: '#94a3b8',
                        fontFamily: 'Plus Jakarta Sans',
                        fontSize: '10px',
                        fontWeight: '600'
                    }
                },

                series: [{
                        name: 'Anggaran',
                        color: '#06b6d4',
                        data: [],
                    },
                    {
                        name: 'Realisasi',
                        color: '#a855f7',
                        data: [],
                    }
                ]
            });
            
            //Eksekusi chart dengan for loop
            if (chartData) {
                chartData.forEach(function(subData, idx) {
                    if (subData['nama']) {
                        if ((!subData['realisasi'] && !subData['anggaran'])) {
                            $("#grafik-container").append(
                                "<div class='graph-sub' id='graph-sub-" + idx + "'><i class='fas fa-receipt mr-2'></i>" + subData['nama'] + "</div><div id='graph-" +
                                idx + "' class='graph-not-available'>Data tidak tersedia.</div>");
                        } else {
                            var anggaran = parseInt(subData['anggaran']) || 0;
                            var realisasi = parseInt(subData['realisasi']) || 0;
                            
                            var persentaseReal = (anggaran > 0) ? Math.round(realisasi / anggaran * 100) : 0;
                            
                            var formattedRealisasi = "Rp. " + Highcharts.numberFormat(realisasi, 0, ',', '.');
                            var formattedAnggaran = "Rp. " + Highcharts.numberFormat(anggaran, 0, ',', '.');
                            
                            var maxVal = Math.max(anggaran, realisasi);
                            if (maxVal <= 0) { maxVal = 1; }

                            $("#grafik-container").append(
                                "<div class='mb-4 px-3'>" +
                                "  <div class='flex justify-between items-center mb-1.5 flex-wrap gap-y-1'>" +
                                "    <span class='text-[11px] font-bold text-slate-200 tracking-wide uppercase flex items-center'><i class='fas fa-receipt mr-2 text-cyan-400'></i>" + subData['nama'] + "</span>" +
                                "    <span class='text-[10px] font-semibold text-slate-400'>" +
                                "      <span class='text-cyan-400 font-bold'>" + formattedRealisasi + "</span> <span class='text-slate-600'>/</span> <span class='text-slate-300'>" + formattedAnggaran + "</span>" +
                                "      <span class='ml-1.5 px-1.5 py-0.5 rounded-full bg-purple-500/10 text-purple-300 border border-purple-500/20 font-bold'>" + persentaseReal + "%</span>" +
                                "    </span>" +
                                "  </div>" +
                                "  <div id='graph-" + idx + "' class='graph' style='position: relative; height: 14px; padding: 0;'></div>" +
                                "</div>"
                            );
                            
                            Highcharts.chart("graph-" + idx, {
                                chart: {
                                    type: 'bar',
                                    margin: [0, 0, 0, 0],
                                    spacing: [0, 0, 0, 0],
                                    height: 14,
                                    backgroundColor: "rgba(0,0,0,0)",
                                },

                                title: {
                                    text: ''
                                },

                                subtitle: {
                                    text: '',
                                },

                                xAxis: {
                                    visible: false,
                                    categories: [''],
                                },

                                tooltip: {
                                    useHTML: true,
                                    borderWidth: 0,
                                    shadow: false,
                                    backgroundColor: "rgba(0,0,0,0)",
                                    valueSuffix: '',
                                    hideDelay: 0,
                                    outside: true,
                                },

                                plotOptions: {
                                    bar: {
                                        pointWidth: 8,
                                        borderWidth: 1,
                                    },

                                    series: {
                                        pointPadding: 0,
                                        groupPadding: 0,
                                        borderRadius: 4,
                                        grouping: false,
                                        dataLabels: {
                                            enabled: false
                                        },
                                    },
                                },

                                credits: {
                                    enabled: false
                                },

                                yAxis: {
                                    visible: false,
                                    min: 0,
                                    max: maxVal,
                                },

                                exporting: {
                                    enabled: false
                                },

                                legend: {
                                    enabled: false
                                },

                                series: [{
                                    name: 'Anggaran',
                                    color: 'rgba(6, 182, 212, 0.12)',
                                    borderColor: 'rgba(6, 182, 212, 0.35)',
                                    data: [anggaran],
                                    tooltip: {
                                        pointFormatter: function() {
                                            return '<span>Anggaran: <b>Rp. ' + Highcharts.numberFormat(this.y, 0, ',', '.') + '</b></span>';
                                        }
                                    }
                                }, {
                                    name: 'Realisasi',
                                    color: 'rgba(168, 85, 247, 0.95)',
                                    borderColor: '#c084fc',
                                    shadow: {
                                        color: 'rgba(168, 85, 247, 0.3)',
                                        width: 4,
                                        offsetX: 0,
                                        offsetY: 0
                                    },
                                    data: [realisasi],
                                    tooltip: {
                                        pointFormatter: function() {
                                            return '<span>Realisasi: <b>Rp. ' + Highcharts.numberFormat(this.y, 0, ',', '.') + '</b></span>';
                                        }
                                    }
                                }]
                            });
                        }
                    }
                });
            }

            $("p#grafik-tahun").text("Tahun " + year);
        }

        function resetContainer() {
            $("#grafik-container").html("");
        }

        function gantiTahun(newThn) {
            year = newThn;
            displayChart(year, tipe);
        }

        function gantiTipe(newType) {
            tipe = newType;
            displayChart(year, tipe);
        }

        $("#keuangan-selector").change(function() {
            gantiTahun($("#keuangan-selector").val());
        })

        $(document).ready(function() {
            //Realisasi Pelaksanaan APBD
            $("#keuangan-selector").val("{{ $widget_keuangan['tahun_terbaru'] }}")
            displayChart(year, tipe);
        });
    </script>
@endif
