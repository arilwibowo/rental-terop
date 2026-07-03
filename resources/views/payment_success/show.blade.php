<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran Berhasil - Rental Terop Astika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-lg-5 text-center">
                        <div class="display-3 mb-3">✅</div>
                        <h1 class="h3 fw-bold mb-3">Pembayaran Berhasil Dikirim</h1>

                        <div class="alert alert-success text-start">
                            <div>✅ Upload bukti pembayaran berhasil.</div>
                            <div>✅ Pembayaran berhasil dikirim.</div>
                            <div>⏳ Silakan tunggu proses verifikasi dari Admin.</div>
                        </div>

                        <div class="border rounded p-3 mb-4 text-start">
                            <div><strong>Nomor Invoice:</strong> {{ $booking->invoice_number }}</div>
                            <div><strong>Nama Customer:</strong> {{ $booking->customer_name }}</div>
                            <div><strong>Status:</strong> {{ str_replace('_', ' ', strtoupper($booking->payment_status)) }}</div>
                        </div>

                        @php
                            $message = "Halo Admin, saya sudah melakukan pembayaran dan mengunggah bukti transfer. Mohon dilakukan verifikasi pembayaran saya.\n\nNomor Invoice: {$booking->invoice_number}\n\nNama Customer: {$booking->customer_name}";
                        @endphp

                        <div class="d-grid gap-2">
                            <a href="{{ route('bookings.invoice-download', $booking->invoice_number) }}" class="btn btn-primary">
                                Download Invoice PDF
                            </a>
                            <a href="https://wa.me/{{ $setting->admin_whatsapp }}?text={{ urlencode($message) }}" target="_blank" class="btn btn-success">
                                Hubungi Admin via WhatsApp
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                Kembali ke Halaman Utama
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
