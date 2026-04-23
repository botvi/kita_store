@extends('template-admin.layout')

@section('content')
    <section class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard-superadmin">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('pesanan.index') }}">Pesanan</a></li>
                                <li class="breadcrumb-item" aria-current="page">Detail Pesanan</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title d-flex justify-content-between">
                                <h2 class="mb-0">Detail Pesanan #{{ $pesanan->order_id }}</h2>
                                <a href="{{ route('pesanan.index') }}" class="btn btn-light"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Daftar Produk</h5>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @php
                                    $details = is_string($pesanan->produk_id) ? json_decode($pesanan->produk_id, true) : $pesanan->produk_id;
                                @endphp
                                @if(is_array($details))
                                    @foreach($details as $detail)
                                    <li class="list-group-item p-4">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="fw-bold mb-1">{{ $detail['nama_produk'] }}</h6>
                                                <span class="text-muted d-block mt-2">
                                                    @if(!empty($detail['varian'])) <span class="badge bg-light text-dark border">Varian: {{ $detail['varian'] }}</span> @endif
                                                    @if(!empty($detail['ukuran'])) <span class="badge bg-light text-dark border">Ukuran: {{ $detail['ukuran'] }}</span> @endif
                                                    @if(!empty($detail['foto_custom'])) <a href="{{ asset($detail['foto_custom']) }}" target="_blank" class="badge bg-primary mt-1 text-decoration-none"><i class="fa fa-image"></i> Lihat Desain Custom</a> @endif
                                                </span>
                                                <p class="mb-0 mt-3"><span class="text-muted">Harga:</span> Rp {{ number_format($detail['harga_satuan'] ?? 0, 0, ',', '.') }} x {{ $detail['qty'] }}</p>
                                            </div>
                                            <div class="text-end">
                                                <h5 class="fw-bold text-success mb-0">Rp {{ number_format($detail['subtotal'], 0, ',', '.') }}</h5>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-muted">Total Tagihan</h5>
                            <h4 class="mb-0 fw-bold text-primary">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Data Pembeli & Pengiriman</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-1 small">Nama Pembeli</p>
                            <h6 class="fw-bold mb-3">{{ $pesanan->user->name ?? 'User Dihapus' }}</h6>

                            <p class="text-muted mb-1 small">Nomor HP/WA</p>
                            <h6 class="fw-bold mb-3">{{ $pesanan->user->no_wa ?? '-' }}</h6>

                            <p class="text-muted mb-1 small">Email</p>
                            <h6 class="fw-bold mb-3">{{ $pesanan->user->email ?? '-' }}</h6>

                            <p class="text-muted mb-1 small">Alamat Pengiriman</p>
                            <div class="p-3 bg-light border rounded">
                                {{ $pesanan->alamat }}
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Status Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <p class="text-muted mb-1 small">Status Pembayaran</p>
                                @if($pesanan->status == 'UNPAID')
                                    <span class="badge bg-warning text-dark fs-6 w-100 py-2">BELUM DIBAYAR</span>
                                @else
                                    <span class="badge bg-success fs-6 w-100 py-2">LUNAS PADA {{ $pesanan->updated_at->format('d M Y') }}</span>
                                @endif
                            </div>

                            <form action="{{ route('pesanan.update', $pesanan->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <p class="text-muted mb-1 small">Proses Pengiriman</p>
                                    <select name="status_pengiriman" class="form-control" {{ $pesanan->status == 'UNPAID' ? 'disabled' : '' }}>
                                        <option value="Pesanan Sedang Diproses" {{ $pesanan->status_pengiriman == 'Pesanan Sedang Diproses' ? 'selected' : '' }}>Pesanan Sedang Diproses</option>
                                        <option value="Pesanan Diantar" {{ $pesanan->status_pengiriman == 'Pesanan Diantar' ? 'selected' : '' }}>Pesanan Diantar</option>
                                        <option value="Pesanan Telah Sampai" {{ $pesanan->status_pengiriman == 'Pesanan Telah Sampai' ? 'selected' : '' }}>Pesanan Telah Sampai</option>
                                    </select>
                                    @if($pesanan->status == 'UNPAID')
                                        <small class="text-danger mt-1 d-block"><i class="fa fa-info-circle"></i> Status pengiriman tidak dapat diubah karena belum dibayar.</small>
                                    @endif
                                </div>
                                @if($pesanan->status != 'UNPAID')
                                    <button type="submit" class="btn btn-primary w-100"><i class="fa fa-save"></i> Ubah Status Pengiriman</button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Sukses',
        text: '{{ session('success') }}',
        timer: 3000
    });
</script>
@endif
@endsection
