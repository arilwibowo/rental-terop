<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Produk - Rental Terop Astika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f7fb; }
        .sidebar { min-height: 100vh; background: #111827; }
        .sidebar .nav-link { color: #cbd5e1; border-radius: 10px; padding: 10px 14px; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { color: #fff; background: #2563eb; }
        .content-card { border: 0; border-radius: 18px; box-shadow: 0 10px 30px rgba(15, 23, 42, .08); }
        .product-thumb { width: 64px; height: 64px; object-fit: cover; border-radius: 12px; background: #e5e7eb; }

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
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a class="nav-link" href="{{ route('admin.categories.index') }}">Kategori</a>
                    <a class="nav-link active" href="{{ route('admin.products.index') }}">Produk</a>
                    <a class="nav-link" href="{{ route('admin.payment-methods.index') }}">Pembayaran</a>
                    <a class="nav-link" href="{{ route('admin.bookings.index') }}">Booking</a>
                    <a class="nav-link" href="{{ route('admin.reports.index') }}">Laporan</a>
                    <a class="nav-link" href="{{ url('/') }}" target="_blank">Lihat Website</a>
                </nav>

                <div class="mt-5 pt-4 border-top border-secondary">
                    <div class="small text-secondary mb-2">Login sebagai</div>
                    <div class="text-white fw-semibold">{{ auth()->user()->name }}</div>
                    <div class="small text-secondary">{{ auth()->user()->email }}</div>

                    <form action="{{ route('admin.logout') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm w-100">Logout</button>
                    </form>
                </div>
            </aside>

            <main class="col-md-9 col-lg-10 p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                    <div>
                        <h1 class="h3 fw-bold mb-1">Produk Rental</h1>
                        <p class="text-muted mb-0">Kelola produk seperti terop, kursi, meja, dekorasi, dan paket sewa.</p>
                    </div>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Tambah Produk</a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card content-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 70px;">No</th>
                                        <th>Gambar</th>
                                        <th>Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                        <th style="width: 180px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $product)
                                        <tr>
                                            <td>{{ $products->firstItem() + $loop->index }}</td>
                                            <td>
                                                @if ($product->primaryImage())
                                                    <img src="{{ $product->imageUrl() }}" class="product-thumb" alt="{{ $product->name }}">
                                                @else
                                                    <div class="product-thumb d-flex align-items-center justify-content-center text-muted small">No Img</div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $product->name }}</div>
                                                <div class="small text-muted">{{ $product->slug }}</div>
                                            </td>
                                            <td>{{ $product->category->name ?? '-' }}</td>
                                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                            <td>{{ $product->stock }}</td>
                                            <td>
                                                @if ($product->is_active)
                                                    <span class="badge text-bg-success">Aktif</span>
                                                @else
                                                    <span class="badge text-bg-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm">Edit</a>

                                                    <form
                                                        action="{{ route('admin.products.destroy', $product) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Yakin ingin menghapus produk ini? Riwayat booking produk ini juga akan dihapus.')"
                                                    >
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">
                                                Belum ada produk. Klik tombol Tambah Produk untuk membuat data pertama.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
