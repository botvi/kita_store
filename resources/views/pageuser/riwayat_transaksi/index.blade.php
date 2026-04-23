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
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs nav-fill mb-4 border-0 shadow-sm bg-white" id="pesananTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active py-3 text-dark fw-bold border-0" style="border-bottom: 3px solid transparent;" id="unpaid-tab" data-bs-toggle="tab" data-bs-target="#unpaid" type="button" role="tab" aria-controls="unpaid" aria-selected="true" onclick="this.style.borderBottomColor='#111'; document.getElementById('paid-tab').style.borderBottomColor='transparent';">MENUNGGU PEMBAYARAN</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 text-dark fw-bold border-0" style="border-bottom: 3px solid transparent;" id="paid-tab" data-bs-toggle="tab" data-bs-target="#paid" type="button" role="tab" aria-controls="paid" aria-selected="false" onclick="this.style.borderBottomColor='#111'; document.getElementById('unpaid-tab').style.borderBottomColor='transparent';">SELESAI / LUNAS</button>
                </li>
            </ul>

            <script>
                // Set initial border color
                document.getElementById('unpaid-tab').style.borderBottomColor = '#111';
            </script>

            <div class="tab-content" id="pesananTabContent">
                <!-- TAB UNPAID -->
                <div class="tab-pane fade show active" id="unpaid" role="tabpanel" aria-labelledby="unpaid-tab">
                    @php $unpaidCount = 0; @endphp
                    @foreach($pesanans as $pesanan)
                        @if($pesanan->status == 'UNPAID')
                            @php $unpaidCount++; @endphp
                            <div class="card border-0 shadow-sm rounded-0 mb-4">
                                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">Order ID</small>
                                            <h6 class="fw-bold mb-0">{{ $pesanan->order_id }}</h6>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted">{{ $pesanan->created_at->format('d M Y, H:i') }}</small>
                                            <div class="mt-1">
                                                <span class="badge bg-warning text-dark py-2 px-3 rounded-0">BELUM BAYAR</span>
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
                                                    $details = is_string($pesanan->produk_id) ? json_decode($pesanan->produk_id, true) : $pesanan->produk_id;
                                                @endphp
                                                @if(is_array($details))
                                                    @foreach($details as $detail)
                                                    <li class="list-group-item px-0 py-2 border-0 d-flex justify-content-between">
                                                        <div>
                                                            <span>{{ $detail['nama_produk'] }} <span class="text-muted">x{{ $detail['qty'] }}</span></span><br>
                                                            <small class="text-muted">
                                                                @if(!empty($detail['varian'])) Varian: {{ $detail['varian'] }} @endif
                                                                @if(!empty($detail['ukuran'])) | Ukuran: {{ $detail['ukuran'] }} @endif
                                                                @if(!empty($detail['foto_custom']))
                                                                    <br><a href="{{ asset($detail['foto_custom']) }}" target="_blank" class="badge bg-dark mt-1 text-decoration-none"><i class="fa fa-image"></i> Lihat Desain Custom</a>
                                                                @endif
                                                            </small>
                                                        </div>
                                                        <span class="fw-bold">Rp {{ number_format($detail['subtotal'], 0, ',', '.') }}</span>
                                                    </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="col-md-4 border-start d-flex flex-column justify-content-center align-items-end text-end">
                                            <span class="text-muted mb-1">Total Tagihan</span>
                                            <h4 class="fw-bold mb-3">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</h4>
                                            
                                            @if($pesanan->snap_token)
                                                <button class="btn btn-dark w-100 rounded-0 fw-bold py-2 btn-pay" data-token="{{ $pesanan->snap_token }}">BAYAR SEKARANG</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if($unpaidCount == 0)
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
                    @foreach($pesanans as $pesanan)
                        @if($pesanan->status != 'UNPAID')
                            @php $paidCount++; @endphp
                            <div class="card border-0 shadow-sm rounded-0 mb-4 opacity-75">
                                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">Order ID</small>
                                            <h6 class="fw-bold mb-0">{{ $pesanan->order_id }}</h6>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted">{{ $pesanan->created_at->format('d M Y, H:i') }}</small>
                                            <div class="mt-1">
                                                <span class="badge bg-success py-2 px-3 rounded-0">LUNAS</span>
                                                <span class="badge bg-primary py-2 px-3 rounded-0 ms-1">{{ $pesanan->status_pengiriman }}</span>
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
                                                    $details = is_string($pesanan->produk_id) ? json_decode($pesanan->produk_id, true) : $pesanan->produk_id;
                                                @endphp
                                                @if(is_array($details))
                                                    @foreach($details as $detail)
                                                    <li class="list-group-item px-0 py-2 border-0 d-flex justify-content-between">
                                                        <div>
                                                            <span>{{ $detail['nama_produk'] }} <span class="text-muted">x{{ $detail['qty'] }}</span></span><br>
                                                            <small class="text-muted">
                                                                @if(!empty($detail['varian'])) Varian: {{ $detail['varian'] }} @endif
                                                                @if(!empty($detail['ukuran'])) | Ukuran: {{ $detail['ukuran'] }} @endif
                                                                @if(!empty($detail['foto_custom']))
                                                                    <br><a href="{{ asset($detail['foto_custom']) }}" target="_blank" class="badge bg-dark mt-1 text-decoration-none"><i class="fa fa-image"></i> Lihat Desain Custom</a>
                                                                @endif
                                                            </small>
                                                        </div>
                                                        <span class="fw-bold">Rp {{ number_format($detail['subtotal'], 0, ',', '.') }}</span>
                                                    </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="col-md-3 border-start d-flex flex-column justify-content-center align-items-end text-end">
                                            <span class="text-muted mb-1">Total Dibayar</span>
                                            <h5 class="fw-bold mb-0 text-success">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if($paidCount == 0)
                        <div class="text-center py-5 bg-white shadow-sm mt-2">
                            <i class="fa-solid fa-box-open fs-1 text-muted mb-4"></i>
                            <h4 class="fw-bold text-muted">Belum ada transaksi selesai</h4>
                            <p class="text-muted mb-4">Ayo mulai belanja dan check out item favoritmu.</p>
                            <a href="{{ route('katalog.index') }}" class="btn btn-dark rounded-0 fw-bold px-4 py-2">MULAI BELANJA</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Menggunakan library Midtrans dan SweetAlert -->
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ \App\Models\APIMidtrans::first()->client_key ?? '' }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        const payBtns = document.querySelectorAll('.btn-pay');
        payBtns.forEach(btn => {
            btn.addEventListener('click', function(){
                let token = this.getAttribute('data-token');
                snap.pay(token, {
                    onSuccess: function(result){ 
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading() }
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
                    onPending: function(result){ 
                        Swal.fire({
                            icon: 'info',
                            title: 'Status Pending',
                            text: 'Silakan selesaikan pembayaran sesuai instruksi.',
                            confirmButtonColor: '#111'
                        });
                    },
                    onError: function(result){ 
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
    });
</script>
@endsection
