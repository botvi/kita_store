@extends('pageuser.layout')

@section('content')
<div class="page-header text-center">
    <div class="container">
        <h1 class="page-title">Keranjang Belanja</h1>
        <p class="text-muted mt-2">Selesaikan pesananmu sekarang sebelum kehabisan stok.</p>
    </div>
</div>

<div class="container pb-5 mb-5">
    @if($keranjangs->count() > 0)
    <div class="row g-5">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-0">
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($keranjangs as $item)
                        <li class="list-group-item p-4">
                            <div class="row align-items-center">
                                <div class="col-md-2 col-4">
                                    @if($item->model_produk && $item->model_produk->gambar && is_array($item->model_produk->gambar) && count($item->model_produk->gambar) > 0)
                                        <img src="{{ asset($item->model_produk->gambar[0]) }}" class="img-fluid" style="object-fit: cover; height: 100px; width: 100%;" alt="{{ $item->model_produk->nama_produk }}">
                                    @else
                                        <img src="https://via.placeholder.com/100" class="img-fluid" alt="">
                                    @endif
                                </div>
                                <div class="col-md-6 col-8">
                                    <h6 class="fw-bold text-uppercase mb-1">{{ $item->model_produk->nama_produk ?? 'Produk Dihapus' }}</h6>
                                    <p class="text-muted mb-2 small">{{ $item->model_produk->kategori_produk->nama_kategori ?? 'Umum' }}</p>
                                    
                                    @php
                                        $produkIdInfo = is_string($item->produk_id) ? json_decode($item->produk_id, true) : $item->produk_id;
                                        $hargaTampil = $produkIdInfo['harga_satuan'] ?? $item->model_produk->harga;
                                    @endphp
                                    @if(is_array($produkIdInfo))
                                        @if(!empty($produkIdInfo['varian'])) <span class="badge bg-light text-dark border me-1">Varian: {{ $produkIdInfo['varian'] }}</span> @endif
                                        @if(!empty($produkIdInfo['ukuran'])) <span class="badge bg-light text-dark border">Ukuran: {{ $produkIdInfo['ukuran'] }}</span> @endif
                                        @if(!empty($produkIdInfo['foto_custom']))
                                            <br><a href="{{ asset($produkIdInfo['foto_custom']) }}" target="_blank" class="badge bg-dark text-decoration-none mt-2 d-inline-block"><i class="fa fa-image"></i> Lihat Desain Custom</a>
                                        @endif
                                    @endif
                                    
                                    <h6 class="mb-0 mt-2 fw-bold">Rp {{ number_format($hargaTampil ?? 0, 0, ',', '.') }}</h6>
                                </div>
                                <div class="col-md-2 col-6 mt-3 mt-md-0">
                                    <div class="d-flex justify-content-center text-center">
                                        <p class="mb-0 fw-bold border px-3 py-2">Qty: {{ $item->qty }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2 col-6 mt-3 mt-md-0 text-end">
                                    <form action="{{ route('keranjang.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger rounded-0 btn-sm"><i class="fa-solid fa-trash me-1"></i> Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-0 sticky-top" style="top: 100px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-uppercase mb-4">Ringkasan Belanja</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Barang</span>
                        <span class="fw-bold">{{ $keranjangs->sum('qty') }} Item</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 border-bottom pb-4">
                        <span class="text-muted">Subtotal</span>
                        <h5 class="fw-bold mb-0">Rp {{ number_format($total, 0, ',', '.') }}</h5>
                    </div>
                    
                    <a href="{{ route('transaksi.summary') }}" class="btn btn-dark w-100 rounded-0 btn-lg fw-bold px-3 py-3" style="letter-spacing: 1px;">LANJUTKAN PEMBAYARAN</a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <i class="fa-solid fa-cart-shopping fs-1 text-muted mb-4"></i>
        <h3 class="fw-bold">Keranjangmu masih kosong</h3>
        <p class="text-muted mb-4">Ayo mulai belanja dan penuhi gayamu!</p>
        <a href="{{ route('katalog.index') }}" class="btn btn-dark rounded-0 fw-bold px-5 py-3">MULAI BELANJA</a>
    </div>
    @endif
</div>
@endsection
