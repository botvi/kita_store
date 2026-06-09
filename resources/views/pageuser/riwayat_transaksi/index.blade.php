@extends('pageuser.layout')

@section('content')
    <div class="page-header text-center pb-3">
        <div class="container">
            <h1 class="page-title">Riwayat Transaksi</h1>
            <p class="text-muted mt-2">Daftar semua pesanan yang pernah kamu lakukan.</p>
        </div>
    </div>

    <div class="container pb-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Custom CSS to differentiate active tab colors -->
                <style>
                    #pesananTab .nav-link {
                        color: #8c98a5 !important;
                        border-bottom: 3px solid transparent !important;
                        transition: all 0.3s ease;
                        letter-spacing: 0.5px;
                    }

                    #pesananTab #unpaid-tab.active {
                        color: #d97706 !important;
                        /* Deep warm amber/orange */
                        border-bottom-color: #d97706 !important;
                        background-color: rgba(217, 119, 6, 0.03);
                    }

                    #pesananTab #paid-tab.active {
                        color: #16a34a !important;
                        /* Rich green */
                        border-bottom-color: #16a34a !important;
                        background-color: rgba(22, 163, 74, 0.03);
                    }

                    #pesananTab .nav-link:hover:not(.active) {
                        color: #495057 !important;
                        background-color: #f8f9fa;
                    }
                </style>

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs nav-fill mb-4 border-0 shadow-sm bg-white" id="pesananTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active py-3 fw-bold border-0" id="unpaid-tab" data-bs-toggle="tab"
                            data-bs-target="#unpaid" type="button" role="tab" aria-controls="unpaid"
                            aria-selected="true">MENUNGGU PEMBAYARAN</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 fw-bold border-0" id="paid-tab" data-bs-toggle="tab"
                            data-bs-target="#paid" type="button" role="tab" aria-controls="paid"
                            aria-selected="false">SELESAI / LUNAS</button>
                    </li>
                </ul>

                <div class="tab-content" id="pesananTabContent">
                    <!-- TAB UNPAID -->
                    <div class="tab-pane fade show active" id="unpaid" role="tabpanel" aria-labelledby="unpaid-tab">
                        @php $unpaidCount = 0; @endphp
                        @foreach ($pesanans as $pesanan)
                            @if ($pesanan->status == 'UNPAID')
                                @php $unpaidCount++; @endphp
                                <div class="card border-0 shadow-sm rounded-0 mb-4">
                                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted">Order ID</small>
                                                <h6 class="fw-bold mb-0">{{ $pesanan->order_id }}</h6>
                                            </div>
                                            <div class="text-end">
                                                <small
                                                    class="text-muted">{{ $pesanan->created_at->format('d M Y, H:i') }}</small>
                                                <div class="mt-1">
                                                    <span class="badge bg-warning text-dark py-2 px-3 rounded-0">BELUM
                                                        BAYAR</span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="card-body px-4 pb-4 pt-2">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h6 class="fw-bold text-uppercase mb-3">Item Pesanan:</h6>
                                                <ul class="list-group list-group-flush mb-3">
                                                    @php
                                                        $details = is_string($pesanan->produk_id)
                                                            ? json_decode($pesanan->produk_id, true)
                                                            : $pesanan->produk_id;
                                                    @endphp
                                                    @if (is_array($details))
                                                        @foreach ($details as $detail)
                                                            <li
                                                                class="list-group-item px-0 py-2 border-0 d-flex justify-content-between">
                                                                <div>
                                                                    <span>{{ $detail['nama_produk'] }} <span
                                                                            class="text-muted">x{{ $detail['qty'] }}</span></span><br>
                                                                    <small class="text-muted">
                                                                        @if (!empty($detail['varian']))
                                                                            Varian: {{ $detail['varian'] }}
                                                                        @endif
                                                                        @if (!empty($detail['ukuran']))
                                                                            | Ukuran: {{ $detail['ukuran'] }}
                                                                        @endif
                                                                        @if (!empty($detail['foto_custom']))
                                                                            <br><a
                                                                                href="{{ asset($detail['foto_custom']) }}"
                                                                                target="_blank"
                                                                                class="badge bg-dark mt-1 text-decoration-none"><i
                                                                                    class="fa fa-image"></i> Lihat Desain
                                                                                Custom</a>
                                                                        @endif
                                                                    </small>
                                                                </div>
                                                                <span class="fw-bold">Rp
                                                                    {{ number_format($detail['subtotal'], 0, ',', '.') }}</span>
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                            <div
                                                class="col-md-4 border-start d-flex flex-column justify-content-center align-items-end text-end">
                                                <span class="text-muted mb-1">Total Tagihan</span>
                                                <h4 class="fw-bold mb-3">Rp
                                                    {{ number_format($pesanan->total_harga, 0, ',', '.') }}</h4>

                                                @if ($pesanan->snap_token)
                                                    <button class="btn btn-dark w-100 rounded-0 fw-bold py-2 btn-pay"
                                                        data-token="{{ $pesanan->snap_token }}">BAYAR SEKARANG</button>
                                                @endif
                                                <button class="btn btn-outline-danger w-100 rounded-0 fw-bold py-2 mt-2 btn-cancel"
                                                    data-id="{{ $pesanan->id }}">BATALKAN PESANAN</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @if ($unpaidCount == 0)
                            <div class="text-center py-5 bg-white shadow-sm mt-2">
                                <i class="fa-solid fa-receipt fs-1 text-muted mb-4"></i>
                                <h4 class="fw-bold text-muted">Tidak ada tagihan tertunda</h4>
                                <p class="text-muted mb-0">Kamu hebat! Semua pembayaranmu sudah lunas.</p>
                            </div>
                        @endif
                    </div>

                    <!-- TAB PAID -->
                    <div class="tab-pane fade" id="paid" role="tabpanel" aria-labelledby="paid-tab">
                        @php $paidCount = 0; @endphp
                        @foreach ($pesanans as $pesanan)
                            @if ($pesanan->status != 'UNPAID')
                                @php $paidCount++; @endphp
                                <div class="card border-0 shadow-sm rounded-0 mb-4 opacity-75">
                                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted">Order ID</small>
                                                <h6 class="fw-bold mb-0">{{ $pesanan->order_id }}</h6>
                                            </div>
                                            <div class="text-end">
                                                <small
                                                    class="text-muted">{{ $pesanan->created_at->format('d M Y, H:i') }}</small>
                                                <div class="mt-1">
                                                    <span class="badge bg-success py-2 px-3 rounded-0">LUNAS</span>
                                                    <span
                                                        class="badge bg-primary py-2 px-3 rounded-0 ms-1">{{ $pesanan->status_pengiriman }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="card-body px-4 pb-4 pt-2">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <h6 class="fw-bold text-uppercase mb-3">Item Pesanan:</h6>
                                                <ul class="list-group list-group-flush mb-0">
                                                    @php
                                                        $details = is_string($pesanan->produk_id)
                                                            ? json_decode($pesanan->produk_id, true)
                                                            : $pesanan->produk_id;
                                                    @endphp
                                                    @if (is_array($details))
                                                        @foreach ($details as $detail)
                                                            <li
                                                                class="list-group-item px-0 py-2 border-0 d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <span>{{ $detail['nama_produk'] }} <span
                                                                            class="text-muted">x{{ $detail['qty'] }}</span></span><br>
                                                                    <small class="text-muted">
                                                                        @if (!empty($detail['varian']))
                                                                            Varian: {{ $detail['varian'] }}
                                                                        @endif
                                                                        @if (!empty($detail['ukuran']))
                                                                            | Ukuran: {{ $detail['ukuran'] }}
                                                                        @endif
                                                                        @if (!empty($detail['foto_custom']))
                                                                            <br><a
                                                                                href="{{ asset($detail['foto_custom']) }}"
                                                                                target="_blank"
                                                                                class="badge bg-dark mt-1 text-decoration-none"><i
                                                                                    class="fa fa-image"></i> Lihat Desain
                                                                                Custom</a>
                                                                        @endif
                                                                    </small>
                                                                </div>
                                                                <div class="text-end">
                                                                    <span class="fw-bold d-block mb-1">Rp
                                                                        {{ number_format($detail['subtotal'], 0, ',', '.') }}</span>
                                                                    @if ($pesanan->status_pengiriman == 'Pesanan Telah Sampai')
                                                                        @php
                                                                            $hasUlasan = isset($ulasans[$pesanan->id . '-' . $detail['id']]);
                                                                        @endphp
                                                                        @if ($hasUlasan)
                                                                            <span class="badge bg-success rounded-0 py-2 px-3"><i class="fa fa-check"></i> SUDAH DIULAS</span>
                                                                        @else
                                                                            <button class="btn btn-dark btn-sm rounded-0 fw-bold px-3 py-1 btn-tulis-ulasan"
                                                                                data-pesanan-id="{{ $pesanan->id }}"
                                                                                data-produk-id="{{ $detail['id'] }}"
                                                                                data-produk-nama="{{ $detail['nama_produk'] }}"
                                                                                data-produk-varian-ukuran="{{ (!empty($detail['varian']) ? 'Varian: ' . $detail['varian'] : '') }}{{ (!empty($detail['ukuran']) ? ' | Ukuran: ' . $detail['ukuran'] : '') }}"
                                                                                data-produk-img="{{ ($produks[$detail['id']] ?? null) && is_array($produks[$detail['id']]->gambar) && count($produks[$detail['id']]->gambar) > 0 ? asset($produks[$detail['id']]->gambar[0]) : '' }}"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#ulasanModal">
                                                                                TULIS ULASAN
                                                                            </button>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                            <div
                                                class="col-md-3 border-start d-flex flex-column justify-content-center align-items-end text-end">
                                                <span class="text-muted mb-1">Total Dibayar</span>
                                                <h5 class="fw-bold mb-0 text-success">Rp
                                                    {{ number_format($pesanan->total_harga, 0, ',', '.') }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @if ($paidCount == 0)
                            <div class="text-center py-5 bg-white shadow-sm mt-2">
                                <i class="fa-solid fa-box-open fs-1 text-muted mb-4"></i>
                                <h4 class="fw-bold text-muted">Belum ada transaksi selesai</h4>
                                <p class="text-muted mb-4">Ayo mulai belanja dan check out item favoritmu.</p>
                                <a href="{{ route('katalog.index') }}"
                                    class="btn btn-dark rounded-0 fw-bold px-4 py-2">MULAI BELANJA</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ulasan -->
    <div class="modal fade" id="ulasanModal" tabindex="-1" aria-labelledby="ulasanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-extrabold text-dark" id="ulasanModalLabel">TULIS ULASAN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('riwayat-pesanan.ulasan') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="pesanan_id" id="modal-pesanan-id">
                        <input type="hidden" name="produk_id" id="modal-produk-id">
                        
                        <div class="text-center mb-4 pb-3 border-bottom">
                            <img id="modal-produk-img" src="" class="img-fluid rounded mb-2 shadow-sm" style="height: 100px; width: 100px; object-fit: cover; display: none;">
                            <h6 class="fw-bold mb-1" id="modal-produk-nama">Nama Produk</h6>
                            <p class="text-muted small mb-0" id="modal-produk-varian-ukuran"></p>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold d-block text-center mb-2">Rating Produk</label>
                            <div class="rating-stars text-center fs-3">
                                <input type="hidden" name="rating" id="modal-rating-val" value="5">
                                <i class="fa-star fa-solid text-warning rating-star-btn px-1" data-value="1" style="cursor: pointer; transition: transform 0.2s;"></i>
                                <i class="fa-star fa-solid text-warning rating-star-btn px-1" data-value="2" style="cursor: pointer; transition: transform 0.2s;"></i>
                                <i class="fa-star fa-solid text-warning rating-star-btn px-1" data-value="3" style="cursor: pointer; transition: transform 0.2s;"></i>
                                <i class="fa-star fa-solid text-warning rating-star-btn px-1" data-value="4" style="cursor: pointer; transition: transform 0.2s;"></i>
                                <i class="fa-star fa-solid text-warning rating-star-btn px-1" data-value="5" style="cursor: pointer; transition: transform 0.2s;"></i>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ulasan Anda</label>
                            <textarea class="form-control rounded-0 border-dark" name="ulasan" rows="4" placeholder="Tuliskan pengalaman Anda menggunakan produk ini..." required style="resize: none;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-dark rounded-0 fw-bold px-4" data-bs-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-dark rounded-0 fw-bold px-4">KIRIM</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Menggunakan library Midtrans dan SweetAlert -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ \App\Models\APIMidtrans::first()->client_key ?? '' }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payBtns = document.querySelectorAll('.btn-pay');
            payBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    let token = this.getAttribute('data-token');
                    snap.pay(token, {
                        onSuccess: function(result) {
                            Swal.fire({
                                title: 'Memproses...',
                                text: 'Mohon tunggu sebentar',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            });

                            fetch("{{ route('transaksi.success_frontend') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    order_id: result.order_id
                                })
                            }).then(() => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pembayaran Berhasil!',
                                    text: 'Terima kasih atas pesanan Anda.',
                                    confirmButtonColor: '#111'
                                }).then(() => {
                                    location.reload();
                                });
                            });
                        },
                        onPending: function(result) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Status Pending',
                                text: 'Silakan selesaikan pembayaran sesuai instruksi.',
                                confirmButtonColor: '#111'
                            });
                        },
                        onError: function(result) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Pembayaran Gagal',
                                text: 'Terjadi kesalahan pada saat pembayaran.',
                                confirmButtonColor: '#111'
                            });
                        },
                        onClose: function() {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Dibatalkan',
                                text: 'Anda menutup popup pembayaran sebelum menyelesaikan transaksi.',
                                confirmButtonColor: '#111'
                            });
                        }
                    });
                });
            });

            const cancelBtns = document.querySelectorAll('.btn-cancel');
            cancelBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    let id = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Pesanan ini akan dibatalkan dan dihapus!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Batalkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Memproses...',
                                text: 'Mohon tunggu sebentar',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            });

                            fetch(`{{ url('riwayat-pesanan/cancel') }}/${id}`, {
                                method: 'POST',
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: data.message,
                                        confirmButtonColor: '#111'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: data.message || 'Terjadi kesalahan.',
                                        confirmButtonColor: '#111'
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Terjadi kesalahan pada sistem.',
                                    confirmButtonColor: '#111'
                                });
                            });
                        }
                    })
                });
            });

            // Handle Modal Ulasan Data & Stars Interaction
            const ulasanModal = document.getElementById('ulasanModal');
            if (ulasanModal) {
                ulasanModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const pesananId = button.getAttribute('data-pesanan-id');
                    const produkId = button.getAttribute('data-produk-id');
                    const produkNama = button.getAttribute('data-produk-nama');
                    const produkVarianUkuran = button.getAttribute('data-produk-varian-ukuran');
                    const produkImg = button.getAttribute('data-produk-img');

                    document.getElementById('modal-pesanan-id').value = pesananId;
                    document.getElementById('modal-produk-id').value = produkId;
                    document.getElementById('modal-produk-nama').textContent = produkNama;
                    document.getElementById('modal-produk-varian-ukuran').textContent = produkVarianUkuran;

                    const imgEl = document.getElementById('modal-produk-img');
                    if (produkImg) {
                        imgEl.src = produkImg;
                        imgEl.style.display = 'inline-block';
                    } else {
                        imgEl.style.display = 'none';
                    }

                    // Reset rating to 5 stars
                    document.getElementById('modal-rating-val').value = 5;
                    const stars = ulasanModal.querySelectorAll('.rating-star-btn');
                    stars.forEach(s => {
                        s.classList.remove('fa-regular');
                        s.classList.add('fa-solid');
                    });
                    
                    // Reset textarea
                    ulasanModal.querySelector('textarea').value = '';
                });

                // Stars Hover & Click Handling
                const stars = ulasanModal.querySelectorAll('.rating-star-btn');
                const ratingInput = document.getElementById('modal-rating-val');

                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        const val = parseInt(this.getAttribute('data-value'));
                        ratingInput.value = val;
                        
                        stars.forEach(s => {
                            const sVal = parseInt(s.getAttribute('data-value'));
                            if (sVal <= val) {
                                s.classList.remove('fa-regular');
                                s.classList.add('fa-solid');
                            } else {
                                s.classList.remove('fa-solid');
                                s.classList.add('fa-regular');
                            }
                        });
                    });

                    // Add small scale effect on hover
                    star.addEventListener('mouseenter', function() {
                        this.style.transform = 'scale(1.2)';
                    });
                    star.addEventListener('mouseleave', function() {
                        this.style.transform = 'scale(1)';
                    });
                });
            }
        });
    </script>
@endsection
