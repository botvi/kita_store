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
                <li class="breadcrumb-item"><a href="javascript: void(0)">Kategori Produk</a></li>
                <li class="breadcrumb-item" aria-current="page">Form Edit Kategori Produk</li>
              </ul>
            </div>
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Form Edit Kategori Produk</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- [ breadcrumb ] end -->

      <!-- [ Main Content ] start -->
      <div class="row justify-content-center">
        <!-- [ form-element ] start -->
        <div class="col-sm-6">
          <!-- Basic Inputs -->
          <div class="card">
            <div class="card-header">
              <h5>Form Edit Kategori Produk</h5>
            </div>
            <div class="card-body">
              <form action="{{ route('kategori-produk.update', $kategoriproduk->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                  <label class="form-label">Nama Kategori</label>
                  <input type="text" name="nama_kategori" class="form-control" value="{{ $kategoriproduk->nama_kategori }}" required>
                </div>
                <div class="card-footer p-0 mt-3">
                  <button type="submit" class="btn btn-primary me-2">Submit</button>
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