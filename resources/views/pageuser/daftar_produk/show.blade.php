@extends('pageuser.layout')

@section('content')
<div class="container py-5 my-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            @if($produk->gambar && is_array($produk->gambar) && count($produk->gambar) > 0)
                <div id="carouselProduct" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($produk->gambar as $idx => $gbr)
                        <div class="carousel-item {{ $idx == 0 ? 'active' : '' }}">
                            <img src="{{ asset($gbr) }}" class="d-block w-100 rounded" style="height: 600px; object-fit: cover;" alt="{{ $produk->nama_produk }}">
                        </div>
                        @endforeach
                    </div>
                    @if(count($produk->gambar) > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselProduct" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(1);"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselProduct" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true" style="filter: invert(1);"></span>
                    </button>
                    @endif
                </div>
            @else
                <img src="https://via.placeholder.com/600x800" class="img-fluid rounded border" style="height: 600px; object-fit:cover; width:100%;" alt="">
            @endif
        </div>
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('katalog.index') }}" class="text-decoration-none text-muted">Katalog</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $produk->nama_produk }}</li>
                </ol>
            </nav>
            <div class="p-2 d-flex flex-column justify-content-center">
                <small class="text-muted text-uppercase fw-bold mb-2">{{ $produk->kategori_produk->nama_kategori ?? 'Umum' }}</small>
                <h2 class="fw-bold mb-3" style="text-transform: uppercase;">{{ $produk->nama_produk }}</h2>
                <h4 class="text-dark fw-bold mb-4">
                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    @if($produk->is_custom == 1 && $produk->harga_custom)
                        <br><small class="text-muted fs-6">Harga Custom: Rp {{ number_format($produk->harga_custom, 0, ',', '.') }}</small>
                    @endif
                </h4>
                
                <div class="mb-4">
                    <h6 class="fw-bold text-uppercase">Deskripsi Produk</h6>
                    <p class="text-muted" style="line-height: 1.8;">{!! nl2br(e($produk->deskripsi)) !!}</p>
                </div>

                <div class="mb-4">
                    <span class="badge bg-dark rounded-0 px-3 py-2 fs-6">Stok: {{ $produk->stok ?? 'Tersedia' }}</span>
                </div>
                
                <form action="{{ route('keranjang.add', $produk->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    @if(is_array($produk->varian) && count($produk->varian) > 0)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Varian</label>
                        <select name="varian" class="form-select rounded-0" required>
                            <option value="">Pilih Varian</option>
                            @foreach($produk->varian as $varian)
                                <option value="{{ $varian }}">{{ $varian }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    @if(is_array($produk->ukuran) && count($produk->ukuran) > 0)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ukuran</label>
                        <select name="ukuran" class="form-select rounded-0" required>
                            <option value="">Pilih Ukuran</option>
                            @foreach($produk->ukuran as $ukuran)
                                <option value="{{ $ukuran }}">{{ $ukuran }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    @if($produk->is_custom == 1)
                    <div class="mb-3">
                        <label class="form-label fw-bold text-primary">Upload Desain Custom (Opsional / Wajib jika ingin custom)</label>
                        <input type="file" name="foto_custom" class="form-control rounded-0" accept="image/*">
                        <small class="text-muted"><i class="fa fa-info-circle"></i> Produk ini mendukung desain custom, silakan upload desain Anda.</small>
                    </div>
                    @endif

                    <div class="row gx-2 mt-4">
                        <div class="col-sm-3 col-4 mb-2">
                            <input type="number" name="qty" class="form-control form-control-lg rounded-0 text-center" value="1" min="1" max="{{ $produk->stok ?? '' }}">
                        </div>
                        <div class="col-sm-9 col-8 mb-2">
                            <button type="submit" class="btn btn-dark btn-lg rounded-0 w-100 fw-bold"><i class="fa-solid fa-cart-plus me-2"></i> MASUKKAN KERANJANG</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
