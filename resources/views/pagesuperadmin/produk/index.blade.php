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
                                <li class="breadcrumb-item"><a href="javascript: void(0)">Produk</a></li>
                                <li class="breadcrumb-item" aria-current="page">Tabel Produk</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Tabel Produk</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- Zero config table start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Tabel Produk</h5>
                            <a href="{{ route('produk.create') }}" class="btn btn-primary">Tambah Produk</a>
                        </div>
                        <div class="card-body">
                            <div class="dt-responsive table-responsive">
                                <table id="simpletable" class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Gambar</th>
                                            <th>Kategori</th>
                                            <th>Nama Produk</th>
                                            <th>Varian</th>
                                            <th>Ukuran</th>
                                            <th>Harga</th>
                                            <th>Stok Per Ukuran</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($produks as $e => $item)
                                            <tr>
                                                <td>{{ $e + 1 }}</td>
                                                <td>
                                                    @if ($item->gambar && is_array($item->gambar) && count($item->gambar) > 0)
                                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                            data-bs-target="#modalGambar{{ $item->id }}">
                                                            <img src="{{ asset($item->gambar[0]) }}" alt="gambar"
                                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $item->kategori_produk ? $item->kategori_produk->nama_kategori : '-' }}
                                                </td>
                                                <td>{{ $item->nama_produk }}</td>
                                                <td>
                                                    @if(is_array($item->varian) && count($item->varian) > 0)
                                                        {{ implode(', ', $item->varian) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(is_array($item->ukuran) && count($item->ukuran) > 0)
                                                        {{ implode(', ', $item->ukuran) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                                <td>
                                                    @php $stokData = $item->stok_per_ukuran; @endphp
                                                    @if(is_array($stokData) && count($stokData) > 0)
                                                        @foreach($stokData as $uk => $stokVal)
                                                            <span class="badge {{ $stokVal > 0 ? 'bg-success' : 'bg-danger' }} me-1 mb-1">
                                                                {{ $uk === 'default' ? 'Stok' : $uk }}: {{ $stokVal }}
                                                            </span>
                                                        @endforeach
                                                        <br><small class="text-muted">Total: {{ $item->getTotalStok() }}</small>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $item->stok ?? '-' }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('produk.edit', $item->id) }}"
                                                        class="btn btn-sm btn-warning">Edit</a>
                                                    <form action="{{ route('produk.destroy', $item->id) }}" method="POST"
                                                        style="display:inline;" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Zero config table end -->
            </div>
        </div>

        <!-- Modals for Gambar -->
        @foreach ($produks as $item)
            @if ($item->gambar && is_array($item->gambar) && count($item->gambar) > 0)
                <div class="modal fade" id="modalGambar{{ $item->id }}" tabindex="-1"
                    aria-labelledby="modalGambarLabel{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalGambarLabel{{ $item->id }}">Gambar Produk:
                                    {{ $item->nama_produk }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex flex-wrap gap-3 justify-content-center">
                                    @foreach ($item->gambar as $gbr)
                                        <img src="{{ asset($gbr) }}" class="img-fluid rounded border"
                                            alt="Gambar {{ $item->nama_produk }}"
                                            style="max-height: 200px; object-fit: cover;">
                                    @endforeach
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </section>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data ini akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#simpletable').DataTable();
        });
    </script>
@endsection
