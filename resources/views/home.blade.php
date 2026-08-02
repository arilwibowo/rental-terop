<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rental Terop Astika</title>

    {{-- Bootstrap 5 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-image {
            height: 220px;
            object-fit: cover;
        }

        .product-placeholder {
            height: 220px;
            background: linear-gradient(135deg, #e5e7eb, #f8fafc);
        }

        .products-premium {
            background:
                radial-gradient(circle at top right, rgba(245, 158, 11, .16), transparent 32%),
                linear-gradient(180deg, #fffaf0 0%, #f8fafc 100%);
        }

        .section-eyebrow {
            display: inline-flex;
            padding: 7px 14px;
            border-radius: 999px;
            background: #fff7ed;
            color: #b45309;
            font-weight: 700;
            font-size: .82rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            border: 1px solid #fed7aa;
        }

        .product-card-premium {
            overflow: hidden;
            border-radius: 22px;
            transition: transform .25s ease, box-shadow .25s ease;
            box-shadow: 0 16px 36px rgba(15, 23, 42, .08) !important;
        }

        .product-card-premium:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 52px rgba(15, 23, 42, .14) !important;
        }

        .product-card-premium .product-image,
        .product-card-premium .product-placeholder {
            transition: transform .35s ease;
        }

        .product-card-premium:hover .product-image,
        .product-card-premium:hover .product-placeholder {
            transform: scale(1.035);
        }

        .btn-gold {
            color: #111827;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border: 0;
            box-shadow: 0 12px 24px rgba(245, 158, 11, .25);
        }

        .btn-gold:hover {
            color: #111827;
            background: linear-gradient(135deg, #fcd34d, #d97706);
            transform: translateY(-1px);
        }

        .btn-gold:disabled {
            color: #78350f;
            background: #fde68a;
            opacity: .75;
            box-shadow: none;
        }

        .badge-gold {
            color: #78350f;
            background: #fef3c7;
            border: 1px solid #fde68a;
        }

        .text-gold {
            color: #d97706 !important;
        }

        .product-price {
            color: #111827;
        }

        .hero-corporate {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at top left, rgba(245, 158, 11, .18), transparent 34%),
                linear-gradient(135deg, #0f172a 0%, #1e293b 48%, #f8fafc 48%, #ffffff 100%);
            min-height: 640px;
        }

        .hero-corporate::after {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, .05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .05) 1px, transparent 1px);
            background-size: 42px 42px;
            opacity: .35;
            pointer-events: none;
        }

        .hero-content,
        .hero-visual {
            position: relative;
            z-index: 1;
            animation: heroFadeUp .85s ease both;
        }

        .hero-visual {
            animation-delay: .15s;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .12);
            color: #fde68a;
            border: 1px solid rgba(255, 255, 255, .18);
            font-size: .92rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .hero-title {
            color: #ffffff;
            font-size: clamp(2.4rem, 5vw, 4.8rem);
            line-height: 1.04;
            letter-spacing: -.04em;
        }

        .hero-description {
            max-width: 600px;
            color: rgba(255, 255, 255, .78);
            font-size: 1.15rem;
            line-height: 1.75;
        }

        .hero-image-wrap {
            position: relative;
        }

        .hero-image-wrap::before {
            content: "";
            position: absolute;
            inset: 34px -22px -22px 34px;
            border-radius: 32px;
            background: linear-gradient(135deg, rgba(245, 158, 11, .26), rgba(37, 99, 235, .22));
            z-index: -1;
        }

        .hero-image {
            width: 100%;
            min-height: 440px;
            max-height: 560px;
            object-fit: cover;
            border-radius: 32px;
            border: 10px solid rgba(255, 255, 255, .84);
            box-shadow: 0 28px 70px rgba(15, 23, 42, .32);
        }

        .hero-floating-card {
            position: absolute;
            left: -18px;
            bottom: 28px;
            max-width: 260px;
            border: 0;
            border-radius: 20px;
            box-shadow: 0 20px 45px rgba(15, 23, 42, .22);
        }

        .contact-premium {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at top left, rgba(245, 158, 11, .18), transparent 32%),
                radial-gradient(circle at bottom right, rgba(37, 99, 235, .26), transparent 34%),
                linear-gradient(135deg, #0f172a 0%, #1e293b 58%, #334155 100%);
            color: #ffffff;
        }

        .contact-premium::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, .05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .05) 1px, transparent 1px);
            background-size: 42px 42px;
            opacity: .28;
            pointer-events: none;
        }

        .contact-premium .container {
            position: relative;
            z-index: 1;
        }

        .contact-premium .text-muted {
            color: rgba(255, 255, 255, .72) !important;
        }

        .contact-card {
            height: 100%;
            border: 1px solid rgba(255, 255, 255, .14) !important;
            border-radius: 22px;
            background: rgba(255, 255, 255, .09);
            color: #ffffff;
            box-shadow: 0 20px 48px rgba(15, 23, 42, .24);
            backdrop-filter: blur(12px);
            transition: transform .25s ease, border-color .25s ease, background .25s ease;
        }

        .contact-card:hover {
            transform: translateY(-6px);
            border-color: rgba(251, 191, 36, .55) !important;
            background: rgba(255, 255, 255, .12);
        }

        .contact-icon {
            width: 46px;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            color: #111827;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            font-weight: 800;
            margin-bottom: 18px;
            box-shadow: 0 12px 24px rgba(245, 158, 11, .24);
        }

        .btn-contact-outline {
            color: #fde68a;
            border: 1px solid rgba(253, 230, 138, .7);
            background: rgba(255, 255, 255, .04);
        }

        .btn-contact-outline:hover {
            color: #111827;
            background: #fbbf24;
            border-color: #fbbf24;
        }

        @keyframes heroFadeUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 991.98px) {
            .hero-corporate {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                min-height: auto;
            }

            .hero-image {
                min-height: 340px;
            }

            .hero-floating-card {
                position: static;
                max-width: 100%;
                margin-top: -24px;
                margin-left: 18px;
                margin-right: 18px;
            }
        }

        @media (max-width: 575.98px) {
            .products-premium {
                padding-top: 2.5rem !important;
                padding-bottom: 2.5rem !important;
            }

            .products-premium .container,
            .contact-premium .container {
                padding-left: 14px;
                padding-right: 14px;
            }

            .products-premium .row {
                --bs-gutter-x: .45rem;
                --bs-gutter-y: .45rem;
            }

            .section-eyebrow {
                padding: 5px 10px;
                font-size: .68rem;
            }

            .products-premium h2,
            .contact-premium h2 {
                font-size: 1.45rem;
            }

            .products-premium p,
            .contact-premium p {
                font-size: .85rem;
            }

            .product-card-premium {
                border-radius: 16px;
                box-shadow: 0 10px 24px rgba(15, 23, 42, .08) !important;
            }

            .product-image,
            .product-placeholder {
                height: 64px;
            }

            .product-card-premium .card-body {
                padding: .45rem;
            }

            .product-card-premium .badge {
                font-size: .48rem;
                white-space: normal;
            }

            .product-card-premium .card-title {
                font-size: .64rem;
                line-height: 1.2;
                margin-bottom: .25rem;
            }

            .product-card-premium .card-text {
                display: none;
            }

            .product-price {
                font-size: .62rem;
                margin-bottom: .15rem !important;
            }

            .product-card-premium .small {
                font-size: .55rem;
                margin-bottom: .35rem !important;
            }

            .product-card-premium .btn {
                padding: .32rem .2rem;
                font-size: .52rem;
                line-height: 1.2;
            }

            .contact-premium .row {
                --bs-gutter-x: .55rem;
                --bs-gutter-y: .55rem;
            }

            .contact-card {
                border-radius: 16px;
            }

            .contact-card .card-body {
                padding: .7rem;
            }

            .contact-icon {
                width: 34px;
                height: 34px;
                border-radius: 12px;
                font-size: .72rem;
                margin-bottom: .55rem;
            }

            .contact-card h5 {
                font-size: .82rem;
            }

            .contact-card p {
                font-size: .72rem;
                line-height: 1.35;
            }

            .contact-card .btn {
                width: 100%;
                padding: .4rem .25rem;
                font-size: .68rem;
                line-height: 1.2;
                word-break: break-word;
            }
        }
    </style>
