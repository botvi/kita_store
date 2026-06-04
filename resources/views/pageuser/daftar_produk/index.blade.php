@extends('pageuser.layout')

@push('style')
<style>
    /* ===== MODAL PILIH VARIAN & UKURAN ===== */
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
        border-color: #111;
        color: #111;
        background: #f5f5f5;
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(0,0,0,0.12);
    }

    .option-box.active {
        border-color: #111;
        color: #fff;
        background: #111;
        font-weight: 700;
        box-shadow: 0 0 0 2px #111;
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
        border-color: transparent transparent #fff transparent;
    }

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
        color: #111;
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

    #modal-stok-indicator {
        transition: all 0.2s ease;
    }

    .stok-badge-tersedia { background: #111 !important; color: #fff !important; }
    .stok-badge-habis    { background: #e53935 !important; color: #fff !important; }
    .stok-badge-terbatas { background: #f57c00 !important; color: #fff !important; }

    #modalKeranjang .modal-header {
        background: #111;
        color: #fff;
        border-radius: 0;
    }
    #modalKeranjang .modal-header .btn-close {
        filter: invert(1);
    }
    #modalKeranjang .modal-content {
        border-radius: 0;
        border: none;
    }
    #modalKeranjang .btn-submit-modal {
        border-radius: 0;
        font-weight: 700;
        letter-spacing: 1px;
    }
</style>
@endpush

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

                            {{-- Tombol buka modal --}}
                            <button type="button"
                                class="btn btn-dark w-100 mt-3 btn-open-modal"
                                data-produk-id="{{ $produk->id }}"
                                data-produk-nama="{{ $produk->nama_produk }}"
                                data-produk-harga="{{ $produk->harga }}"
                                data-varian="{{ json_encode($produk->varian ?? []) }}"
                                data-ukuran="{{ json_encode($produk->ukuran ?? []) }}"
                                data-stok-per-ukuran="{{ json_encode($produk->stok_per_ukuran ?? []) }}"
                                data-harga-per-ukuran="{{ json_encode($produk->harga_per_ukuran ?? []) }}"
                                data-is-custom="{{ $produk->is_custom ? '1' : '0' }}"
                                data-action="{{ route('keranjang.add', $produk->id) }}">
                                <i class="fa-solid fa-cart-shopping me-1"></i> KERANJANG
                            </button>
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

