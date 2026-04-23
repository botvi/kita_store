@extends('template-admin.layout')

@section('content')
<section class="pc-container">
    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard-superadmin">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Laporan Penjualan</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Laporan Penjualan Lunas</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0 pb-0 pt-4 px-4">
                        <h5 class="mb-0 fw-bold">Filter Periode Laporan</h5>
                    </div>
                    <div class="card-body px-4">
                        <form action="{{ route('laporan.index') }}" method="GET" class="row align-items-end g-3">
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-bold">Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $start_date->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-bold">Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $end_date->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-6 d-flex gap-2">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-filter me-2"></i> Filter</button>
                                <a href="{{ route('laporan.index') }}" class="btn btn-light"><i class="fa fa-refresh me-1"></i> Reset</a>
                                <a href="{{ route('laporan.print', ['start_date' => $start_date->format('Y-m-d'), 'end_date' => $end_date->format('Y-m-d')]) }}" target="_blank" class="btn btn-success ms-auto"><i class="fa fa-print me-2"></i> Cetak Dokumen</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="alert alert-primary bg-primary bg-opacity-10 py-3 mb-4 border-0 rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-primary fw-bold d-block mb-1">Total Pendapatan Terseleksi</span>
                                    <small class="text-muted">{{ $start_date->format('d M Y') }} s/d {{ $end_date->format('d M Y') }}</small>
                                </div>
                                <h3 class="mb-0 text-primary fw-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                        
                        <div class="dt-responsive table-responsive">
                            <table class="table table-striped table-bordered nowrap datatable">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th>No</th>
                                        <th>Waktu Transaksi</th>
                                        <th>Order ID</th>
                                        <th>Pelanggan</th>
                                        <th>Item Terjual</th>
                                        <th class="text-end">Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pesanans as $e => $item)
                                        <tr>
                                            <td>{{ $e + 1 }}</td>
                                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                            <td><span class="badge bg-light text-dark border">{{ $item->order_id }}</span></td>
                                            <td>{{ $item->user->name ?? 'User Dihapus' }}</td>
                                            <td>
                                                @php $det = is_string($item->produk_id) ? json_decode($item->produk_id, true) : $item->produk_id; @endphp
                                                @if(is_array($det))
                                                    <ul class="mb-0 ps-3 small text-muted">
                                                    @foreach($det as $d)
                                                        <li>{{ $d['nama_produk'] }} (x{{ $d['qty'] }})</li>
                                                    @endforeach
                                                    </ul>
                                                @endif
                                            </td>
                                            <td class="text-end fw-bold">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                "pageLength": 25,
                "ordering": false
            });
        });
    </script>
@endsection