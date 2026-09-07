@extends('admin.layouts.master')

@section('title','dashboard')

@section('css')

@endsection

@section('content')
            <div class="page-heading">
                <h3>Selamat Datang, Admin!</h3>
            </div> 
            <div class="page-content"> 
                <section class="row">
                    <div class="col-12 col-lg-12">
                        <div class="row">
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                <div class="stats-icon purple mb-2">
                                                    <i class="iconly-boldWallet"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">Total Pesanan</h6>
                                                <h6 class="font-extrabold mb-0">{{ number_format($totalOrders, 0, ',', '.') }}</h6>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card"> 
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                <div class="stats-icon blue">
                                                    <i class="iconly-boldBuy"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">Pesanan Hari Ini</h6>
                                                <h6 class="font-extrabold mb-0">{{ number_format($todayOrdersCount, 0, ',', '.') }}</h6>
                                                <small class="text-muted">
                                                    Rp{{ number_format($todayOrdersRevenue, 0, ',', '.') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                <div class="stats-icon green mb-2">
                                                    <i class="iconly-boldFolder"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">Jumlah Item</h6>
                                                <h6 class="font-extrabold mb-0">{{ number_format($totalMenu, 0, ',', '.') }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <!-- <div class="col-6 col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                    <div class="stats-icon blue mb-2">
                                                        <i class="iconly-boldProfile"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                    <h6 class="text-muted font-semibold">Jumlah Karyawan</h6>
                                                    <h6 class="font-extrabold mb-0">{{ number_format($totalEmployee, 0, ',', '.') }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Grafik Penjualan (7 Hari Terakhir)</h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="chart-profile-visit"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.45.1/apexcharts.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartLabels = @json($chartLabels);
        const chartData = @json($chartData);

        const options = {
            chart: {
                type: 'area',
                height: 320,
                toolbar: { show: false },
            },
            series: [{
                name: 'Omzet',
                data: chartData,
            }],
            xaxis: {
                categories: chartLabels,
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return 'Rp' + val.toLocaleString('id-ID');
                    }
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            colors: ['#7367F0'],
            tooltip: {
                y: {
                    formatter: function (val) {
                        return 'Rp' + val.toLocaleString('id-ID');
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector('#chart-profile-visit'), options);
        chart.render();
    });
</script>
@endsection