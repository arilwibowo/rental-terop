<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Booking - Admin Rental Terop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f7fb; }
        .content-card { border: 0; border-radius: 18px; box-shadow: 0 10px 30px rgba(15, 23, 42, .08); }
        .proof-image { max-width: 100%; border-radius: 14px; border: 1px solid #dee2e6; }
    </style>
</head>
<body>
    <main class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Detail Booking</h1>
                <p class="text-muted mb-0">{{ $booking->invoice_number }}</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.bookings.invoice-pdf', $booking) }}" class="btn btn-danger">Download PDF</a>
                <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-warning">Edit Booking</a>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card content-card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Data Booking</h5>
                        <table class="table">
                            <tr><th>Customer</th><td>{{ $booking->customer_name }}</td></tr>
                            <tr><th>Nomor HP</th><td>{{ $booking->phone_number }}</td></tr>
                            <tr><th>Alamat</th><td>{{ $booking->address }}</td></tr>
                            <tr><th>Tanggal Acara</th><td>{{ $booking->event_date->format('d/m/Y') }}</td></tr>
                            <tr><th>Produk</th><td>{{ $booking->product->name }}</td></tr>
                            <tr><th>Jumlah</th><td>{{ $booking->quantity }}</td></tr>
                            <tr><th>Total</th><td class="fw-bold text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td></tr>
                            <tr>
                                <th>Status Pembayaran</th>
                                <td>{{ str_replace('_', ' ', strtoupper($booking->payment_status)) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card content-card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Bukti Pembayaran</h5>

                        @if ($booking->payment_proof)
                            <a href="{{ $booking->paymentProofUrl() }}" target="_blank">
                                <img src="{{ $booking->paymentProofUrl() }}" class="proof-image mb-3" alt="Bukti pembayaran">
                            </a>
                        @else
                            <div class="alert alert-warning">Customer belum upload bukti pembayaran.</div>
                        @endif

                        @if ($booking->payment_rejection_reason)
                            <div class="alert alert-danger">
                                Alasan ditolak: {{ $booking->payment_rejection_reason }}
                            </div>
                        @endif

                        @if (! in_array($booking->payment_status, ['sudah_bayar', 'lunas'], true))
                            <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST" class="mb-3">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success w-100" @disabled(! $booking->payment_proof)>
                                    Setujui Pembayaran
                                </button>
                            </form>

                            <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="mb-2">
                                    <textarea name="payment_rejection_reason" class="form-control" rows="3" placeholder="Alasan penolakan, opsional"></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger w-100" @disabled(! $booking->payment_proof)>
                                    Tolak Pembayaran
                                </button>
                            </form>
                        @else
                            <div class="alert alert-success mb-0">
                                Pembayaran sudah disetujui pada {{ $booking->payment_verified_at?->format('d/m/Y H:i') }}.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