</head>
<body>
    @php
        $cartCount = array_sum(session('cart', []));
    @endphp

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                @include('partials.brand-logo')
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products') }}">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            Keranjang <span class="badge text-bg-light">{{ $cartCount }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orders.history') }}">Cek Pesanan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="hero-corporate py-5 py-lg-0">
        <div class="container position-relative">
            <div class="row align-items-center min-vh-100 py-5 g-5">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <div class="hero-badge mb-4">
                            Event Planner • Rental Terop • Alat Pesta
                        </div>

                        <h1 class="hero-title fw-bold mb-4">
                            Solusi Terop Elegan untuk Acara yang Berkesan.
                        </h1>

                        <p class="hero-description mb-5">
                            Hartono Jaya Terop melayani penyewaan terop, dekorasi, kursi, meja,
                            dan perlengkapan pesta untuk acara keluarga, wedding, hajatan,
                            hingga event corporate dengan pemasangan rapi dan layanan profesional.
                        </p>

                        <div class="d-flex flex-column flex-sm-row gap-3">
                            <a href="{{ route('products') }}" class="btn btn-warning btn-lg px-4 fw-semibold">
                                Lihat Produk
                            </a>
                            <a href="https://wa.me/{{ $setting->admin_whatsapp }}" class="btn btn-outline-light btn-lg px-4" target="_blank">
                                Hubungi Admin via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hero-visual">
                        <div class="hero-image-wrap">
                            <img
                                src="{{ asset('images/hero-terop.png') }}"
                                alt="Pemasangan terop dekorasi untuk acara"
                                class="hero-image"
                            >

                            <div class="card hero-floating-card">
                                <div class="card-body p-3">
                                    <div class="fw-bold">Pemasangan rapi & elegan</div>
                                    <div class="small text-muted">
                                        Cocok untuk wedding, hajatan, dan event profesional.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Produk Unggulan --}}
    <section id="produk" class="products-premium py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-eyebrow mb-3">Pilihan Terbaik</span>
                <h2 class="fw-bold">Produk Unggulan</h2>
                <p class="text-muted">Pilihan perlengkapan acara yang tersedia untuk disewa.</p>
            </div>

            <div class="row g-4">
                @forelse ($products as $product)
                    @php $isOutOfStock = $product->stock < 1; @endphp
                    <div class="col-3 col-md-6 col-lg-4">
                        <div class="card h-100 border-0 product-card-premium">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                            @else
                                <div class="product-placeholder d-flex align-items-center justify-content-center text-muted">
                                    Belum ada gambar
                                </div>
                            @endif

                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    <span class="badge badge-gold">
                                        {{ $product->category->name ?? 'Tanpa Kategori' }}
                                    </span>
                                    @if ($isOutOfStock)
                                        <span class="badge text-bg-danger">STOK HABIS</span>
                                    @endif
                                </div>

                                <h5 class="card-title fw-bold">{{ $product->name }}</h5>
                                <p class="card-text text-muted">
                                    {{ $product->description ?: 'Produk rental siap digunakan untuk kebutuhan acara Anda.' }}
                                </p>

                                <div class="mt-auto">
                                    <p class="fw-semibold product-price mb-2">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </p>
                                    <p class="small text-muted mb-3">
                                        Stok tersedia: {{ $product->stock }}
                                    </p>

                                    @if ($isOutOfStock)
                                        <div class="alert alert-warning small mb-2">
                                            Maaf, stok produk sedang habis atau tidak tersedia pada tanggal yang dipilih.
                                        </div>
                                    @endif

                                    <form action="{{ route('cart.add', $product->slug) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-gold w-100 fw-semibold" @disabled($isOutOfStock)>
                                            Tambah ke Keranjang
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center mb-0">
                            Produk belum tersedia. Silakan tambahkan produk melalui halaman admin.
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($products->isNotEmpty())
                <div class="text-center mt-5">
                    <a href="{{ route('products') }}" class="btn btn-gold btn-lg px-4 fw-semibold">
                        Lihat Semua Produk
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- Tentang Kami --}}
    <section id="tentang" class="bg-light py-5">
        <div class="container">
            <h2 class="fw-bold mb-3">Tentang Kami</h2>
            <p class="text-muted mb-0">
                Rental Terop Astika menyediakan layanan sewa terop dan perlengkapan acara
                dengan pelayanan cepat, rapi, dan harga bersahabat.
            </p>
        </div>
    </section>

    {{-- Kontak --}}
    <section id="kontak" class="contact-premium py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Kontak Kami</h2>
                <p class="text-muted mb-0">Silakan hubungi kami untuk cek ketersediaan dan pemesanan.</p>
            </div>

            <div class="row g-4">
                <div class="col-4 col-md-4">
                    <div class="card contact-card">
                        <div class="card-body">
                            <div class="contact-icon">WA</div>
                            <h5 class="fw-bold">WhatsApp</h5>
                            <p class="text-muted mb-3">Tanya harga dan ketersediaan produk.</p>
                            <a href="https://wa.me/{{ $setting->admin_whatsapp }}" class="btn btn-gold fw-semibold">
                                Chat WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-4 col-md-4">
                    <div class="card contact-card">
                        <div class="card-body">
                            <div class="contact-icon">IG</div>
                            <h5 class="fw-bold">Instagram</h5>
                            <p class="text-muted mb-3">
                                Lihat dokumentasi dan update terbaru kami.
                            </p>
                            <a href="https://www.instagram.com/hartonojayaterop" target="_blank" class="btn btn-contact-outline fw-semibold">
                                @hartonojayaterop
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-4 col-md-4">
                    <div class="card contact-card">
                        <div class="card-body">
                            <div class="contact-icon">LO</div>
                            <h5 class="fw-bold">Lokasi</h5>
                            <p class="text-muted mb-3">
                                Bangkingan Timur 2 No.41 A, Surabaya
                            </p>
                            <a
                                href="https://www.google.com/maps/search/?api=1&query=Bangkingan%20Timur%202%20No.41%20A%20Surabaya"
                                target="_blank"
                                class="btn btn-contact-outline fw-semibold"
                            >
                                Lihat Lokasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-dark text-white text-center py-3">
        <div class="container">
            <small>&copy; {{ date('Y') }} Rental Terop Hartono jaya. Semua Hak Dilindungi.</small>
        </div>
    </footer>

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
