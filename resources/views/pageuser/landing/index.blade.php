@extends('pageuser.layout')

@section('content')
    <div class="hero-section">
        <div class="container hero-content">
            <h1 class="display-3 fw-bold mb-4" style="text-transform: uppercase; letter-spacing: 2px;">KOJAR
            </h1>
            <p class="lead mb-5" style="max-width: 600px; margin: 0 auto; opacity: 0.9;">Tampil lebih berani dengan balutan
                desain modern yang dirancang khusus untuk memenuhi gaya fashion streetwear masa kini.</p>
            <a href="{{ route('katalog.index') }}" class="btn btn-light btn-lg rounded-0 fw-bold px-5 py-3">BELANJA
                SEKARANG</a>
        </div>
    </div>

    <div class="container py-5 my-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="text-transform: uppercase; letter-spacing: 1px;">Koleksi Terbaru</h2>
            <div style="width: 50px; height: 3px; background: #111; margin: 15px auto;"></div>
        </div>

        <div class="row g-4">
            @forelse($latestProduks as $produk)
                <div class="col-md-3">
                    <div class="card product-card h-100">
                        <a href="{{ route('katalog.index') }}">
                            @if ($produk->gambar && is_array($produk->gambar) && count($produk->gambar) > 0)
                                <img src="{{ asset($produk->gambar[0]) }}" class="product-img"
                                    alt="{{ $produk->nama_produk }}">
                            @else
                                <img src="https://via.placeholder.com/400x500?text=No+Image" class="product-img"
                                    alt="No image">
                            @endif
                        </a>
                        <div class="card-body text-center p-4">
                            <small class="text-muted text-uppercase fw-bold"
                                style="letter-spacing: 1px; font-size: 0.75rem;">{{ $produk->kategori_produk->nama_kategori ?? 'Umum' }}</small>
                            <a href="{{ route('katalog.index') }}" class="product-title">{{ $produk->nama_produk }}</a>
                            <p class="product-price mt-2 mb-0">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>

                            <a href="{{ route('katalog.show', $produk->id) }}" class="btn btn-dark w-100 mt-3"><i
                                    class="fa-solid fa-eye me-1"></i> LIHAT DETAIL</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h5 class="text-muted">Belum ada produk terbaru.</h5>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('katalog.index') }}" class="btn btn-outline-dark rounded-0 fw-bold px-5 py-3"
                style="letter-spacing: 1px;">LIHAT SEMUA KOLEKSI</a>
        </div>
    </div>
@endsection
