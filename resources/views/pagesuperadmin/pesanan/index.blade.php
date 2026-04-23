@extends('template-admin.layout')

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
                                <li class="breadcrumb-item" aria-current="page">Pesanan</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Kelola Pesanan</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-pills mb-4 d-flex" id="pesananTab" role="tablist">
                                <li class="nav-item flex-fill text-center" role="presentation">
                                    <button class="nav-link w-100 active fw-bold" id="unpaid-tab" data-bs-toggle="tab" data-bs-target="#unpaid" type="button" role="tab" aria-controls="unpaid" aria-selected="true"><i class="fa-solid fa-clock opacity-50 me-1"></i> BELUM BAYAR</button>
                                </li>
                                <li class="nav-item flex-fill text-center" role="presentation">
                                    <button class="nav-link w-100 fw-bold border" id="proses-tab" data-bs-toggle="tab" data-bs-target="#proses" type="button" role="tab" aria-controls="proses" aria-selected="false"><i class="fa-solid fa-box opacity-50 me-1"></i> DIPROSES</button>
                                </li>
                                <li class="nav-item flex-fill text-center" role="presentation">
                                    <button class="nav-link w-100 fw-bold border" id="diantar-tab" data-bs-toggle="tab" data-bs-target="#diantar" type="button" role="tab" aria-controls="diantar" aria-selected="false"><i class="fa-solid fa-truck opacity-50 me-1"></i> DIANTAR</button>
                                </li>
                                <li class="nav-item flex-fill text-center" role="presentation">
                                    <button class="nav-link w-100 fw-bold border" id="sampai-tab" data-bs-toggle="tab" data-bs-target="#sampai" type="button" role="tab" aria-controls="sampai" aria-selected="false"><i class="fa-solid fa-check opacity-50 me-1"></i> SELESAI</button>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content" id="pesananTabContent">
                                <!-- UNPAID -->
                                <div class="tab-pane fade show active" id="unpaid" role="tabpanel" aria-labelledby="unpaid-tab">
                                    @include('pagesuperadmin.pesanan.partials.table', ['status_filter' => 'UNPAID', 'pengiriman_filter' => null])
                                </div>
                                <!-- PROSES -->
                                <div class="tab-pane fade" id="proses" role="tabpanel" aria-labelledby="proses-tab">
                                    @include('pagesuperadmin.pesanan.partials.table', ['status_filter' => 'PAID', 'pengiriman_filter' => 'Pesanan Sedang Diproses'])
                                </div>
                                <!-- DIANTAR -->
                                <div class="tab-pane fade" id="diantar" role="tabpanel" aria-labelledby="diantar-tab">
                                    @include('pagesuperadmin.pesanan.partials.table', ['status_filter' => 'PAID', 'pengiriman_filter' => 'Pesanan Diantar'])
                                </div>
                                <!-- SAMPAI -->
                                <div class="tab-pane fade" id="sampai" role="tabpanel" aria-labelledby="sampai-tab">
                                    @include('pagesuperadmin.pesanan.partials.table', ['status_filter' => 'PAID', 'pengiriman_filter' => 'Pesanan Telah Sampai'])
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable();
        });
    </script>
@endsection
