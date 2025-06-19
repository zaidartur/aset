@extends('layouts.layout')

@section('title', 'Home')


@section('css')
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/apex-charts/apex-charts.css" />
@endsection


@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="row">
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-armchair ti-md"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">{{ number_format($total) }}</h4>
                    </div>
                    <p class="mb-1">Total Semua Aset</p>
                    <p class="mb-0">
                        <span class="fw-medium me-1">{{ ($diff > 0 ? '+' : ($diff == 0 ? '' : '-')) . $diff }}</span>
                        <small class="text-muted">dari tahun sebelumnya</small>
                    </p>
                </div>
            </div>
        </div>
        @foreach ($widget as $item)
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-info"><i class="ti ti-armchair ti-md"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">{{ number_format($item['jumlah']) }}</h4>
                        </div>
                        <p class="mb-1">Total Aset Tahun {{ $item['tahun'] }}</p>
                        <p class="mb-0">
                            <span class="fw-medium me-1">&nbsp;</span>
                            <small class="text-muted">&nbsp;</small>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-md-6 col-xxl-4 mb-4 order-1 order-xxl-3">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Sebaran aset berdasarkan lokasi</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if (count($charts) > 0)
                        <div id="chart_sebaran" class="pt-md-4"></div>
                    @else
                        <h4 class="text-center">Tidak ada data</h4>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        {{-- <p class="card-subtitle text-muted mb-1">Kondisi Barang</p> --}}
                        <h5 class="card-title mb-0">Kondisi Barang</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart_kondisi"></div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection


@section('js')
<!-- Vendors JS -->
<script src="{{ asset('') }}assets/vendor/libs/apex-charts/apexcharts.js"></script>

<script>
$(document).ready(function() {
    let cardColor, headingColor, labelColor, borderColor, legendColor;

    if (isDarkStyle) {
        labelColor = config.colors_dark.textMuted;
        headingColor = config.colors_dark.headingColor;
        cardColor = config.colors_dark.cardColor;
        legendColor = config.colors_dark.bodyColor;
        borderColor = config.colors_dark.borderColor;
    } else {
        labelColor = config.colors.textMuted;
        headingColor = config.colors.headingColor;
        cardColor = config.colors.cardColor;
        legendColor = config.colors.bodyColor;
        borderColor = config.colors.borderColor;
    }
    // Chart Colors
    const chartColors = {
        donut: {
            series1: config.colors.success,
            series2: '#28c76fb3',
            series3: '#28c76f80',
            series4: config.colors_label.success
        },
        line: {
            series1: config.colors.warning,
            series2: config.colors.primary,
            series3: '#7367f029'
        }
    };

    @if (count($charts) > 0)
    const sebaran = document.querySelector('#chart_sebaran'),
    chartConfig = {
        chart: {
            height: 420,
            parentHeightOffset: 0,
            type: 'donut'
        },
        labels: [
            @foreach ($charts['data'] as $label)
                '{{ $label['label'] }}',
            @endforeach
        ],
        series: [
            @foreach ($charts['data'] as $label)
                {{ $label['value'] }},
            @endforeach
        ],
        // colors: [
        //     chartColors.donut.series1,
        //     chartColors.donut.series2,
        //     chartColors.donut.series3,
        //     chartColors.donut.series4
        // ],
        stroke: {
            width: 0
        },
        dataLabels: {
            enabled: false,
            formatter: function (val, opt) {
                return parseInt(val) + '';
            }
        },
        legend: {
            show: true,
            position: 'bottom',
            offsetY: 10,
            markers: {
                width: 8,
                height: 8,
                offsetX: -3
            },
            itemMargin: {
                horizontal: 15,
                vertical: 5
            },
            fontSize: '13px',
            fontFamily: 'Public Sans',
            fontWeight: 400,
            labels: {
                colors: headingColor,
                useSeriesColors: false
            }
        },
        tooltip: {
            theme: false
        },
        grid: {
            padding: {
                top: 15
            }
        },
        states: {
            hover: {
                filter: {
                type: 'none'
                }
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '77%',
                    labels: {
                        show: true,
                        value: {
                            fontSize: '26px',
                            fontFamily: 'Public Sans',
                            color: headingColor,
                            fontWeight: 500,
                            offsetY: -30,
                            formatter: function (val) {
                                return parseInt(val) + '';
                            }
                        },
                        name: {
                            offsetY: 20,
                            fontFamily: 'Public Sans'
                        },
                        total: {
                            show: true,
                            fontSize: '.75rem',
                            label: 'Total Aset',
                            color: labelColor,
                            formatter: function (w) {
                                return '{{ $charts["total"] }}';
                            }
                        }
                    }
                }
            }
        },
        responsive: [
        {
            breakpoint: 420,
            options: {
                chart: {
                    height: 360
                }
            }
        }]
    };
    if (typeof sebaran !== undefined && sebaran !== null) {
        const chartSebaran = new ApexCharts(sebaran, chartConfig);
        chartSebaran.render();
    }
    @endif

    const kondisi = document.querySelector('#chart_kondisi'),
    kondisiConfig = {
        chart: {
            height: 400,
            type: 'bar',
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
            horizontal: true,
            barHeight: '30%',
            startingShape: 'rounded',
            borderRadius: 8
            }
        },
        grid: {
            borderColor: borderColor,
            xaxis: {
                lines: {
                show: false
                }
            },
            padding: {
                top: -20,
                bottom: -12
            }
        },
        dataLabels: {
            enabled: true
        },
        series: [{
            data: [
                {{ $cond['baik'] }},
                {{ $cond['ringan'] }},
                {{ $cond['berat'] }}
            ]
        }],
        xaxis: {
            categories: ['BAIK', 'RUSAK RINGAN', 'RUSAK BERAT'],
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
            labels: {
                style: {
                colors: labelColor,
                fontSize: '13px'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: labelColor,
                    fontSize: '13px'
                },
                formatter: function (value) {
                    return value + " data";
                }
            }
        }
    };
    if (typeof kondisi !== undefined && kondisi !== null) {
        const kondisiChart = new ApexCharts(kondisi, kondisiConfig);
        kondisiChart.render();
    }
})
</script>
@endsection