<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Booking - Admin Rental Terop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f7fb; }
        .content-card { border: 0; border-radius: 18px; box-shadow: 0 10px 30px rgba(15, 23, 42, .08); }
    </style>
</head>
<body>
    <main class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Edit Booking</h1>
                <p class="text-muted mb-0">{{ $booking->invoice_number }}</p>
            </div>
            <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

        <div class="card content-card">
            <div class="card-body p-4">
                <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Customer</label>
                            <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name', $booking->customer_name) }}" required>
                            @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor HP</label>
                            <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', $booking->phone_number) }}" required>
                            @error('phone_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="customer_email" class="form-control @error('customer_email') is-invalid @enderror" value="{{ old('customer_email', $booking->customer_email) }}">
                        @error('customer_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror" required>{{ old('address', $booking->address) }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi Acara</label>
                        <input type="text" name="event_location" class="form-control @error('event_location') is-invalid @enderror" value="{{ old('event_location', $booking->event_location) }}">
                        @error('event_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Pasang / Tanggal Acara</label>
                            <input type="date" name="rental_start_date" class="form-control @error('rental_start_date') is-invalid @enderror" value="{{ old('rental_start_date', ($booking->rental_start_date ?? $booking->event_date)->format('Y-m-d')) }}" required>
                            @error('rental_start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Bongkar / Batas Sewa</label>
                            <input type="date" name="rental_end_date" class="form-control @error('rental_end_date') is-invalid @enderror" value="{{ old('rental_end_date', ($booking->rental_end_date ?? $booking->event_date)->format('Y-m-d')) }}" required>
                            @error('rental_end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status Pembayaran</label>
                            <select name="payment_status" class="form-select" required>
                                @foreach (['belum_bayar' => 'Belum Bayar', 'menunggu_pembayaran' => 'Menunggu Pembayaran', 'menunggu_verifikasi' => 'Menunggu Verifikasi', 'sudah_bayar' => 'Sudah Bayar', 'lunas' => 'Lunas', 'ditolak' => 'Ditolak'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('payment_status', $booking->payment_status) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status Booking</label>
                            <select name="booking_status" class="form-select" required>
                                @foreach (['pending' => 'Pending', 'confirmed' => 'Confirmed', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('booking_status', $booking->booking_status) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" rows="3" class="form-control">{{ old('notes', $booking->notes) }}</textarea>
                    </div>

                    <div class="alert alert-info">
                        Jika tanggal pasang/bongkar diubah, lama sewa, subtotal item, dan grand total akan dihitung ulang otomatis.
                    </div>

                    <button class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
