<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header justify-content-center">
            <a href="/dashboard-superadmin" class="b-brand text-primary">
                <img src="{{ asset('env') }}/hitam.png" alt="Logo" style="height: 60px;">
            </a>
        </div>
        @if (Auth::user()->role == 'superadmin')
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item">
                        <a href="/dashboard-superadmin" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Data KOJAR</label>
                        <i class="ti ti-dashboard"></i>
                    </li>


                    <li class="pc-item">
                        <a href="{{ route('kategori-produk.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-list"></i></span>
                            <span class="pc-mtext">Kategori Produk</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('produk.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-box"></i></span>
                            <span class="pc-mtext">Produk</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('pesanan.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-shopping-cart"></i></span>
                            <span class="pc-mtext">Kelola Pesanan</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('laporan.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-printer"></i></span>
                            <span class="pc-mtext">Laporan Penjualan</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('api-midtrans.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-credit-card"></i></span>
                            <span class="pc-mtext">API Midtrans</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif
    </div>
</nav>
