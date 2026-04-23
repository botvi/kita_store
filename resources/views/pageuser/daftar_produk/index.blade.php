@extends('pageuser.layout')

@section('content')
<div class="page-header text-center">
    <div class="container">
        <h1 class="page-title">Katalog Produk</h1>
        <p class="text-muted mt-2">Pilih gaya favoritmu dari jajaran koleksi terbaik kami.</p>
    </div>
</div>

<div class="container pb-5 mb-5">
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm rounded-0">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4" style="text-transform: uppercase;">Kategori</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 border-0">
                            <a href="{{ route('katalog.index') }}" class="text-decoration-none {{ !request('kategori') ? 'text-dark fw-bold' : 'text-muted' }}">Semua Kategori</a>
                        </li>
                        @foreach($kategoris as $kat)
                        <li class="list-group-item px-0 border-0">
                            <a href="{{ route('katalog.index', ['kategori' => $kat->id]) }}" class="text-decoration-none {{ request('kategori') == $kat->id ? 'text-dark fw-bold' : 'text-muted' }}">
                                {{ $kat->nama_kategori }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-md-9">
            <div class="row g-4">
                @forelse($produks as $produk)
                <div class="col-md-4">
                    <div class="card product-card h-100">
                        <a href="{{ route('katalog.show', $produk->id) }}">
                            @if($produk->gambar && is_array($produk->gambar) && count($produk->gambar) > 0)
                                <img src="{{ asset($produk->gambar[0]) }}" class="product-img" alt="{{ $produk->nama_produk }}">
                            @else
                                <img src="https://via.placeholder.com/400x500?text=No+Image" class="product-img" alt="No image">
                            @endif
                        </a>
                        <div class="card-body text-center p-4">
                            <small class="text-muted text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">{{ $produk->kategori_produk->nama_kategori ?? 'Umum' }}</small>
                            <a href="{{ route('katalog.show', $produk->id) }}" class="product-title">{{ $produk->nama_produk }}</a>
                            <p class="product-price mt-2 mb-0">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                            
                            <form action="{{ route('keranjang.add', $produk->id) }}" method="POST" class="mt-3">
                                @csrf
                                <input type="hidden" name="qty" value="1">
                                <button type="submit" class="btn btn-dark w-100"><i class="fa-solid fa-cart-shopping me-1"></i> KERANJANG</button>
                            </form>
                        </div>
                    </div>
                </div>


                
                @empty
                <div class="col-12 text-center py-5">
                    <i class="fa-solid fa-box-open fs-1 text-muted mb-3"></i>
                    <h5 class="fw-bold">Produk tidak ditemukan</h5>
                    <p class="text-muted">Cobalah memilih kategori lain.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
