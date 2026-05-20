@extends('template-admin.layout')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Dashboard Analytics</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard-superadmin">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- Total Pelanggan -->
                <div class="col-md-6 col-xxl-3 mb-4">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-white">{{ $pelanggan }}</h4>
                                    <h6 class="text-white m-b-0">Total Pelanggan</h6>
                                </div>
                                <div class="col-4 text-end">
                                    <i class="ti ti-users f-36"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Total Produk -->
                <div class="col-md-6 col-xxl-3 mb-4">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-white">{{ $totalProduk }}</h4>
                                    <h6 class="text-white m-b-0">Total Produk</h6>
                                </div>
                                <div class="col-4 text-end">
                                    <i class="ti ti-box f-36"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Pesanan -->
                <div class="col-md-6 col-xxl-3 mb-4">
                    <div class="card bg-warning text-white h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-white">{{ $totalPesanan }}</h4>
                                    <h6 class="text-white m-b-0">Total Pesanan</h6>
                                </div>
                                <div class="col-4 text-end">
                                    <i class="ti ti-shopping-cart f-36"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Pendapatan -->
                <div class="col-md-6 col-xxl-3 mb-4">
                    <div class="card bg-danger text-white h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-white">Rp {{ number_format($pendapatan, 0, ',', '.') }}</h4>
                                    <h6 class="text-white m-b-0">Pendapatan</h6>
                                </div>
                                <div class="col-4 text-end">
                                    <i class="ti ti-wallet f-36"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Row -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Statistik Pesanan ({{ date('Y') }})</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="pesananChart" width="100%" height="30"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('pesananChart').getContext('2d');
            var pesananChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Jumlah Pesanan',
                        data: @json($pesananPerBulan),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
