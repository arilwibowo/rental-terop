<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cek Pesanan - Rental Terop Astika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5">
        <div class="mb-4">
            <h1 class="h3 fw-bold mb-1">Cek Pesanan</h1>
            <p class="text-muted mb-0">Masukkan Nomor Invoice untuk melihat status pesanan.</p>
        </div>

        <div class="mb-4">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                Kembali ke Halaman Utama
            </a>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-2">
                    <div class="col-md-10">
                        <input type="text" name="invoice" class="form-control" value="{{ request('invoice') }}" placeholder="Nomor Invoice">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Cari</button>
                    </div>
                </form>
            </div>
        </div>

        @if ($notFound)
            @php
                $message = "Halo Admin, saya ingin menanyakan pesanan saya, tetapi Nomor Invoice tidak ditemukan.\n\nNomor Invoice: " . request('invoice');
            @endphp

            <div class="alert alert-warning">
                Nomor Invoice tidak ditemukan.
            </div>

            <a href="https://wa.me/{{ $setting->admin_whatsapp }}?text={{ urlencode($message) }}" target="_blank" class="btn btn-success mb-4">
                Hubungi Admin via WhatsApp
            </a>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Nama</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $booking)
                                <tr>
                                    <td class="fw-semibold">{{ $booking->invoice_number }}</td>
                                    <td>{{ $booking->customer_name }}</td>
                                    <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                    <td>{{ str_replace('_', ' ', strtoupper($booking->payment_status)) }}</td>
                                    <td>
                                        <a href="{{ route('bookings.invoice-download', $booking->invoice_number) }}" class="btn btn-primary btn-sm">Download Invoice PDF</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data pesanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