{{-- ===== MODAL PILIH VARIAN & UKURAN ===== --}}
<div class="modal fade" id="modalKeranjang" tabindex="-1" aria-labelledby="modalKeranjangLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalKeranjangLabel">
                    <i class="fa-solid fa-cart-plus me-2"></i>Tambah ke Keranjang
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="fw-bold fs-5 mb-1" id="modal-produk-nama"></p>
                <p class="text-muted mb-3" id="modal-produk-harga"></p>

                <form id="form-modal-keranjang" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Varian --}}
                    <div id="modal-wrapper-varian" class="mb-3" style="display:none;">
                        <div class="option-label">
                            Warna: <span class="selected-val" id="modal-label-varian">Pilih Warna</span>
                        </div>
                        <div class="option-box-group" id="modal-group-varian"></div>
                        <input type="hidden" name="varian" id="modal-input-varian" value="">
                        <div class="option-required-hint" id="modal-hint-varian">⚠ Silakan pilih Warna terlebih dahulu</div>
                    </div>

                    {{-- Ukuran --}}
                    <div id="modal-wrapper-ukuran" class="mb-3" style="display:none;">
                        <div class="option-label">
                            Ukuran: <span class="selected-val" id="modal-label-ukuran">Pilih Ukuran</span>
                        </div>
                        <div class="option-box-group" id="modal-group-ukuran"></div>
                        <input type="hidden" name="ukuran" id="modal-input-ukuran" value="">
                        <div class="option-required-hint" id="modal-hint-ukuran">⚠ Silakan pilih Ukuran terlebih dahulu</div>
                    </div>

                    {{-- Stok indicator --}}
                    <div class="mb-3">
                        <span id="modal-stok-indicator" class="badge rounded-0 px-3 py-2 stok-badge-tersedia"></span>
                    </div>

                    {{-- Custom foto (jika produk custom) --}}
                    <div id="modal-wrapper-custom" class="mb-3" style="display:none;">
                        <label class="form-label fw-bold text-primary">Upload Desain Custom (Opsional)</label>
                        <input type="file" name="foto_custom" class="form-control rounded-0" accept="image/*">
                        <small class="text-muted"><i class="fa fa-info-circle"></i> Produk ini mendukung desain custom.</small>
                    </div>

                    {{-- Qty --}}
                    <div class="row gx-2 mt-3 align-items-center">
                        <div class="col-auto">
                            <label class="form-label fw-bold mb-0">Qty:</label>
                        </div>
                        <div class="col-3">
                            <input type="number" name="qty" id="modal-input-qty"
                                class="form-control rounded-0 text-center"
                                value="1" min="1" max="99">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" id="modal-btn-submit" class="btn btn-dark w-100 btn-submit-modal py-2">
                            <i class="fa-solid fa-cart-plus me-2"></i> MASUKKAN KERANJANG
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl      = document.getElementById('modalKeranjang');
    const bsModal      = new bootstrap.Modal(modalEl);
    const form         = document.getElementById('form-modal-keranjang');
    const stokBadge    = document.getElementById('modal-stok-indicator');
    const btnSubmit    = document.getElementById('modal-btn-submit');
    const inputQty     = document.getElementById('modal-input-qty');

    let currentStokData = {};
    let currentHargaData = {};
    let currentHargaUtama = 0;

    // ── Render option boxes ──────────────────────────────────────────
    function renderOptions(groupId, items, type, stokData) {
        const container = document.getElementById(groupId);
        container.innerHTML = '';

        items.forEach(function (val) {
            const stok = (type === 'ukuran' && stokData[val] !== undefined)
                ? parseInt(stokData[val])
                : 99;
            const habis = (type === 'ukuran' && stok === 0);

            const div = document.createElement('div');
            div.className = 'option-box' + (habis ? ' out-of-stock' : '');
            div.dataset.group = type;
            div.dataset.value = val;
            if (type === 'ukuran') div.dataset.stok = stok;

            div.innerHTML = val + (habis ? '<small style="font-size:0.65rem;display:block;margin-top:1px;">Habis</small>' : '');
            container.appendChild(div);
        });

        // Re-attach click handlers
        container.querySelectorAll('.option-box').forEach(function (box) {
            box.addEventListener('click', handleBoxClick);
        });
    }

    // ── Handle option box click ──────────────────────────────────────
    function handleBoxClick() {
        if (this.classList.contains('out-of-stock')) return;

        const group = this.dataset.group;
        const value = this.dataset.value;

        document.querySelectorAll(`#modal-group-${group} .option-box`).forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const input = document.getElementById(`modal-input-${group}`);
        const label = document.getElementById(`modal-label-${group}`);
        const hint  = document.getElementById(`modal-hint-${group}`);
        if (input) input.value = value;
        if (label) label.textContent = value;
        if (hint)  hint.classList.remove('show');

        if (group === 'ukuran') {
            updateStokUI(value);
        }
    }

    // ── Update stok badge & tombol ───────────────────────────────────
    function updateStokUI(ukuran) {
        const stok = currentStokData[ukuran] !== undefined ? parseInt(currentStokData[ukuran]) : 0;

        if (stok === 0) {
            stokBadge.textContent = 'Stok Habis';
            stokBadge.className = 'badge rounded-0 px-3 py-2 stok-badge-habis';
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fa-solid fa-ban me-2"></i> STOK HABIS';
            inputQty.max = 0;
            inputQty.value = 0;
        } else if (stok <= 5) {
            stokBadge.textContent = 'Stok: ' + stok + ' (Terbatas!)';
            stokBadge.className = 'badge rounded-0 px-3 py-2 stok-badge-terbatas';
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="fa-solid fa-cart-plus me-2"></i> MASUKKAN KERANJANG';
            inputQty.max = stok;
            inputQty.value = Math.min(parseInt(inputQty.value) || 1, stok);
        } else {
            stokBadge.textContent = 'Stok: ' + stok;
            stokBadge.className = 'badge rounded-0 px-3 py-2 stok-badge-tersedia';
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="fa-solid fa-cart-plus me-2"></i> MASUKKAN KERANJANG';
            inputQty.max = stok;
            inputQty.value = Math.min(parseInt(inputQty.value) || 1, stok);
        }
    }

    // ── Buka modal saat tombol diklik ────────────────────────────────
    document.querySelectorAll('.btn-open-modal').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const produkId    = this.dataset.produkId;
            const produkNama  = this.dataset.produkNama;
            const produkHarga = parseInt(this.dataset.produkHarga);
            const varian      = JSON.parse(this.dataset.varian || '[]');
            const ukuran      = JSON.parse(this.dataset.ukuran || '[]');
            const stokPerUkuran  = JSON.parse(this.dataset.stokPerUkuran || '{}');
            const hargaPerUkuran = JSON.parse(this.dataset.hargaPerUkuran || '{}');
            const isCustom    = this.dataset.isCustom === '1';
            const action      = this.dataset.action;

            // Set data global
            currentStokData   = stokPerUkuran;
            currentHargaData  = hargaPerUkuran;
            currentHargaUtama = produkHarga;

            // Set form action
            form.action = action;

            // Set nama & harga
            document.getElementById('modal-produk-nama').textContent = produkNama;
            document.getElementById('modal-produk-harga').textContent =
                'Rp ' + new Intl.NumberFormat('id-ID').format(produkHarga);

            // Reset input
            document.getElementById('modal-input-varian').value = '';
            document.getElementById('modal-input-ukuran').value = '';
            document.getElementById('modal-label-varian').textContent = 'Pilih Warna';
            document.getElementById('modal-label-ukuran').textContent = 'Pilih Ukuran';
            document.getElementById('modal-hint-varian').classList.remove('show');
            document.getElementById('modal-hint-ukuran').classList.remove('show');
            inputQty.value = 1;
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="fa-solid fa-cart-plus me-2"></i> MASUKKAN KERANJANG';

            // Varian
            const wrapperVarian = document.getElementById('modal-wrapper-varian');
            if (varian.length > 0) {
                renderOptions('modal-group-varian', varian, 'varian', {});
                wrapperVarian.style.display = '';
            } else {
                wrapperVarian.style.display = 'none';
            }

            // Ukuran
            const wrapperUkuran = document.getElementById('modal-wrapper-ukuran');
            if (ukuran.length > 0) {
                renderOptions('modal-group-ukuran', ukuran, 'ukuran', stokPerUkuran);
                wrapperUkuran.style.display = '';
                // Tampilkan instruksi awal
                stokBadge.textContent = 'Pilih ukuran untuk melihat stok';
                stokBadge.className = 'badge rounded-0 px-3 py-2 stok-badge-tersedia';
            } else {
                wrapperUkuran.style.display = 'none';
                // Hitung total stok
                let totalStok = Object.values(stokPerUkuran).reduce((a, b) => parseInt(a) + parseInt(b), 0);
                if (Object.keys(stokPerUkuran).length === 0) totalStok = 99;
                if (totalStok === 0) {
                    stokBadge.textContent = 'Stok Habis';
                    stokBadge.className = 'badge rounded-0 px-3 py-2 stok-badge-habis';
                    btnSubmit.disabled = true;
                    btnSubmit.innerHTML = '<i class="fa-solid fa-ban me-2"></i> STOK HABIS';
                } else {
                    stokBadge.textContent = 'Stok: ' + totalStok;
                    stokBadge.className = 'badge rounded-0 px-3 py-2 stok-badge-tersedia';
                }
            }

            // Custom foto
            const wrapperCustom = document.getElementById('modal-wrapper-custom');
            wrapperCustom.style.display = isCustom ? '' : 'none';

            bsModal.show();
        });
    });

    // ── Validasi sebelum submit ──────────────────────────────────────
    form.addEventListener('submit', function (e) {
        let valid = true;

        const inputVarian = document.getElementById('modal-input-varian');
        const inputUkuran = document.getElementById('modal-input-ukuran');
        const wrapperVarian = document.getElementById('modal-wrapper-varian');
        const wrapperUkuran = document.getElementById('modal-wrapper-ukuran');

        if (wrapperVarian.style.display !== 'none' && inputVarian.value === '') {
            document.getElementById('modal-hint-varian').classList.add('show');
            valid = false;
        }
        if (wrapperUkuran.style.display !== 'none' && inputUkuran.value === '') {
            document.getElementById('modal-hint-ukuran').classList.add('show');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
