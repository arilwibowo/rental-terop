<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - Rental Terop Astika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fb;
        }

        .sidebar {
            min-height: 100vh;
            background: #111827;
        }

        .sidebar .nav-link {
            color: #cbd5e1;
            border-radius: 10px;
            padding: 10px 14px;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            color: #ffffff;
            background: #2563eb;
        }

        .stat-card,
        .content-card {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
        }

        @include('admin.partials.mobile-desktop-layout')
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <aside class="col-md-3 col-lg-2 sidebar p-4">
                <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none d-block mb-4">
                    <div class="fw-bold fs-5">Rental Terop</div>
                    <div class="small text-secondary">Astika Admin Panel</div>
                </a>

                <nav class="nav flex-column gap-2">
                    <a class="nav-link active" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a class="nav-link" href="{{ route('admin.categories.index') }}">Kategori</a>
                    <a class="nav-link" href="{{ route('admin.products.index') }}">Produk</a>
                    <a class="nav-link" href="{{ route('admin.payment-methods.index') }}">Pembayaran</a>
                    <a class="nav-link" href="{{ route('admin.bookings.index') }}">Booking</a>
                    <a class="nav-link" href="{{ route('admin.reports.index') }}">Laporan</a>
                    <a class="nav-link" href="{{ route('admin.settings.edit') }}">Pengaturan</a>
                    <a class="nav-link" href="{{ url('/') }}" target="_blank">Lihat Website</a>
                </nav>

                <div class="mt-5 pt-4 border-top border-secondary">
                    <div class="small text-secondary mb-2">Login sebagai</div>
                    <div class="text-white fw-semibold">{{ auth()->user()->name }}</div>
                    <div class="small text-secondary">{{ auth()->user()->email }}</div>

                    <form action="{{ route('admin.logout') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm w-100">
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            <main class="col-md-9 col-lg-10 p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                    <div>
                        <h1 class="h3 fw-bold mb-1">Dashboard Admin</h1>
                        <p class="text-muted mb-0">
                            Ringkasan pengelolaan website Rental Terop Astika.
                        </p>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Tambah Produk</a>
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-primary">Tambah Kategori</a>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <div class="text-muted small mb-2">Total Kategori</div>
                                <div class="display-6 fw-bold">{{ $totalCategories }}</div>
                                <div class="small text-muted">Kategori produk rental</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <div class="text-muted small mb-2">Total Produk</div>
                                <div class="display-6 fw-bold">{{ $totalProducts }}</div>
                                <div class="small text-muted">Produk yang tersimpan</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <div class="text-muted small mb-2">Produk Aktif</div>
                                <div class="display-6 fw-bold">{{ $activeProducts }}</div>
                                <div class="small text-muted">Tampil di website</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card content-card">
                            <div class="card-body">
                                <h5 class="fw-bold mb-1">Produk Terbaru</h5>
                                <p class="text-muted small mb-3">Daftar 5 produk terakhir yang ditambahkan.</p>

                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead>
                                            <tr>
                                                <th>Produk</th>
                                                <th>Kategori</th>
                                                <th>Harga</th>
                                                <th>Stok</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($latestProducts as $product)
                                                <tr>
                                                    <td class="fw-semibold">{{ $product->name }}</td>
                                                    <td>{{ $product->category_name ?? '-' }}</td>
                                                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                                    <td>{{ $product->stock }}</td>
                                                    <td>
                                                        @if ($product->is_active)
                                                            <span class="badge text-bg-success">Aktif</span>
                                                        @else
                                                            <span class="badge text-bg-secondary">Nonaktif</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-4">
                                                        Belum ada produk. Nanti data produk akan tampil di sini.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card content-card mb-4">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3">Quick Guide</h5>
                                <ol class="text-muted mb-0">
                                    <li>Buat kategori produk.</li>
                                    <li>Tambah produk rental.</li>
                                    <li>Tampilkan produk ke halaman website.</li>
                                    <li>Hubungkan tombol pemesanan ke WhatsApp.</li>
                                </ol>
                            </div>
                        </div>

                        <div class="card content-card">
                            <div class="card-body">
                                <h5 class="fw-bold mb-2">Status Sistem</h5>
                                <p class="text-muted mb-3">
                                    Login admin, middleware, dan database produk sudah aktif.
                                </p>
                                <span class="badge text-bg-success">Online</span>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
