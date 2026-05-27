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
        <div class="col-sm-8">
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

                <!-- ===== VARIAN ===== -->
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
                  <button type="button" class="btn btn-sm btn-success" id="add-varian">+ Tambah Varian</button>
                </div>

                <!-- ===== UKURAN ===== -->
                <div class="form-group mb-3">
                  <label class="form-label">Ukuran</label>
                  <div id="ukuran-container">
                    @if(is_array($produk->ukuran) && count($produk->ukuran) > 0)
                        @foreach($produk->ukuran as $ukuran)
                        <div class="ukuran-row input-group mb-2">
                          <input type="text" name="ukuran[]" class="form-control ukuran-input" value="{{ $ukuran }}" placeholder="Contoh: S, M, L, XL">
                          <button type="button" class="btn btn-danger remove-ukuran">Hapus</button>
                        </div>
                        @endforeach
                    @else
                        <div class="ukuran-row input-group mb-2">
                          <input type="text" name="ukuran[]" class="form-control ukuran-input" placeholder="Contoh: S, M, L, XL">
                          <button type="button" class="btn btn-danger remove-ukuran">Hapus</button>
                        </div>
                    @endif
                  </div>
                  <button type="button" class="btn btn-sm btn-success" id="add-ukuran">+ Tambah Ukuran</button>
                </div>

                <!-- ===== TABEL STOK PER UKURAN ===== -->
                <div class="form-group mb-3" id="stok-section">
                  <label class="form-label fw-bold">Stok Per Ukuran</label>
                  <div class="alert alert-info py-2 px-3 small mb-2" id="stok-info-no-ukuran" style="display:none;">
                    <i class="ti ti-info-circle me-1"></i> Tidak ada ukuran — masukkan stok total produk.
                  </div>
                  <div id="stok-table-wrapper">
                    <table class="table table-sm table-bordered" id="stok-table">
                      <thead class="table-light">
                        <tr>
                          <th>Ukuran</th>
                          <th style="width:160px">Stok</th>
                        </tr>
                      </thead>
                      <tbody id="stok-tbody">
                        <!-- Baris default (jika tidak ada ukuran) -->
                        <tr id="stok-default-row" style="display:none;">
                          <td class="align-middle text-muted fst-italic">Default (tanpa ukuran)</td>
                          <td>
                            @php $defaultStok = (is_array($produk->stok_per_ukuran) && isset($produk->stok_per_ukuran['default'])) ? $produk->stok_per_ukuran['default'] : ($produk->stok ?? 0); @endphp
                            <input type="number" name="stok_ukuran[default]" class="form-control form-control-sm" value="{{ $defaultStok }}" min="0">
                          </td>
                        </tr>
                        <!-- Baris per ukuran (pre-filled dari data existing) -->
                        @if(is_array($produk->ukuran) && count($produk->ukuran) > 0)
                          @foreach($produk->ukuran as $idx => $ukuran)
                          <tr class="stok-ukuran-row" data-ukuran="{{ $ukuran }}">
                            <td class="align-middle fw-semibold">{{ $ukuran }}</td>
                            <td>
                              @php $stokVal = (is_array($produk->stok_per_ukuran) && isset($produk->stok_per_ukuran[$ukuran])) ? $produk->stok_per_ukuran[$ukuran] : 0; @endphp
                              <input type="number" name="stok_ukuran[{{ $idx }}]" class="form-control form-control-sm" value="{{ $stokVal }}" min="0">
                            </td>
                          </tr>
                          @endforeach
                        @endif
                      </tbody>
                    </table>
                  </div>
                </div>

                <div class="form-group mb-4">
                  <div class="form-check form-switch pt-2">
                    <input class="form-check-input" type="checkbox" name="is_custom" value="1" id="customCheck" {{ $produk->is_custom ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="customCheck">Izinkan Opsi Upload Desain Custom oleh Pembeli?</label>
                  </div>
                </div>

                <div class="card-footer p-0 mt-3 pt-3 border-top">
                  <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
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
    // Existing stok data dari PHP untuk referensi
    const existingStokData = @json($produk->stok_per_ukuran ?? []);

    // =========== VARIAN ===========
    document.getElementById('add-varian').addEventListener('click', function () {
        const container = document.getElementById('varian-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = '<input type="text" name="varian[]" class="form-control" placeholder="Contoh: Merah, Hijau"><button type="button" class="btn btn-danger remove-varian">Hapus</button>';
        container.appendChild(div);
    });

    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-varian')) {
            e.target.parentElement.remove();
        }
    });

    // =========== UKURAN + STOK DINAMIS ===========
    function rebuildStokTable() {
        const ukuranInputs = document.querySelectorAll('.ukuran-input');
        const tbody = document.getElementById('stok-tbody');
        const defaultRow = document.getElementById('stok-default-row');
        const infoNoUkuran = document.getElementById('stok-info-no-ukuran');

        const ukuranValues = Array.from(ukuranInputs).map(el => el.value.trim()).filter(v => v !== '');

        // Hapus baris dinamis yang sudah ada
        Array.from(tbody.querySelectorAll('tr.stok-ukuran-row')).forEach(r => r.remove());

        if (ukuranValues.length > 0) {
            defaultRow.style.display = 'none';
            infoNoUkuran.style.display = 'none';

            ukuranValues.forEach(function (ukuran, idx) {
                const stokVal = existingStokData[ukuran] ?? 0;
                const tr = document.createElement('tr');
                tr.className = 'stok-ukuran-row';
                tr.dataset.ukuran = ukuran;
                tr.innerHTML = `<td class="align-middle fw-semibold">${ukuran}</td>
                    <td><input type="number" name="stok_ukuran[${idx}]" class="form-control form-control-sm" value="${stokVal}" min="0"></td>`;
                tbody.appendChild(tr);
            });
        } else {
            defaultRow.style.display = '';
            infoNoUkuran.style.display = '';
        }
    }

    // Tambah ukuran baru
    document.getElementById('add-ukuran').addEventListener('click', function () {
        const container = document.getElementById('ukuran-container');
        const div = document.createElement('div');
        div.className = 'ukuran-row input-group mb-2';
        div.innerHTML = '<input type="text" name="ukuran[]" class="form-control ukuran-input" placeholder="Contoh: S, M, L, XL"><button type="button" class="btn btn-danger remove-ukuran">Hapus</button>';
        container.appendChild(div);
        div.querySelector('.ukuran-input').addEventListener('input', rebuildStokTable);
        rebuildStokTable();
    });

    // Hapus baris ukuran
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-ukuran')) {
            e.target.parentElement.remove();
            rebuildStokTable();
        }
    });

    // Input listener ke semua ukuran yang sudah ada
    document.querySelectorAll('.ukuran-input').forEach(function (el) {
        el.addEventListener('input', rebuildStokTable);
    });

    // Inisialisasi saat page load
    // Cek apakah produk punya ukuran atau tidak
    const hasUkuran = {{ (is_array($produk->ukuran) && count($produk->ukuran) > 0) ? 'true' : 'false' }};
    if (!hasUkuran) {
        document.getElementById('stok-default-row').style.display = '';
        document.getElementById('stok-info-no-ukuran').style.display = '';
    }
  </script>
@endsection