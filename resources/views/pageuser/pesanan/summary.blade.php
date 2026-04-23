@extends('pageuser.layout')

@section('content')
<div class="page-header text-center">
    <div class="container">
        <h1 class="page-title">Summary & Checkout</h1>
        <p class="text-muted mt-2">Cek kembali rincian pesanan Anda sebelum melakukan pembayaran.</p>
    </div>
</div>

<div class="container pb-5 mb-5">
    <div class="row g-5">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Data Pembeli</h5>
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Nama Lengkap</label>
                        <p class="fw-bold mb-0 border rounded px-3 py-2 bg-light">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Email</label>
                        <p class="fw-bold mb-0 border rounded px-3 py-2 bg-light">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="mb-0">
                        <label class="text-muted small mb-1">Nomor Telepon (WhatsApp)</label>
                        <p class="fw-bold mb-0 border rounded px-3 py-2 bg-light">{{ Auth::user()->no_wa ?? '-' }}</p>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top">
                        <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> Jika ingin mengubah data diri, silakan ubah melalui halaman Profil.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-0 sticky-top" style="top: 100px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-uppercase mb-4">Detail Pesanan</h5>
                    
                    <ul class="list-group list-group-flush mb-4">
                        @foreach($keranjangs as $item)
                        @if($item->model_produk)
                        <li class="list-group-item px-0 py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $item->model_produk->nama_produk }}</h6>
                                    
                                    @php
                                        $produkIdInfo = is_string($item->produk_id) ? json_decode($item->produk_id, true) : $item->produk_id;
                                    @endphp
                                    <small class="text-muted d-block mb-1">
                                        @if(is_array($produkIdInfo))
                                            @if(!empty($produkIdInfo['varian'])) Varian: {{ $produkIdInfo['varian'] }} @endif
                                            @if(!empty($produkIdInfo['ukuran'])) | Ukuran: {{ $produkIdInfo['ukuran'] }} @endif
                                            @if(!empty($produkIdInfo['foto_custom']))
                                                <br><a href="{{ asset($produkIdInfo['foto_custom']) }}" target="_blank" class="badge bg-dark mt-1 text-decoration-none"><i class="fa fa-image"></i> Lihat Desain Custom</a>
                                            @endif
                                        @endif
                                    </small>
                                    @php $hargaTampil = $produkIdInfo['harga_satuan'] ?? $item->model_produk->harga; @endphp
                                    <small class="text-muted fw-bold">Rp {{ number_format($hargaTampil, 0, ',', '.') }} x {{ $item->qty }}</small>
                                </div>
                                <span class="fw-bold">Rp {{ number_format($hargaTampil * $item->qty, 0, ',', '.') }}</span>
                            </div>
                        </li>
                        @endif
                        @endforeach
                    </ul>

                    <div class="d-flex justify-content-between mb-4 border-top pt-4">
                        <span class="text-muted fw-bold">Total Pembayaran</span>
                        <h4 class="fw-bold text-danger mb-0">Rp {{ number_format($totalHarga, 0, ',', '.') }}</h4>
                    </div>
                    
                    <form action="{{ route('transaksi.checkout') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="fw-bold mb-2">Alamat Pengiriman</label>
                            <textarea name="alamat" class="form-control rounded-0" rows="3" required placeholder="Masukkan alamat lengkap RT/RW, Kecamatan, Kota..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-dark w-100 rounded-0 btn-lg fw-bold px-3 py-3" style="letter-spacing: 1px;"><i class="fa-solid fa-lock me-2"></i> BUAT PESANAN</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
