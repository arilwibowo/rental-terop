<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $booking->invoice_number }} - Rental Terop Astika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; }
        .invoice-card { border: 0; border-radius: 18px; box-shadow: 0 10px 30px rgba(15, 23, 42, .08); }
        .qris-image { max-width: 220px; border-radius: 12px; border: 1px solid #dee2e6; }
        @media print {
            .no-print { display: none !important; }
            body { background: #ffffff; }
            .invoice-card { box-shadow: none; border: 1px solid #dee2e6; }
        }
    </style>
</head>
<body>
    @php
        $invoiceItems = $booking->items->isNotEmpty()
            ? $booking->items
            : collect([(object) [
                'product' => $booking->product,
                'quantity' => $booking->quantity,
                'unit_price' => $booking->unit_price,
                'rental_days' => $booking->rental_days ?? 1,
                'subtotal' => $booking->total_price,
            ]]);
    @endphp

    <main class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <a href="{{ route('products') }}" class="btn btn-outline-secondary">Kembali ke Produk</a>
            <button onclick="window.print()" class="btn btn-primary">Cetak Invoice</button>
        </div>

        @if (session('success'))
            <div class="alert alert-success no-print">{{ session('success') }}</div>
        @endif

        <div class="card invoice-card">
            <div class="card-body p-4 p-lg-5">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h1 class="h3 fw-bold mb-1">Rental Terop Astika</h1>
                        <p class="text-muted mb-0">Invoice Booking Rental</p>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <div class="text-muted small">Nomor Invoice</div>
                        <div class="h5 fw-bold">{{ $booking->invoice_number }}</div>
                        <span class="badge text-bg-{{ in_array($booking->payment_status, ['sudah_bayar', 'lunas'], true) ? 'success' : ($booking->payment_status === 'ditolak' ? 'danger' : 'warning') }}">
                            {{ str_replace('_', ' ', strtoupper($booking->payment_status)) }}
                        </span>
                    </div>
                </div>

                <hr>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="fw-bold">Data Customer</h5>
                        <table class="table table-borderless table-sm">
                            <tr><td class="text-muted" style="width: 140px;">Nama</td><td>: {{ $booking->customer_name }}</td></tr>
                            <tr><td class="text-muted">Nomor HP</td><td>: {{ $booking->phone_number }}</td></tr>
                            @if ($booking->customer_email)
                                <tr><td class="text-muted">Email</td><td>: {{ $booking->customer_email }}</td></tr>
                            @endif
                            <tr><td class="text-muted">Alamat</td><td>: {{ $booking->address }}</td></tr>
                            @if ($booking->event_location)
                                <tr><td class="text-muted">Lokasi Acara</td><td>: {{ $booking->event_location }}</td></tr>
                            @endif
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h5 class="fw-bold">Data Sewa</h5>
                        <table class="table table-borderless table-sm">
                            <tr><td class="text-muted" style="width: 150px;">Tanggal Invoice</td><td>: {{ $booking->created_at->format('d/m/Y H:i') }}</td></tr>
                            <tr><td class="text-muted">Mulai Sewa</td><td>: {{ $booking->rental_start_date?->format('d/m/Y') ?? $booking->event_date->format('d/m/Y') }}</td></tr>
                            <tr><td class="text-muted">Selesai Sewa</td><td>: {{ $booking->rental_end_date?->format('d/m/Y') ?? $booking->event_date->format('d/m/Y') }}</td></tr>
                            <tr><td class="text-muted">Lama Sewa</td><td>: {{ $booking->rental_days ?? 1 }} hari</td></tr>
                            <tr><td class="text-muted">Status Booking</td><td>: {{ ucfirst($booking->booking_status) }}</td></tr>
                        </table>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga per Hari</th>
                                <th class="text-center">Lama Sewa</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoiceItems as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $item->product->name ?? '-' }}</div>
                                        <div class="small text-muted">{{ $item->product->category->name ?? '-' }}</div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $item->rental_days }} hari</td>
                                    <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Grand Total</th>
                                <th class="text-end text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="alert alert-warning mt-4">
                    Status pembayaran:
                    <strong>{{ str_replace('_', ' ', strtoupper($booking->payment_status)) }}</strong>.
                    Silakan lakukan transfer manual lalu upload bukti pembayaran.
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Pembayaran Manual</h5>

                        @forelse ($paymentMethods as $paymentMethod)
                            <div class="border rounded p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="fw-bold">{{ $paymentMethod->bank_name }}</div>
                                        <div>Nomor Rekening: <strong>{{ $paymentMethod->account_number }}</strong></div>
                                        <div>Atas Nama: <strong>{{ $paymentMethod->account_holder }}</strong></div>
                                    </div>
                                    <div class="col-md-5 mt-3 mt-md-0 text-md-end">
                                        @if ($paymentMethod->qris_image)
                                            <img src="{{ $paymentMethod->qrisImageUrl() }}" class="qris-image" alt="QRIS {{ $paymentMethod->bank_name }}">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info mb-0">
                                Rekening pembayaran belum diatur admin.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="card mt-4 no-print">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Upload Bukti Pembayaran</h5>

                        @if ($booking->payment_proof)
                            <p class="text-muted mb-2">Bukti pembayaran saat ini:</p>
                            <a href="{{ $booking->paymentProofUrl() }}" target="_blank">
                                <img src="{{ $booking->paymentProofUrl() }}" alt="Bukti pembayaran" class="img-fluid rounded border mb-3" style="max-height: 260px;">
                            </a>
                        @endif

                        @if (! in_array($booking->payment_status, ['sudah_bayar', 'lunas'], true))
                            <form action="{{ route('bookings.payment-proof', $booking->invoice_number) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="payment_proof" class="form-label">Pilih gambar bukti pembayaran</label>
                                    <input type="file" class="form-control @error('payment_proof') is-invalid @enderror" id="payment_proof" name="payment_proof" accept="image/*" required>
                                    <div class="form-text">Format JPG, PNG, WEBP. Maksimal 2MB.</div>
                                    @error('payment_proof')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button class="btn btn-primary">Upload Bukti Pembayaran</button>
                            </form>
                        @else
                            <div class="alert alert-success mb-0">
                                Pembayaran sudah diverifikasi.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
