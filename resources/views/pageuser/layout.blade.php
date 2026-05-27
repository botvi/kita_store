<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KOJAR | Modern Clothing</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('style')
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #111;
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: 1px;
            font-size: 1.5rem;
            text-transform: uppercase;
            color: #111 !important;
        }

        .nav-link {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
            color: #555 !important;
        }

        .nav-link:hover {
            color: #000 !important;
        }

        .btn-dark {
            border-radius: 0;
            font-weight: 600;
            letter-spacing: 1px;
            padding: 10px 20px;
            transition: 0.3s;
        }

        .btn-dark:hover {
            background-color: #333;
        }

        .product-card {
            border: none;
            border-radius: 0;
            transition: transform 0.3s, box-shadow 0.3s;
            background: #fff;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .product-img {
            height: 350px;
            object-fit: cover;
            width: 100%;
            transition: 0.3s;
        }

        .product-img:hover {
            opacity: 0.9;
        }

        .product-title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-top: 15px;
            color: #111;
            text-decoration: none;
            display: block;
        }

        .product-title:hover {
            color: #f39c12;
        }

        .product-price {
            color: #666;
            font-weight: 600;
            font-size: 1rem;
        }

        .hero-section {
            background: url("{{ asset('env/hero.png') }}") center/cover;
            padding: 180px 0;
            color: white;
            position: relative;
            text-align: center;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .footer {
            background: #111;
            color: #fff;
            padding: 60px 0;
            margin-top: 50px;
        }

        .footer a {
            color: #aaa;
            text-decoration: none;
            transition: 0.3s;
        }

        .footer a:hover {
            color: #fff;
        }

        .page-header {
            background: #fff;
            padding: 40px 0;
            border-bottom: 1px solid #eaeaea;
            margin-bottom: 40px;
        }

        .page-title {
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top py-3 shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('index') }}">
                <img src="{{ asset('env/hitam.png') }}" alt="Logo" style="height: 40px; margin-right: 10px;">
                KOJAR
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navs">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navs">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ route('index') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('katalog.index') }}">Katalog Produk</a></li>
                </ul>
                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                        <li class="nav-item ms-3"><a class="nav-link" href="{{ route('keranjang.index') }}"><i
                                    class="fa-solid fa-cart-shopping me-1"></i> Keranjang</a></li>
                        <li class="nav-item ms-3"><a class="nav-link" href="{{ route('riwayat-pesanan.index') }}"><i
                                    class="fa-solid fa-receipt me-1"></i> Riwayat</a></li>
                        <li class="nav-item ms-3"><a class="nav-link" href="{{ route('profil.index') }}"><i
                                    class="fa-solid fa-user me-1"></i> Profil</a></li>
                        <li class="nav-item ms-3"><a class="btn btn-outline-danger btn-sm rounded-0 fw-bold"
                                href="{{ route('logout') }}">LOGOUT</a></li>
                    @else
                        <li class="nav-item ms-3"><a class="btn btn-dark btn-sm rounded-0 fw-bold px-4"
                                href="{{ route('login') }}">LOGIN</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#111',
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#111'
                });
            });
        </script>
    @endif

    @yield('content')

    <footer class="footer text-center">
        <div class="container">
            <img src="{{ asset('env/hitam.png') }}" alt="Logo"
                style="height: 50px; margin-bottom: 15px; filter: invert(1);">
            <h4 class="mb-3" style="font-weight:800; letter-spacing:2px;">KOJAR</h4>
            <p class="text-muted" style="max-width:400px; margin: 0 auto;">Pakaian anak muda kekinian. Selalu tampil
                stylish dan percaya diri dengan koleksi terbaru kami.</p>
            <div class="mt-4">
                <a href="#" class="me-3"><i class="fa-brands fa-instagram fs-4"></i></a>
                <a href="#" class="me-3"><i class="fa-brands fa-twitter fs-4"></i></a>
                <a href="#"><i class="fa-brands fa-tiktok fs-4"></i></a>
            </div>
            <p class="mt-4 text-muted mb-0" style="font-size: 0.9rem;">&copy; {{ date('Y') }} KOJAR. Dibuat
                Untuk Kebutuhan Clothing & Gaya.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
