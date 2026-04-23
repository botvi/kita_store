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
                                <li class="breadcrumb-item"><a href="javascript: void(0)">Konfigurasi</a></li>
                                <li class="breadcrumb-item" aria-current="page">API Midtrans</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Konfigurasi API Midtrans</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row justify-content-center">
                <!-- [ form-element ] start -->
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ $api ? 'Update Konfigurasi API Midtrans' : 'Konfigurasi API Midtrans Baru' }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ $api ? route('api-midtrans.update', $api->id) : route('api-midtrans.store') }}"
                                method="POST">
                                @csrf
                                @if ($api)
                                    @method('PUT')
                                @endif

                                <div class="form-group mb-3">
                                    <label class="form-label">Merchant ID</label>
                                    <input type="text" name="merchant_id" class="form-control"
                                        value="{{ $api->merchant_id ?? '' }}" placeholder="G-123456789" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Client Key</label>
                                    <input type="text" name="client_key" class="form-control"
                                        value="{{ $api->client_key ?? '' }}" placeholder="SB-Mid-client-XXXXX" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Server Key</label>
                                    <input type="text" name="server_key" class="form-control"
                                        value="{{ $api->server_key ?? '' }}" placeholder="SB-Mid-server-XXXXX" required>
                                </div>

                                <div class="card-footer p-0 mt-4 pt-3">
                                    <button type="submit" class="btn btn-primary me-2">Simpan Konfigurasi</button>
                                    <button type="reset" class="btn btn-light">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
