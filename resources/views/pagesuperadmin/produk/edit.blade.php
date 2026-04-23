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
                <li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Produk</a></li>
                <li class="breadcrumb-item" aria-current="page">Form Edit Produk</li>
              </ul>
            </div>
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Form Edit Produk</h2>
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
          <!-- Basic Inputs -->
          <div class="card">
            <div class="card-header">
              <h5>Form Edit Produk</h5>
            </div>
            <div class="card-body">
              <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                  <label class="form-label">Kategori Produk</label>
                  <select name="kategori_produk_id" class="form-control" required>
                      <option value="">Pilih Kategori</option>
                      @foreach($kategoris as $kategori)
                          <option value="{{ $kategori->id }}" {{ $produk->kategori_produk_id == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                      @endforeach
                  </select>
                </div>
                <div class="form-group mb-3">
                  <label class="form-label">Nama Produk</label>
                  <input type="text" name="nama_produk" class="form-control" value="{{ $produk->nama_produk }}" required>
                </div>
                <div class="form-group mb-3">
                  <label class="form-label">Deskripsi</label>
                  <textarea name="deskripsi" class="form-control" rows="4" required>{{ $produk->deskripsi }}</textarea>
                </div>
                <div class="form-group mb-3">
                  <label class="form-label">Harga (Rp)</label>
                  <input type="number" name="harga" class="form-control" value="{{ $produk->harga }}" required>
                </div>
                <div class="form-group mb-3">
                  <label class="form-label">Harga Custom (Opsional)</label>
                  <input type="number" name="harga_custom" class="form-control" value="{{ $produk->harga_custom }}">
                </div>
                <div class="form-group mb-3">
                  <label class="form-label">Stok</label>
                  <input type="number" name="stok" class="form-control" value="{{ $produk->stok }}">
                </div>
                <div class="form-group mb-3">
                  <label class="form-label">Gambar Produk Baru</label>
                  <input type="file" name="gambar[]" class="form-control" multiple accept="image/*">
                  <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                  
                  @if($produk->gambar && is_array($produk->gambar))
                    <div class="mt-3">
                        <p class="mb-2">Gambar saat ini:</p>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($produk->gambar as $gbr)
                                <img src="{{ asset($gbr) }}" alt="gambar produk" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                            @endforeach
                        </div>
                    </div>
                  @endif
                </div>

                <div class="form-group mb-3">
                  <label class="form-label">Varian</label>
                  <div id="varian-container">
                    @if(is_array($produk->varian) && count($produk->varian) > 0)
                        @foreach($produk->varian as $varian)
                        <div class="input-group mb-2">
                          <input type="text" name="varian[]" class="form-control" value="{{ $varian }}" placeholder="Contoh: Merah, Hijau">
                          <button type="button" class="btn btn-danger remove-varian">Hapus</button>
                        </div>
                        @endforeach
                    @else
                        <div class="input-group mb-2">
                          <input type="text" name="varian[]" class="form-control" placeholder="Contoh: Merah, Hijau">
                          <button type="button" class="btn btn-danger remove-varian">Hapus</button>
                        </div>
                    @endif
                  </div>
                  <button type="button" class="btn btn-sm btn-success" id="add-varian">Tambah Varian</button>
                </div>

                <div class="form-group mb-3">
                  <label class="form-label">Ukuran</label>
                  <div id="ukuran-container">
                    @if(is_array($produk->ukuran) && count($produk->ukuran) > 0)
                        @foreach($produk->ukuran as $ukuran)
                        <div class="input-group mb-2">
                          <input type="text" name="ukuran[]" class="form-control" value="{{ $ukuran }}" placeholder="Contoh: S, M, L, XL">
                          <button type="button" class="btn btn-danger remove-ukuran">Hapus</button>
                        </div>
                        @endforeach
                    @else
                        <div class="input-group mb-2">
                          <input type="text" name="ukuran[]" class="form-control" placeholder="Contoh: S, M, L, XL">
                          <button type="button" class="btn btn-danger remove-ukuran">Hapus</button>
                        </div>
                    @endif
                  </div>
                  <button type="button" class="btn btn-sm btn-success" id="add-ukuran">Tambah Ukuran</button>
                </div>
                
                <div class="form-group mb-4">
                  <div class="form-check form-switch pt-2">
                    <input class="form-check-input" type="checkbox" name="is_custom" value="1" id="customCheck" {{ $produk->is_custom ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="customCheck">Izinkan Opsi Upload Desain Custom oleh Pembeli?</label>
                  </div>
                </div>

                <div class="card-footer p-0 mt-3 pt-3 border-top">
                  <button type="submit" class="btn btn-primary me-2">Submit</button>
                  <a href="{{ route('produk.index') }}" class="btn btn-light">Kembali</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script>
    document.getElementById('add-varian').addEventListener('click', function() {
        let container = document.getElementById('varian-container');
        let div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = '<input type="text" name="varian[]" class="form-control" placeholder="Contoh: Merah, Hijau"><button type="button" class="btn btn-danger remove-varian">Hapus</button>';
        container.appendChild(div);
    });

    document.getElementById('add-ukuran').addEventListener('click', function() {
        let container = document.getElementById('ukuran-container');
        let div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = '<input type="text" name="ukuran[]" class="form-control" placeholder="Contoh: S, M, L, XL"><button type="button" class="btn btn-danger remove-ukuran">Hapus</button>';
        container.appendChild(div);
    });

    document.addEventListener('click', function(e) {
        if(e.target && e.target.classList.contains('remove-varian')) {
            e.target.parentElement.remove();
        }
        if(e.target && e.target.classList.contains('remove-ukuran')) {
            e.target.parentElement.remove();
        }
    });
  </script>
@endsection