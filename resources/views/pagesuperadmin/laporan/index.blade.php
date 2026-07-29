@extends('template-admin.layout')

@push('style')
<style>
    /* Multi-modal z-index fix for Bootstrap 5 stacked modals */
    #modalPilihVarianOffline {
        z-index: 1070 !important;
    }
    .modal-backdrop.show:nth-of-type(even) {
        z-index: 1065 !important;
    }

    /* ===== OPTION BOX STYLING PERSIS SEPERTI SHOW.BLADE.PHP ===== */
    .option-box-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 6px;
    }

    .option-box {
        display: inline-flex;
        align-items: center;
        justify-content: flex-start;
        min-width: 90px;
        padding: 8px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 20px; /* pill shape */
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        background: #f8fafc;
        color: #475569;
        transition: all 0.2s ease;
        user-select: none;
        position: relative;
        gap: 8px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .option-box:hover:not(.out-of-stock) {
        border-color: #0d6efd;
        color: #0d6efd;
        background: #f1f5f9;
    }

    .option-box.active {
        border-color: #0d6efd !important;
        color: #0d6efd !important;
        background: #eff6ff !important;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.15) !important;
    }

    .option-box.out-of-stock {
        border-color: #cbd5e1;
        color: #94a3b8;
        background: #f1f5f9;
        cursor: not-allowed;
        text-decoration: line-through;
        opacity: 0.5;
    }

    .option-label {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 4px;
    }

    .stok-badge-tersedia { background: #198754 !important; color: #fff !important; }
    .stok-badge-habis    { background: #dc3545 !important; color: #fff !important; }
    .stok-badge-terbatas { background: #fd7e14 !important; color: #fff !important; }

    /* Visual Product Card Styles */
    .pos-product-card {
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        border: 1.5px solid #e2e8f0;
    }

    .pos-product-card:hover {
        border-color: #0d6efd !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
    }

    .product-thumb-sm {
        width: 30px;
        height: 30px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
    }
</style>
@endpush

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
                    <div class="col-md-12 d-flex justify-content-between align-items-center">
                        <div class="page-header-title">
                            <h2 class="mb-0">Laporan Penjualan Lunas</h2>
                        </div>
                        <button type="button" class="btn btn-warning text-dark fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalInputOffline">
                            <i class="fa fa-cash-register me-1"></i> Input Penjualan Offline (Kasir POS)
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-sm-12 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0 pb-0 pt-4 px-4">
                        <h5 class="mb-0 fw-bold">Filter Periode Laporan</h5>
                    </div>
                    <div class="card-body px-4">
                        <form action="{{ route('laporan.index') }}" method="GET" class="row align-items-end g-3">
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-bold">Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $start_date ? $start_date->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-bold">Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $end_date ? $end_date->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-md-6 d-flex gap-2">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-filter me-2"></i> Filter</button>
                                <a href="{{ route('laporan.index') }}" class="btn btn-light"><i class="fa fa-refresh me-1"></i> Reset</a>
                                <a href="{{ route('laporan.print', ['start_date' => $start_date ? $start_date->format('Y-m-d') : '', 'end_date' => $end_date ? $end_date->format('Y-m-d') : '']) }}" target="_blank" class="btn btn-success ms-auto"><i class="fa fa-print me-2"></i> Cetak Dokumen</a>
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
                                    <small class="text-muted">
                                        @if($start_date && $end_date)
                                            {{ $start_date->format('d M Y') }} s/d {{ $end_date->format('d M Y') }}
                                        @else
                                            Semua Periode
                                        @endif
                                    </small>
                                </div>
                                <h3 class="mb-0 text-primary fw-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                        
                        <div class="dt-responsive table-responsive">
                            <table class="table table-striped table-bordered nowrap align-middle datatable">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th style="width: 5%">No</th>
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
                                            <td>
                                                <span class="badge bg-light text-dark border">{{ $item->order_id }}</span>
                                                @if(!empty($item->nama_pembeli))
                                                    <span class="badge bg-info text-dark ms-1">Offline</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-dark">{{ $item->nama_pelanggan }}</span>
                                                @if(!empty($item->nama_pembeli))
                                                    <small class="text-muted d-block" style="font-size: 0.75rem;">Admin: {{ $item->user->name ?? 'Admin' }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @php $det = is_string($item->produk_id) ? json_decode($item->produk_id, true) : $item->produk_id; @endphp
                                                @if(is_array($det))
                                                    <div class="d-flex flex-column gap-2 py-1">
                                                    @foreach($det as $d)
                                                        <div class="d-flex align-items-center gap-2">
                                                         
                                                            <div>
                                                                <span class="fw-bold d-block text-dark small" style="line-height: 1.2;">{{ $d['nama_produk'] }}</span>
                                                                <span class="small text-muted">
                                                                    @if(!empty($d['ukuran'])) <span class="badge bg-light text-dark border me-1">Uk: {{ $d['ukuran'] }}</span> @endif
                                                                    @if(!empty($d['varian'])) <span class="badge bg-light text-dark border me-1">{{ $d['varian'] }}</span> @endif
                                                                    (x{{ $d['qty'] }})
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    </div>
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

<!-- MODAL KASIR UTAMA (POS VISUAL KATALOG BERGAMBAR) -->
<div class="modal fade" id="modalInputOffline" tabindex="-1" aria-labelledby="modalInputOfflineLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <form action="{{ route('laporan.storeOffline') }}" method="POST" id="formPenjualanOffline">
                @csrf
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title text-white fw-bold d-flex align-items-center" id="modalInputOfflineLabel">
                        <i class="fa fa-cash-register text-warning me-2 fs-4"></i> KASIR PENJUALAN OFFLINE (POS)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 bg-light">
                    <div class="row g-4">
                        
                        <!-- KOLOM KIRI: KATALOG PRODUK BERGAMBAR -->
                        <div class="col-lg-6 col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center">
                                        <i class="fa fa-th-large me-2 text-primary"></i> 1. PILIH PRODUK DARI KATALOG
                                    </h6>
                                    <span class="badge bg-light text-dark border" id="pos-product-count">{{ count($produks) }} Produk</span>
                                </div>
                                <div class="card-body p-3">
                                    <!-- Search Input -->
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                                        <input type="text" id="pos-search-input" class="form-control border-start-0 ps-0" placeholder="Cari nama produk...">
                                    </div>

                                    <!-- Visual Product Cards Grid -->
                                    <div class="row g-2" id="pos-product-grid" style="max-height: 440px; overflow-y: auto;">
                                        @foreach($produks as $p)
                                            @php
                                                $gbr = (is_array($p->gambar) && count($p->gambar) > 0) ? asset($p->gambar[0]) : null;
                                            @endphp
                                            <div class="col-6 col-sm-4 pos-card-item" data-name="{{ strtolower($p->nama_produk) }}">
                                                <div class="pos-product-card p-2 rounded text-center h-100 bg-white shadow-sm" data-id="{{ $p->id }}">
                                                    @if($gbr)
                                                        <img src="{{ $gbr }}" class="rounded mb-2" style="width: 100%; height: 90px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded mb-2 d-flex align-items-center justify-content-center text-muted small" style="height: 90px; font-size: 0.7rem;">No Image</div>
                                                    @endif
                                                    <h6 class="fw-bold mb-1 text-dark text-truncate small" title="{{ $p->nama_produk }}">{{ $p->nama_produk }}</h6>
                                                    <span class="text-primary fw-bold small d-block">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 mt-2 py-1 small fw-bold btn-pilih-produk" data-id="{{ $p->id }}">
                                                        <i class="fa fa-plus me-1"></i> Pilih
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KOLOM KANAN: STRUK & KERANJANG KASIR -->
                        <div class="col-lg-6 col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center">
                                        <i class="fa fa-receipt me-2 text-success"></i> 2. STRUK TRANSAKSI KASIR
                                    </h6>
                                    <span class="badge bg-light text-dark border fw-normal" id="cart-count-badge">0 Item</span>
                                </div>
                                <div class="card-body p-4 d-flex flex-column justify-content-between">
                                    <div>
                                        <!-- Nama Pembeli Offline -->
                                        <div class="mb-3">
                                            <label class="form-label text-muted small fw-bold text-uppercase">Nama Pembeli Offline</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fa fa-user text-muted"></i></span>
                                                <input type="text" name="nama_pembeli" class="form-control fw-bold" value="Pembeli Offline" placeholder="Masukkan nama pembeli..." required>
                                            </div>
                                        </div>

                                        <!-- Tabel Struk Items -->
                                        <div class="table-responsive border rounded mb-3 bg-white" style="min-height: 250px; max-height: 350px; overflow-y: auto;">
                                            <table class="table table-hover align-middle mb-0" id="tableOfflineCart">
                                                <thead class="table-light sticky-top">
                                                    <tr>
                                                        <th>Produk</th>
                                                        <th style="width: 120px;" class="text-center">Jumlah (Qty)</th>
                                                        <th style="width: 110px;" class="text-end">Subtotal</th>
                                                        <th style="width: 40px;" class="text-center"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="cart-container">
                                                    <tr id="row-empty-cart">
                                                        <td colspan="4" class="text-center text-muted py-5">
                                                            <i class="fa fa-shopping-basket fs-2 mb-2 d-block opacity-25"></i>
                                                            Struk belanjaan kasir masih kosong.<br>
                                                            <small class="text-muted">Klik produk di sebelah kiri untuk menambah ke struk.</small>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Bottom Grand Total & Submit -->
                                    <div>
                                        <div class="p-3 bg-dark text-white rounded d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <span class="text-white-50 text-uppercase small fw-bold d-block">TOTAL PEMBAYARAN KASIR</span>
                                                <small class="text-warning"><i class="fa fa-check-circle me-1"></i> Pembayaran Tunai / Lunas</small>
                                            </div>
                                            <h2 class="mb-0 text-warning fw-bold" id="cart-total-display">Rp 0</h2>
                                        </div>

                                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold py-3 shadow" id="btn-submit-offline">
                                            <i class="fa fa-check-circle me-2"></i> BAYAR & SIMPAN TRANSAKSI KASIR
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SUB-MODAL POP-UP PILIH VARIASI, UKURAN & QTY (SAAT PRODUK DIKLIK) -->
<div class="modal fade" id="modalPilihVarianOffline" tabindex="-1" aria-labelledby="modalPilihVarianOfflineLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title text-white fw-bold d-flex align-items-center" id="modalPilihVarianOfflineLabel">
                    <i class="fa fa-cart-plus text-warning me-2"></i> Pilih Variasi & Ukuran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                    <img id="submodal-img" src="" class="rounded border shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                    <div>
                        <h5 class="fw-bold text-dark mb-1" id="submodal-title"></h5>
                        <h4 class="text-primary fw-bold mb-1" id="submodal-harga"></h4>
                        <span id="submodal-stok-badge" class="badge"></span>
                    </div>
                </div>

                <!-- Option Box Warna / Varian -->
                <div id="submodal-wrapper-varian" class="mb-3" style="display:none;">
                    <div class="option-label">Warna / Varian: <span class="fw-bold text-primary" id="submodal-label-varian">Pilih Varian</span></div>
                    <div class="option-box-group" id="submodal-boxes-varian"></div>
                </div>

                <!-- Option Box Ukuran -->
                <div id="submodal-wrapper-ukuran" class="mb-3" style="display:none;">
                    <div class="option-label">Ukuran: <span class="fw-bold text-primary" id="submodal-label-ukuran">Pilih Ukuran</span></div>
                    <div class="option-box-group" id="submodal-boxes-ukuran"></div>
                </div>

                <!-- Qty Selector -->
                <div class="row align-items-center g-2 mt-3 pt-3 border-top">
                    <div class="col-4">
                        <label class="form-label small fw-bold mb-0">JUMLAH (QTY):</label>
                    </div>
                    <div class="col-8">
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-secondary px-3" id="submodal-btn-minus"><i class="fa fa-minus"></i></button>
                            <input type="number" id="submodal-qty" class="form-control text-center fw-bold fs-5" value="1" min="1">
                            <button type="button" class="btn btn-outline-secondary px-3" id="submodal-btn-plus"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Subtotal Preview -->
                <div class="d-flex justify-content-between align-items-center mt-3 pt-2">
                    <span class="small text-muted fw-bold">Subtotal Item:</span>
                    <h4 class="fw-bold text-dark mb-0" id="submodal-subtotal-preview">Rp 0</h4>
                </div>
            </div>
            <div class="modal-footer bg-light py-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-lg fw-bold px-4 shadow-sm" id="btn-add-submodal-to-cart">
                    <i class="fa fa-plus-circle me-1"></i> TAMBAHKAN KE STRUK KASIR
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const produksData = @json($produks ?? []);

            function parseMaybeJson(val) {
                if (!val) return [];
                if (Array.isArray(val)) return val;
                if (typeof val === 'string') {
                    try {
                        const parsed = JSON.parse(val);
                        if (Array.isArray(parsed)) return parsed;
                    } catch(e) {
                        return val.split(',').map(s => s.trim()).filter(s => s !== '');
                    }
                }
                return [];
            }

            function parseMaybeJsonObject(val) {
                if (!val) return {};
                if (typeof val === 'object' && val !== null && !Array.isArray(val)) return val;
                if (typeof val === 'string') {
                    try {
                        const parsed = JSON.parse(val);
                        if (typeof parsed === 'object' && parsed !== null) return parsed;
                    } catch(e) {}
                }
                return {};
            }

            $('.datatable').DataTable({
                "pageLength": 25,
                "ordering": false
            });

            let cartItems = [];
            let subSelectedVarianVal = '';
            let subSelectedUkuranVal = '';
            let subCurrentSelectedHarga = 0;
            let subCurrentProdukObj = null;

            // Helper to show/hide modals using Bootstrap 5 API directly to prevent stacking/backdrop bugs
            function showModal(id) {
                const el = document.getElementById(id);
                if (!el) return;
                let instance = bootstrap.Modal.getInstance(el);
                if (!instance) {
                    instance = new bootstrap.Modal(el);
                }
                instance.show();
            }

            function hideModal(id) {
                const el = document.getElementById(id);
                if (!el) return;
                let instance = bootstrap.Modal.getInstance(el);
                if (!instance) {
                    instance = new bootstrap.Modal(el);
                }
                instance.hide();
            }

            // Search filter for product cards
            $('#pos-search-input').on('keyup input', function() {
                const query = $(this).val().toLowerCase().trim();
                $('.pos-card-item').each(function() {
                    const name = $(this).data('name') || '';
                    if (name.includes(query)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Klik kartu produk / tombol pilih -> BUKA SUB-MODAL POP-UP
            $(document).on('click', '.pos-product-card, .btn-pilih-produk', function(e) {
                e.preventDefault();
                e.stopPropagation();

                let prodId = $(this).data('id');
                if (!prodId) {
                    prodId = $(this).closest('.pos-product-card').data('id');
                }
                if (!prodId) return;

                const produk = produksData.find(p => p.id == prodId);
                if (!produk) return;

                subCurrentProdukObj = produk;
                const nama = produk.nama_produk;
                const harga = parseFloat(produk.harga) || 0;
                
                const gambarArr = parseMaybeJson(produk.gambar);
                const gambar = (gambarArr.length > 0) ? `/${gambarArr[0]}` : null;
                
                const varian = parseMaybeJson(produk.varian);
                const stokPerUkuran = parseMaybeJsonObject(produk.stok_per_ukuran);
                const hargaPerUkuran = parseMaybeJsonObject(produk.harga_per_ukuran);
                
                let ukuran = parseMaybeJson(produk.ukuran);
                if (ukuran.length === 0 && Object.keys(stokPerUkuran).length > 0 && !stokPerUkuran.default) {
                    ukuran = Object.keys(stokPerUkuran);
                }

                $('#submodal-title').text(nama);
                $('#submodal-harga').text('Rp ' + harga.toLocaleString('id-ID'));
                $('#submodal-img').attr('src', gambar ? gambar : 'https://via.placeholder.com/150?text=No+Image');

                subSelectedVarianVal = '';
                subSelectedUkuranVal = '';
                subCurrentSelectedHarga = harga;

                $('#submodal-label-varian').text('Pilih Varian');
                $('#submodal-label-ukuran').text('Pilih Ukuran');
                $('#submodal-qty').val(1);

                // Render Option Box Varian
                const wrapperVarian = $('#submodal-wrapper-varian');
                const groupVarian = $('#submodal-boxes-varian');
                groupVarian.empty();
                if (varian.length > 0) {
                    wrapperVarian.show();
                    varian.forEach(v => {
                        const box = $(`<div class="option-box" data-value="${v}"><i class="fa-regular fa-circle text-muted"></i> <span>${v}</span></div>`);
                        box.on('click', function() {
                            groupVarian.find('.option-box').removeClass('active');
                            groupVarian.find('.option-box i').removeClass('fa-solid fa-circle-check text-primary').addClass('fa-regular fa-circle text-muted');
                            
                            $(this).addClass('active');
                            $(this).find('i').removeClass('fa-regular fa-circle text-muted').addClass('fa-solid fa-circle-check text-primary');
                            
                            subSelectedVarianVal = v;
                            $('#submodal-label-varian').text(v);
                            updateSubmodalSubtotal();
                        });
                        groupVarian.append(box);
                    });
                } else {
                    wrapperVarian.hide();
                }

                // Render Option Box Ukuran
                const wrapperUkuran = $('#submodal-wrapper-ukuran');
                const groupUkuran = $('#submodal-boxes-ukuran');
                groupUkuran.empty();

                if (ukuran.length > 0) {
                    wrapperUkuran.show();
                    ukuran.forEach(u => {
                        const stokVal = (stokPerUkuran[u] !== undefined) ? parseInt(stokPerUkuran[u]) : 0;
                        const habis = (stokVal === 0);
                        const boxHtml = habis 
                            ? `<div class="option-box out-of-stock" data-value="${u}"><i class="fa-regular fa-circle text-muted"></i> <span>${u} <small style="font-size:0.65rem;">(Habis)</small></span></div>`
                            : `<div class="option-box" data-value="${u}"><i class="fa-regular fa-circle text-muted"></i> <span>${u}</span></div>`;
                        const box = $(boxHtml);
                        
                        if (!habis) {
                            box.on('click', function() {
                                groupUkuran.find('.option-box').removeClass('active');
                                groupUkuran.find('.option-box i').removeClass('fa-solid fa-circle-check text-primary').addClass('fa-regular fa-circle text-muted');

                                $(this).addClass('active');
                                $(this).find('i').removeClass('fa-regular fa-circle text-muted').addClass('fa-solid fa-circle-check text-primary');
                                
                                subSelectedUkuranVal = u;
                                $('#submodal-label-ukuran').text(u);

                                if (hargaPerUkuran && hargaPerUkuran[u]) {
                                    subCurrentSelectedHarga = parseFloat(hargaPerUkuran[u]);
                                    $('#submodal-harga').text('Rp ' + subCurrentSelectedHarga.toLocaleString('id-ID') + ` (Ukuran ${u})`);
                                } else {
                                    subCurrentSelectedHarga = harga;
                                    $('#submodal-harga').text('Rp ' + harga.toLocaleString('id-ID'));
                                }

                                updateSubmodalStokBadge(stokVal);
                                updateSubmodalSubtotal();
                            });
                        }
                        groupUkuran.append(box);
                    });
                    $('#submodal-stok-badge').text('Pilih ukuran').removeClass().addClass('badge bg-primary mt-1');
                } else {
                    wrapperUkuran.hide();
                    let totalStok = 0;
                    if (Object.keys(stokPerUkuran).length > 0) {
                        totalStok = Object.values(stokPerUkuran).reduce((a, b) => parseInt(a) + parseInt(b), 0);
                    } else {
                        totalStok = (produk.stok ?? 99);
                    }
                    updateSubmodalStokBadge(totalStok);
                }

                updateSubmodalSubtotal();

                // Sembunyikan modal utama dulu sebelum membuka sub-modal pilihan variasi
                hideModal('modalInputOffline');
                $('#modalInputOffline').one('hidden.bs.modal', function () {
                    if (subCurrentProdukObj) {
                        showModal('modalPilihVarianOffline');
                    }
                });
            });

            // Ketika sub-modal ditutup (baik cancel, X, maupun setelah add to cart), buka kembali modal utama
            $('#modalPilihVarianOffline').on('hidden.bs.modal', function () {
                showModal('modalInputOffline');
            });

            function updateSubmodalStokBadge(stok) {
                const badge = $('#submodal-stok-badge');
                if (stok === 0) {
                    badge.text('Stok Habis').removeClass().addClass('badge stok-badge-habis mt-1');
                } else if (stok <= 5) {
                    badge.text('Stok: ' + stok + ' (Terbatas!)').removeClass().addClass('badge stok-badge-terbatas mt-1');
                } else {
                    badge.text('Stok: ' + stok).removeClass().addClass('badge stok-badge-tersedia mt-1');
                }
            }

            $('#submodal-btn-minus').on('click', function() {
                let val = parseInt($('#submodal-qty').val()) || 1;
                if (val > 1) {
                    $('#submodal-qty').val(val - 1);
                    updateSubmodalSubtotal();
                }
            });

            $('#submodal-btn-plus').on('click', function() {
                let val = parseInt($('#submodal-qty').val()) || 1;
                $('#submodal-qty').val(val + 1);
                updateSubmodalSubtotal();
            });

            $('#submodal-qty').on('input change', function() {
                updateSubmodalSubtotal();
            });

            function updateSubmodalSubtotal() {
                const qty = parseInt($('#submodal-qty').val()) || 1;
                const sub = subCurrentSelectedHarga * qty;
                $('#submodal-subtotal-preview').text('Rp ' + sub.toLocaleString('id-ID'));
            }

            // Tambahkan item dari sub-modal ke struk kasir
            $('#btn-add-submodal-to-cart').on('click', function() {
                if (!subCurrentProdukObj) return;

                const varianArr = parseMaybeJson(subCurrentProdukObj.varian);
                let ukuranArr = parseMaybeJson(subCurrentProdukObj.ukuran);
                const stokPerUkuran = parseMaybeJsonObject(subCurrentProdukObj.stok_per_ukuran);
                if (ukuranArr.length === 0 && Object.keys(stokPerUkuran).length > 0 && !stokPerUkuran.default) {
                    ukuranArr = Object.keys(stokPerUkuran);
                }

                if (varianArr.length > 0 && !subSelectedVarianVal) {
                    alert('Silakan pilih Warna / Varian terlebih dahulu!');
                    return;
                }

                if (ukuranArr.length > 0 && !subSelectedUkuranVal) {
                    alert('Silakan pilih Ukuran terlebih dahulu!');
                    return;
                }

                const qty = parseInt($('#submodal-qty').val()) || 1;
                if (qty <= 0) return;

                const gambarArr = parseMaybeJson(subCurrentProdukObj.gambar);
                const gambarPath = (gambarArr.length > 0) ? gambarArr[0] : null;

                const existingIndex = cartItems.findIndex(i => 
                    i.produk_id == subCurrentProdukObj.id && 
                    i.ukuran == subSelectedUkuranVal && 
                    i.varian == subSelectedVarianVal
                );

                if (existingIndex > -1) {
                    cartItems[existingIndex].qty += qty;
                } else {
                    cartItems.push({
                        produk_id: subCurrentProdukObj.id,
                        nama_produk: subCurrentProdukObj.nama_produk,
                        gambar: gambarPath,
                        varian: subSelectedVarianVal,
                        ukuran: subSelectedUkuranVal,
                        harga_satuan: subCurrentSelectedHarga,
                        qty: qty
                    });
                }

                renderCart();

                // Reset selected product
                subCurrentProdukObj = null;
                hideModal('modalPilihVarianOffline');
            });

            function renderCart() {
                const container = $('#cart-container');
                container.empty();

                if (cartItems.length === 0) {
                    container.append(`
                        <tr id="row-empty-cart">
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="fa fa-shopping-basket fs-2 mb-2 d-block opacity-25"></i>
                                Struk belanjaan kasir masih kosong.<br>
                                <small class="text-muted">Klik produk di sebelah kiri untuk menambah ke struk.</small>
                            </td>
                        </tr>
                    `);
                    $('#cart-total-display').text('Rp 0');
                    $('#cart-count-badge').text('0 Item');
                    return;
                }

                let grandTotal = 0;
                let totalItemsCount = 0;

                cartItems.forEach((item, index) => {
                    const subtotal = item.harga_satuan * item.qty;
                    grandTotal += subtotal;
                    totalItemsCount += item.qty;

                    const tr = $(`
                        <tr>
                            <td class="px-2">
                                <strong class="text-dark d-block small" style="white-space: normal; max-width: 140px; word-break: break-word;">${item.nama_produk}</strong>
                                <div class="small">
                                    ${item.ukuran ? `<span class="badge bg-light text-dark border me-1" style="font-size: 0.7rem; padding: 2px 4px;">Uk: ${item.ukuran}</span>` : ''}
                                    ${item.varian ? `<span class="badge bg-light text-dark border me-1" style="font-size: 0.7rem; padding: 2px 4px;">${item.varian}</span>` : ''}
                                </div>
                                <small class="text-muted d-block" style="font-size: 0.75rem;">@ Rp ${item.harga_satuan.toLocaleString('id-ID')}</small>

                                <input type="hidden" name="items[${index}][produk_id]" value="${item.produk_id}">
                                <input type="hidden" name="items[${index}][ukuran]" value="${item.ukuran || ''}">
                                <input type="hidden" name="items[${index}][varian]" value="${item.varian || ''}">
                                <input type="hidden" name="items[${index}][harga_satuan]" value="${item.harga_satuan}">
                            </td>
                            <td class="text-center px-1">
                                <div class="input-group input-group-sm flex-nowrap" style="width: 95px; margin: 0 auto;">
                                    <button type="button" class="btn btn-xs btn-outline-secondary btn-cart-minus px-2 py-1" data-index="${index}"><i class="fa fa-minus" style="font-size: 0.65rem;"></i></button>
                                    <input type="number" name="items[${index}][qty]" class="form-control text-center fw-bold input-cart-qty px-0" data-index="${index}" value="${item.qty}" min="1" style="width: 32px; min-width: 32px; font-size: 0.85rem; height: 28px; padding: 0;">
                                    <button type="button" class="btn btn-xs btn-outline-secondary btn-cart-plus px-2 py-1" data-index="${index}"><i class="fa fa-plus" style="font-size: 0.65rem;"></i></button>
                                </div>
                            </td>
                            <td class="text-end fw-bold text-dark px-2" style="font-size: 0.85rem; width: 90px;">
                                Rp ${subtotal.toLocaleString('id-ID')}
                            </td>
                            <td class="text-center px-1">
                                <button type="button" class="btn btn-sm text-danger btn-remove-cart p-1" data-index="${index}"><i class="fa fa-trash fs-6"></i></button>
                            </td>
                        </tr>
                    `);
                    container.append(tr);
                });

                $('#cart-total-display').text('Rp ' + grandTotal.toLocaleString('id-ID'));
                $('#cart-count-badge').text(totalItemsCount + ' Item');
            }

            $(document).on('click', '.btn-cart-minus', function() {
                const idx = $(this).data('index');
                if (cartItems[idx] && cartItems[idx].qty > 1) {
                    cartItems[idx].qty -= 1;
                    renderCart();
                }
            });

            $(document).on('click', '.btn-cart-plus', function() {
                const idx = $(this).data('index');
                if (cartItems[idx]) {
                    cartItems[idx].qty += 1;
                    renderCart();
                }
            });

            $(document).on('change input', '.input-cart-qty', function() {
                const idx = $(this).data('index');
                const val = parseInt($(this).val()) || 1;
                if (cartItems[idx]) {
                    cartItems[idx].qty = val;
                    renderCart();
                }
            });

            $(document).on('click', '.btn-remove-cart', function() {
                const idx = $(this).data('index');
                cartItems.splice(idx, 1);
                renderCart();
            });

            $('#formPenjualanOffline').on('submit', function(e) {
                if (cartItems.length === 0) {
                    e.preventDefault();
                    alert('Struk kasir masih kosong! Silakan tambahkan minimal 1 produk terlebih dahulu.');
                    return false;
                }
            });
        });
    </script>
@endsection