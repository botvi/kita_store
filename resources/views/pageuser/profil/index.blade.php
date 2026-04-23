@extends('pageuser.layout')

@section('content')
<div class="page-header text-center">
    <div class="container">
        <h1 class="page-title">Profil Saya</h1>
        <p class="text-muted mt-2">Kelola informasi data pribadimu.</p>
    </div>
</div>

<div class="container pb-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-0">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <div class="bg-dark text-white rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h4 class="fw-bold text-uppercase">{{ $user->name }}</h4>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>

                    <form action="{{ route('profil.update') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold text-uppercase" style="font-size: 0.9rem;">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control form-control-lg rounded-0" value="{{ $user->name }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-uppercase" style="font-size: 0.9rem;">Nomor WhatsApp</label>
                            <input type="text" name="no_wa" class="form-control form-control-lg rounded-0" value="{{ $user->no_wa }}" required>
                        </div>
                        
                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-dark btn-lg rounded-0 fw-bold py-3">SIMPAN PERUBAHAN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
