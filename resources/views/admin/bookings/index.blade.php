<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking - Admin Rental Terop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f7fb; }
        .sidebar { min-height: 100vh; background: #111827; }
        .sidebar .nav-link { color: #cbd5e1; border-radius: 10px; padding: 10px 14px; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { color: #fff; background: #2563eb; }
        .content-card { border: 0; border-radius: 18px; box-shadow: 0 10px 30px rgba(15, 23, 42, .08); }
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
                    <a class="nav-link" href="{{ route('admin.payment-methods.index') }}">Pembayaran</a>
                    <a class="nav-link active" href="{{ route('admin.bookings.index') }}">Booking</a>
                    <a class="nav-link" href="{{ route('admin.reports.index') }}">Laporan</a>
                    <a class="nav-link" href="{{ url('/') }}" target="_blank">Lihat Website</a>
                </nav>
            </aside>

            <main class="col-md-9 col-lg-10 p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 fw-bold mb-1">Kelola Booking</h1>
                        <p class="text-muted mb-0">Data otomatis diurutkan berdasarkan jadwal pasang dan bongkar.</p>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card content-card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">Cari Nomor Invoice</label>
                                <input type="text" name="invoice" class="form-control" value="{{ request('invoice') }}" placeholder="Contoh: INV-20260628-0001">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Filter Status Pembayaran</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="belum_bayar" @selected(request('status') === 'belum_bayar')>Belum Bayar</option>
                                    <option value="menunggu_pembayaran" @selected(request('status') === 'menunggu_pembayaran')>Menunggu Pembayaran</option>
                                    <option value="menunggu_verifikasi" @selected(request('status') === 'menunggu_verifikasi')>Menunggu Verifikasi</option>
                                    <option value="sudah_bayar" @selected(request('status') === 'sudah_bayar')>Sudah Bayar</option>
                                    <option value="lunas" @selected(request('status') === 'lunas')>Lunas</option>
                                    <option value="ditolak" @selected(request('status') === 'ditolak')>Ditolak</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin.bookings.index') }}" class="btn btn-light">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card content-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Nomor Invoice</th>
                                        <th>Customer</th>
                                        <th>Produk</th>
                                        <th>Total</th>
                                        <th>Status Bayar</th>
                                        <th>Tanggal Pasang<br><small class="text-muted">Tanggal Acara</small></th>
                                        <th>Tanggal Bongkar<br><small class="text-muted">Batas Sewa</small></th>
                                        <th style="width: 300px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($bookings as $booking)
                                        <tr>
                                            <td class="fw-semibold">{{ $booking->invoice_number }}</td>
                                            <td>
                                                {{ $booking->customer_name }}<br>
                                                <span class="small text-muted">{{ $booking->phone_number }}</span>
                                            </td>
                                            <td>{{ $booking->product->name ?? '-' }}</td>
                                            <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge text-bg-{{ in_array($booking->payment_status, ['sudah_bayar', 'lunas'], true) ? 'success' : ($booking->payment_status === 'ditolak' ? 'danger' : 'warning') }}">
                                                    {{ str_replace('_', ' ', strtoupper($booking->payment_status)) }}
                                                </span>
                                            </td>
                                            <td>{{ ($booking->rental_start_date ?? $booking->event_date)->format('d/m/Y') }}</td>
                                            <td>{{ ($booking->rental_end_date ?? $booking->event_date)->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <a href="{{ route('admin.bookings.invoice-pdf', $booking) }}" class="btn btn-danger btn-sm">Download PDF</a>
                                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-primary btn-sm">Detail</a>
                                                    <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-warning btn-sm">Edit</a>

                                                    <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus booking ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">Belum ada booking.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $bookings->links() }}
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
