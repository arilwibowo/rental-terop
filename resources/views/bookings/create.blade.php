<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking {{ $product->name }} - Rental Terop Astika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; }
        .product-image { height: 260px; object-fit: cover; }
        .product-placeholder { height: 260px; background: linear-gradient(135deg, #e5e7eb, #f8fafc); }
        .content-card { border: 0; border-radius: 18px; box-shadow: 0 10px 30px rgba(15, 23, 42, .08); }
    </style>
</head>
<body>
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
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('products') }}">Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#kontak">Kontak</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="mb-4">
            <a href="{{ route('products') }}" class="btn btn-outline-secondary btn-sm">← Kembali ke Produk</a>
        </div>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card content-card overflow-hidden">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                    @else
                        <div class="product-placeholder d-flex align-items-center justify-content-center text-muted">
                            Belum ada gambar
                        </div>
                    @endif

                    <div class="card-body">
                        <span class="badge text-bg-primary mb-2">{{ $product->category->name ?? 'Tanpa Kategori' }}</span>
                        <h1 class="h4 fw-bold">{{ $product->name }}</h1>
                        <p class="text-muted">{{ $product->description ?: 'Produk rental siap digunakan untuk kebutuhan acara Anda.' }}</p>

                        <div class="d-flex justify-content-between border-top pt-3">
                            <div>
                                <div class="small text-muted">Harga Sewa</div>
                                <div class="fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            </div>
                            <div class="text-end">
                                <div class="small text-muted">Stok</div>
                                <div class="fw-bold">{{ $product->stock }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card content-card">
                    <div class="card-body p-4">
                        <h2 class="h4 fw-bold mb-1">Form Booking</h2>
                        <p class="text-muted mb-4">Isi data berikut untuk membuat invoice pemesanan.</p>

                        @if ($product->stock < 1)
                            <div class="alert alert-warning">
                                Stok produk ini sedang kosong.
                            </div>
                        @endif

                        <form action="{{ route('bookings.store', $product) }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="customer_name" class="form-label">Nama</label>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required autofocus>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone_number" class="form-label">Nomor HP</label>
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="Contoh: 081234567890" required>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="event_date" class="form-label">Tanggal Acara</label>
                                    <input type="date" class="form-control @error('event_date') is-invalid @enderror" id="event_date" name="event_date" value="{{ old('event_date') }}" min="{{ date('Y-m-d') }}" required>
                                    @error('event_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="quantity" class="form-label">Jumlah</label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" max="{{ max($product->stock, 1) }}" required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="alert alert-info">
                                Total akan dihitung otomatis dari harga produk dikali jumlah.
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100" @disabled($product->stock < 1)>
                                Buat Booking & Invoice
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
