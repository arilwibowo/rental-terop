<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran - Admin Rental Terop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f7fb; }
        .sidebar { min-height: 100vh; background: #111827; }
        .sidebar .nav-link { color: #cbd5e1; border-radius: 10px; padding: 10px 14px; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { color: #fff; background: #2563eb; }
        .content-card { border: 0; border-radius: 18px; box-shadow: 0 10px 30px rgba(15, 23, 42, .08); }
        .qris-thumb { width: 72px; height: 72px; object-fit: cover; border-radius: 10px; background: #e5e7eb; }

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
                    <a class="nav-link" href="{{ route('admin.products.index') }}">Produk</a>
                    <a class="nav-link active" href="{{ route('admin.payment-methods.index') }}">Pembayaran</a>
                    <a class="nav-link" href="{{ route('admin.bookings.index') }}">Booking</a>
                    <a class="nav-link" href="{{ route('admin.reports.index') }}">Laporan</a>
                    <a class="nav-link" href="{{ url('/') }}" target="_blank">Lihat Website</a>
                </nav>
            </aside>

            <main class="col-md-9 col-lg-10 p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 fw-bold mb-1">Pembayaran Manual</h1>
                        <p class="text-muted mb-0">Kelola rekening bank dan QRIS yang tampil di invoice customer.</p>
                    </div>
                    <a href="{{ route('admin.payment-methods.create') }}" class="btn btn-primary">Tambah Pembayaran</a>
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
                                        <th>Bank</th>
                                        <th>Nomor Rekening</th>
                                        <th>Pemilik</th>
                                        <th>QRIS</th>
                                        <th>Status</th>
                                        <th style="width: 180px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($paymentMethods as $paymentMethod)
                                        <tr>
                                            <td class="fw-semibold">{{ $paymentMethod->bank_name }}</td>
                                            <td>{{ $paymentMethod->account_number }}</td>
                                            <td>{{ $paymentMethod->account_holder }}</td>
                                            <td>
                                                @if ($paymentMethod->qris_image)
                                                    <img src="{{ $paymentMethod->qrisImageUrl() }}" class="qris-thumb" alt="QRIS">
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($paymentMethod->is_active)
                                                    <span class="badge text-bg-success">Aktif</span>
                                                @else
                                                    <span class="badge text-bg-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('admin.payment-methods.edit', $paymentMethod) }}" class="btn btn-warning btn-sm">Edit</a>
                                                    <form action="{{ route('admin.payment-methods.destroy', $paymentMethod) }}" method="POST" onsubmit="return confirm('Hapus metode pembayaran ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">Belum ada metode pembayaran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $paymentMethods->links() }}
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
