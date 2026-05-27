@extends('pageuser.layout')

@push('style')
<style>
    /* ===== VARIANT & SIZE BOX PICKER ===== */
    .option-box-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }

    .option-box {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 52px;
        padding: 7px 14px;
        border: 1.5px solid #d0d0d0;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        background: #fff;
        color: #333;
        transition: all 0.18s ease;
        user-select: none;
        position: relative;
        overflow: hidden;
    }

    .option-box:hover:not(.out-of-stock) {
        border-color: #ee4d2d;
        color: #ee4d2d;
        background: #fff6f4;
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(238,77,45,0.15);
    }

    .option-box.active {
        border-color: #ee4d2d;
        color: #ee4d2d;
        background: #fff6f4;
        font-weight: 700;
        box-shadow: 0 0 0 1.5px #ee4d2d;
    }

    .option-box.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 0 14px 14px;
        border-color: transparent transparent #ee4d2d transparent;
    }

    /* Stok habis */
    .option-box.out-of-stock {
        border-color: #e0e0e0;
        color: #bbb;
        background: #f9f9f9;
        cursor: not-allowed;
        text-decoration: line-through;
    }

    .option-label {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #555;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .option-label .selected-val {
        font-weight: 400;
        color: #ee4d2d;
        text-transform: none;
        letter-spacing: 0;
    }

    .option-required-hint {
        font-size: 0.75rem;
        color: #e53935;
        display: none;
        margin-top: 4px;
    }

    .option-required-hint.show {
        display: block;
    }

    /* Stok indicator */
    #stok-indicator {
        transition: all 0.2s ease;
    }

    .stok-badge-tersedia {
        background: #111 !important;
        color: #fff !important;
    }

    .stok-badge-habis {
        background: #e53935 !important;
        color: #fff !important;
    }

    .stok-badge-terbatas {
        background: #f57c00 !important;
        color: #fff !important;
    }
</style>
@endpush

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

                {{-- Stok Indicator (dinamis berdasarkan ukuran dipilih) --}}
                <div class="mb-4">
                    @php
                        $stokPerUkuran = $produk->stok_per_ukuran;
                        $hasUkuran = is_array($produk->ukuran) && count($produk->ukuran) > 0;
                        $defaultStok = is_array($stokPerUkuran) ? array_sum($stokPerUkuran) : ($produk->stok ?? 0);
                    @endphp
                    <span id="stok-indicator" class="badge rounded-0 px-3 py-2 fs-6 stok-badge-tersedia">
                        @if($hasUkuran)
                            Pilih ukuran untuk melihat stok
                        @else
                            Stok: {{ $defaultStok }}
                        @endif
                    </span>
                </div>

                <form action="{{ route('keranjang.add', $produk->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if(is_array($produk->varian) && count($produk->varian) > 0)
                    <div class="mb-3" id="wrapper-varian">
                        <div class="option-label">
                            Varian: <span class="selected-val" id="label-varian">Pilih Varian</span>
                        </div>
                        <div class="option-box-group" id="group-varian">
                            @foreach($produk->varian as $varian)
                            <div class="option-box" data-group="varian" data-value="{{ $varian }}">{{ $varian }}</div>
                            @endforeach
                        </div>
                        <input type="hidden" name="varian" id="input-varian" value="">
                        <div class="option-required-hint" id="hint-varian">⚠ Silakan pilih varian terlebih dahulu</div>
                    </div>
                    @endif

                    @if($hasUkuran)
                    <div class="mb-3" id="wrapper-ukuran">
                        <div class="option-label">
                            Ukuran: <span class="selected-val" id="label-ukuran">Pilih Ukuran</span>
                        </div>
                        <div class="option-box-group" id="group-ukuran">
                            @foreach($produk->ukuran as $ukuran)
                            @php
                                $stokUkuranIni = is_array($stokPerUkuran) ? ($stokPerUkuran[$ukuran] ?? 0) : 0;
                            @endphp
                            <div class="option-box {{ $stokUkuranIni == 0 ? 'out-of-stock' : '' }}"
                                 data-group="ukuran"
                                 data-value="{{ $ukuran }}"
                                 data-stok="{{ $stokUkuranIni }}">
                                {{ $ukuran }}
                                @if($stokUkuranIni == 0)
                                    <small style="font-size:0.65rem; display:block; margin-top:1px;">Habis</small>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="ukuran" id="input-ukuran" value="">
                        <div class="option-required-hint" id="hint-ukuran">⚠ Silakan pilih ukuran terlebih dahulu</div>
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
                            <input type="number" name="qty" id="input-qty" class="form-control form-control-lg rounded-0 text-center" value="1" min="1" max="{{ $hasUkuran ? 1 : $defaultStok }}">
                        </div>
                        <div class="col-sm-9 col-8 mb-2">
                            <button type="submit" id="btn-keranjang" class="btn btn-dark btn-lg rounded-0 w-100 fw-bold">
                                <i class="fa-solid fa-cart-plus me-2"></i> MASUKKAN KERANJANG
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const stokData    = @json($produk->stok_per_ukuran ?? []);
const hasUkuran   = {{ $hasUkuran ? 'true' : 'false' }};
const totalStok   = {{ $defaultStok }};

