<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout - Rental Terop Astika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    @php
        $cartCount = array_sum(session('cart', []));
        $grandTotal = 0;
        $checkoutCustomer = session('checkout_customer', []);
        $hasStockProblem = isset($stockErrors) && $stockErrors->isNotEmpty();
        $rentalStartDate = old('rental_start_date', $checkoutCustomer['rental_start_date'] ?? '');
        $rentalEndDate = old('rental_end_date', $checkoutCustomer['rental_end_date'] ?? '');
        $rentalDays = 1;

        if ($rentalStartDate && $rentalEndDate && strtotime($rentalStartDate) && strtotime($rentalEndDate)) {
            $start = \Carbon\Carbon::parse($rentalStartDate);
            $end = \Carbon\Carbon::parse($rentalEndDate);
            $rentalDays = max(1, $start->diffInDays($end));
        }
    @endphp

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
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            Keranjang <span class="badge text-bg-light">{{ $cartCount }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="mb-4">
            <h1 class="h3 fw-bold mb-1">Checkout</h1>
            <p class="text-muted mb-0">Data produk diambil dari keranjang. Invoice belum dibuat pada tahap ini.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($hasStockProblem)
            <div class="alert alert-danger">
                Maaf, stok produk sedang habis atau tidak tersedia pada tanggal yang dipilih.
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Data Customer</h5>

                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="customer_name" class="form-label">Nama</label>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name', $checkoutCustomer['customer_name'] ?? '') }}" required>
                                    @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone_number" class="form-label">Nomor HP</label>
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number', $checkoutCustomer['phone_number'] ?? '') }}" required>
                                    @error('phone_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-muted">(opsional)</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $checkoutCustomer['email'] ?? '') }}">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required>{{ old('address', $checkoutCustomer['address'] ?? '') }}</textarea>
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="event_location" class="form-label">Lokasi Acara</label>
                                <input type="text" class="form-control @error('event_location') is-invalid @enderror" id="event_location" name="event_location" value="{{ old('event_location', $checkoutCustomer['event_location'] ?? '') }}" required>
                                @error('event_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="rental_start_date" class="form-label">Tanggal Mulai Sewa</label>
                                    <input type="date" class="form-control @error('rental_start_date') is-invalid @enderror" id="rental_start_date" name="rental_start_date" value="{{ $rentalStartDate }}" min="{{ date('Y-m-d') }}" required>
                                    @error('rental_start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="rental_end_date" class="form-label">Tanggal Selesai Sewa</label>
                                    <input type="date" class="form-control @error('rental_end_date') is-invalid @enderror" id="rental_end_date" name="rental_end_date" value="{{ $rentalEndDate }}" min="{{ date('Y-m-d') }}" required>
                                    @error('rental_end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="alert alert-info">
                                Lama sewa: <strong><span id="rental-days">{{ $rentalDays }}</span> hari</strong>.
                                Perhitungan: Harga per hari x Qty x Lama sewa.
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label">Catatan</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $checkoutCustomer['notes'] ?? '') }}</textarea>
                                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100" @disabled($hasStockProblem)>
                                Simpan Data Checkout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Ringkasan Keranjang</h5>

                        @foreach ($products as $product)
                            @php
                                $qty = $cart[$product->id] ?? 1;
                                $subtotal = $product->price * $qty * $rentalDays;
                                $grandTotal += $subtotal;
                            @endphp
                            <div class="d-flex justify-content-between border-bottom py-2 checkout-item" data-price="{{ $product->price }}" data-qty="{{ $qty }}">
                                <div>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    @if ($product->stock < ($cart[$product->id] ?? 0))
                                        <span class="badge text-bg-danger">STOK HABIS</span>
                                    @endif
                                    <div class="small text-muted">
                                        {{ $qty }} x Rp {{ number_format($product->price, 0, ',', '.') }} / hari
                                    </div>
                                    <div class="small text-muted">
                                        Subtotal = Harga per hari x Qty x <span class="item-days">{{ $rentalDays }}</span> hari
                                    </div>
                                </div>
                                <div class="fw-semibold item-subtotal">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-between pt-3">
                            <div class="fw-bold">Grand Total</div>
                            <div class="fw-bold text-primary" id="grand-total">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
                        </div>

                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-3">
                            Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const startInput = document.getElementById('rental_start_date');
        const endInput = document.getElementById('rental_end_date');
        const rentalDaysText = document.getElementById('rental-days');
        const grandTotalText = document.getElementById('grand-total');
        const items = document.querySelectorAll('.checkout-item');

        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(value);
        }

        function calculateRentalDays() {
            if (!startInput.value || !endInput.value) {
                return 1;
            }

            const startDate = new Date(startInput.value + 'T00:00:00');
            const endDate = new Date(endInput.value + 'T00:00:00');
            const diffTime = endDate - startDate;
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

            return Math.max(1, diffDays);
        }

        function updateTotals() {
            const rentalDays = calculateRentalDays();
            let grandTotal = 0;

            rentalDaysText.textContent = rentalDays;

            items.forEach((item) => {
                const price = Number(item.dataset.price);
                const qty = Number(item.dataset.qty);
                const subtotal = price * qty * rentalDays;

                grandTotal += subtotal;
                item.querySelector('.item-subtotal').textContent = formatRupiah(subtotal);
                item.querySelector('.item-days').textContent = rentalDays;
            });

            grandTotalText.textContent = formatRupiah(grandTotal);
        }

        startInput.addEventListener('change', () => {
            if (startInput.value) {
                endInput.min = startInput.value;

                if (endInput.value && endInput.value < startInput.value) {
                    endInput.value = startInput.value;
                }
            }

            updateTotals();
        });

        endInput.addEventListener('change', updateTotals);
        updateTotals();
    </script>
</body>
</html>
