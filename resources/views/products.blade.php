<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Produk Rental - Rental Terop Astika</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background:
                radial-gradient(circle at top right, rgba(245, 158, 11, .16), transparent 32%),
                linear-gradient(180deg, #fffaf0 0%, #f8fafc 42%, #ffffff 100%);
        }

        .products-hero {
            background:
                linear-gradient(135deg, rgba(17, 24, 39, .95), rgba(92, 52, 11, .88)),
                radial-gradient(circle at top right, rgba(251, 191, 36, .34), transparent 34%);
            color: #fff;
        }

        .products-hero .text-muted {
            color: rgba(255, 255, 255, .74) !important;
        }

        .product-image {
            height: 230px;
            object-fit: cover;
        }

        .product-placeholder {
            height: 230px;
            background: linear-gradient(135deg, #e5e7eb, #f8fafc);
        }

        .category-link {
            text-decoration: none;
        }

        .btn-gold {
            color: #111827;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border: 0;
            box-shadow: 0 12px 24px rgba(245, 158, 11, .25);
        }

        .btn-gold:hover,
        .btn-gold:focus {
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

        .product-card-premium,
        .filter-card {
            border-radius: 22px;
            box-shadow: 0 16px 36px rgba(15, 23, 42, .08) !important;
        }

        .product-card-premium {
            overflow: hidden;
            transition: transform .25s ease, box-shadow .25s ease;
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

        .list-group-item.active {
            color: #111827;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border-color: #f59e0b;
            font-weight: 700;
        }

        .list-group-item.active .badge {
            color: #78350f !important;
            background: #fff7ed !important;
        }

        .pagination .page-link {
            color: #b45309;
        }

        .pagination .page-item.active .page-link {
            color: #111827;
            background-color: #f59e0b;
            border-color: #f59e0b;
        }

        @media (max-width: 575.98px) {
            .products-hero {
                padding-top: 2.5rem !important;
                padding-bottom: 2.5rem !important;
            }

            .products-hero h1 {
                font-size: 1.85rem;
            }

            .products-hero .lead {
                font-size: .92rem;
                line-height: 1.55;
            }

            section.py-5 {
                padding-top: 2.2rem !important;
                padding-bottom: 2.2rem !important;
            }

            section.py-5 .container {
                padding-left: 14px;
                padding-right: 14px;
            }

            .col-lg-9 .row {
                --bs-gutter-x: .45rem;
                --bs-gutter-y: .45rem;
            }

            .filter-card {
                border-radius: 16px;
            }

            .filter-card .card-body {
                padding: .85rem;
            }

            .filter-card h5 {
                font-size: .95rem;
            }

            .filter-card .list-group-item {
                padding: .5rem .65rem;
                font-size: .82rem;
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
        }
    </style>
</head>
<body>
    @php
        $cartCount = array_sum(session('cart', []));
    @endphp

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
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('products') }}">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#tentang">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#kontak">Kontak</a>
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

    <section class="products-hero py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3">Daftar Produk Rental</h1>
                    <p class="lead text-muted mb-0">
                        Pilih perlengkapan acara sesuai kebutuhan. Semua produk yang aktif dari admin akan tampil di halaman ini.
                    </p>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0 text-lg-end">
                    <a href="https://wa.me/{{ $setting->admin_whatsapp }}" class="btn btn-gold btn-lg fw-semibold" target="_blank">
                        Konsultasi via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3">
                    <div class="card border-0 filter-card">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Filter Kategori</h5>

                            <div class="list-group">
                                <a
                                    href="{{ route('products') }}"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $selectedCategory ? '' : 'active' }}"
                                >
                                    Semua Produk
                                    <span class="badge {{ $selectedCategory ? 'text-bg-light' : 'badge-gold' }}">
                                        {{ $products->total() }}
                                    </span>
                                </a>

                                @foreach ($categories as $category)
                                    <a
                                        href="{{ route('products', ['category' => $category->slug]) }}"
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $selectedCategory === $category->slug ? 'active' : '' }}"
                                    >
                                        {{ $category->name }}
                                        <span class="badge {{ $selectedCategory === $category->slug ? 'badge-gold' : 'text-bg-light' }}">
                                            {{ $category->products_count }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="row g-4">
                        @forelse ($products as $product)
                            @php $isOutOfStock = $product->stock < 1; @endphp
                            <div class="col-3 col-md-6 col-xl-4">
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
                                <div class="alert alert-info text-center">
                                    Produk belum tersedia untuk kategori ini.
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-3">
        <div class="container">
            <small>&copy; {{ date('Y') }} Rental Terop Astika. Semua Hak Dilindungi.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