document.addEventListener('DOMContentLoaded', function () {

    const stokIndicator = document.getElementById('stok-indicator');
    const btnKeranjang  = document.getElementById('btn-keranjang');
    const inputQty      = document.getElementById('input-qty');

    // ─── Update stok indicator setelah ukuran dipilih ───────────────
    function updateStokUI(ukuran) {
        const stok = stokData[ukuran] !== undefined ? parseInt(stokData[ukuran]) : 0;

        if (stok === 0) {
            stokIndicator.textContent = 'Stok Habis';
            stokIndicator.className = 'badge rounded-0 px-3 py-2 fs-6 stok-badge-habis';
            btnKeranjang.disabled = true;
            btnKeranjang.innerHTML = '<i class="fa-solid fa-ban me-2"></i> STOK HABIS';
            if (inputQty) { inputQty.max = 0; inputQty.value = 0; }
        } else if (stok <= 5) {
            stokIndicator.textContent = 'Stok: ' + stok + ' (Terbatas!)';
            stokIndicator.className = 'badge rounded-0 px-3 py-2 fs-6 stok-badge-terbatas';
            btnKeranjang.disabled = false;
            btnKeranjang.innerHTML = '<i class="fa-solid fa-cart-plus me-2"></i> MASUKKAN KERANJANG';
            if (inputQty) { inputQty.max = stok; inputQty.value = Math.min(parseInt(inputQty.value) || 1, stok); }
        } else {
            stokIndicator.textContent = 'Stok: ' + stok;
            stokIndicator.className = 'badge rounded-0 px-3 py-2 fs-6 stok-badge-tersedia';
            btnKeranjang.disabled = false;
            btnKeranjang.innerHTML = '<i class="fa-solid fa-cart-plus me-2"></i> MASUKKAN KERANJANG';
            if (inputQty) { inputQty.max = stok; inputQty.value = Math.min(parseInt(inputQty.value) || 1, stok); }
        }
    }

    // ─── Jika tidak ada ukuran, set stok dari default ───────────────
    if (!hasUkuran) {
        if (totalStok === 0) {
            if (stokIndicator) { stokIndicator.textContent = 'Stok Habis'; stokIndicator.className = 'badge rounded-0 px-3 py-2 fs-6 stok-badge-habis'; }
            if (btnKeranjang)  { btnKeranjang.disabled = true; btnKeranjang.innerHTML = '<i class="fa-solid fa-ban me-2"></i> STOK HABIS'; }
        }
    }

    // ─── Handle option box clicks ───────────────────────────────────
    document.querySelectorAll('.option-box').forEach(function (box) {
        box.addEventListener('click', function () {
            // Jangan proses jika out-of-stock
            if (this.classList.contains('out-of-stock')) return;

            const group = this.dataset.group;
            const value = this.dataset.value;

            // Deselect all in same group
            document.querySelectorAll(`.option-box[data-group="${group}"]`).forEach(b => b.classList.remove('active'));

            // Activate clicked
            this.classList.add('active');

            // Update hidden input & label
            const input = document.getElementById(`input-${group}`);
            const label = document.getElementById(`label-${group}`);
            const hint  = document.getElementById(`hint-${group}`);

            if (input)  input.value = value;
            if (label)  label.textContent = value;
            if (hint)   hint.classList.remove('show');

            // Update stok UI jika group ukuran
            if (group === 'ukuran') {
                updateStokUI(value);
            }
        });
    });

    // ─── Validate before submit ──────────────────────────────────────
    const form = document.querySelector('form[action*="keranjang"]');
    if (form) {
        form.addEventListener('submit', function (e) {
            let valid = true;

            const inputVarian = document.getElementById('input-varian');
            const inputUkuran = document.getElementById('input-ukuran');

            if (inputVarian && inputVarian.value === '') {
                document.getElementById('hint-varian').classList.add('show');
                valid = false;
            }
            if (inputUkuran && inputUkuran.value === '') {
                document.getElementById('hint-ukuran').classList.add('show');
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
                const firstHint = document.querySelector('.option-required-hint.show');
                if (firstHint) firstHint.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }
});
</script>
@endpush
