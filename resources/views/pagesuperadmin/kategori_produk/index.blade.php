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
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Master Data</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Kategori Produk</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Manajemen Kategori Produk</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Stats Row ] -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm"
                        style="border-radius:14px; background: linear-gradient(135deg,#6c63ff,#48cfad);">
                        <div class="card-body d-flex align-items-center gap-3 py-3">
                            <div
                                style="width:52px;height:52px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                <i class="ti ti-category text-white" style="font-size:1.6rem;"></i>
                            </div>
                            <div>
                                <div class="text-white fw-bold" style="font-size:1.6rem;line-height:1;">
                                    {{ $kategori_produks->count() }}</div>
                                <div class="text-white" style="opacity:.85;font-size:.85rem;">Total Kategori</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- [ Main Content ] -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card border-0 shadow-sm" style="border-radius:16px;">

                        <!-- Card Header -->
                        <div class="card-header border-0 pb-0 pt-4 px-4" style="background:transparent;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1 fw-bold">Daftar Kategori Produk</h5>
                                    <p class="text-muted mb-0" style="font-size:.85rem;">
                                        <i class="ti ti-info-circle me-1"></i>Kelola kategori untuk pengelompokan produk.
                                    </p>
                                </div>
                                <a href="{{ route('kategori-produk.create') }}"
                                    class="btn btn-primary d-flex align-items-center gap-2 px-4"
                                    style="border-radius:10px;">
                                    <i class="ti ti-plus"></i> Tambah Kategori
                                </a>
                            </div>
                        </div>

                        <div class="card-body px-4">
                            <div class="table-responsive">
                                <table id="simpletable" class="table align-middle"
                                    style="border-collapse:separate;border-spacing:0 6px;">
                                    <thead>
                                        <tr style="background:transparent;">
                                            <th class="text-muted fw-semibold"
                                                style="font-size:.8rem;letter-spacing:.05em;text-transform:uppercase;border:none;padding-bottom:4px;width:60px;">
                                                No</th>
                                            <th class="text-muted fw-semibold"
                                                style="font-size:.8rem;letter-spacing:.05em;text-transform:uppercase;border:none;padding-bottom:4px;">
                                                Nama Kategori</th>
                                            <th class="text-muted fw-semibold"
                                                style="font-size:.8rem;letter-spacing:.05em;text-transform:uppercase;border:none;padding-bottom:4px;width:160px;text-align:center;">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kategori_produks as $e => $item)
                                            <tr class="kategori-row" style="transition:box-shadow .15s;">
                                                <td
                                                    style="border-top:1px solid #f0f0f0;border-bottom:1px solid #f0f0f0;border-left:1px solid #f0f0f0;border-radius:10px 0 0 10px;padding:14px 16px;">
                                                    <span class="badge bg-light text-muted fw-bold"
                                                        style="font-size:.8rem;min-width:28px;">{{ $e + 1 }}</span>
                                                </td>
                                                <td
                                                    style="border-top:1px solid #f0f0f0;border-bottom:1px solid #f0f0f0;padding:14px 16px;">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div
                                                            style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#6c63ff22,#48cfad22);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                                            <i class="ti ti-tag"
                                                                style="color:#6c63ff;font-size:1.1rem;"></i>
                                                        </div>
                                                        <div>
                                                            <span class="fw-semibold"
                                                                style="font-size:.95rem;">{{ $item->nama_kategori }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td
                                                    style="border-top:1px solid #f0f0f0;border-bottom:1px solid #f0f0f0;border-right:1px solid #f0f0f0;border-radius:0 10px 10px 0;padding:14px 16px;text-align:center;">
                                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                                        <a href="{{ route('kategori-produk.edit', $item->id) }}"
                                                            class="btn btn-sm d-flex align-items-center gap-1"
                                                            style="border-radius:8px;background:#fff8e1;color:#f59e0b;border:1px solid #fde68a;padding:5px 12px;font-weight:600;font-size:.82rem;">
                                                            <i class="ti ti-pencil" style="font-size:.9rem;"></i> Edit
                                                        </a>
                                                        <form action="{{ route('kategori-produk.destroy', $item->id) }}"
                                                            method="POST" style="display:inline;" class="delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-sm d-flex align-items-center gap-1"
                                                                style="border-radius:8px;background:#fef2f2;color:#ef4444;border:1px solid #fecaca;padding:5px 12px;font-weight:600;font-size:.82rem;">
                                                                <i class="ti ti-trash" style="font-size:.9rem;"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-5">
                                                    <div style="opacity:.4;">
                                                        <i class="ti ti-folder-off"
                                                            style="font-size:3rem;display:block;margin-bottom:8px;"></i>
                                                        <span class="fw-semibold">Belum ada kategori produk</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>

    <style>
        .kategori-row:hover td {
            background: #fafbff !important;
            box-shadow: 0 2px 12px rgba(108, 99, 255, .07);
        }
    </style>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus Kategori?',
                        text: "Kategori ini akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#6c63ff',
                        cancelButtonColor: '#ef4444',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                        borderRadius: '14px',
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#simpletable').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_-_END_ dari _TOTAL_ data",
                    paginate: {
                        previous: "‹",
                        next: "›"
                    }
                }
            });
        });
    </script>
@endsection
