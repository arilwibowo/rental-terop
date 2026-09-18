<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang - Rental Terop Astika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; }
        .product-thumb { width: 80px; height: 80px; object-fit: cover; border-radius: 12px; background: #e5e7eb; }

        .cart-mobile-item {
            padding: 1rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .cart-mobile-item:last-child {
            border-bottom: 0;
        }

        .cart-mobile-product {
            min-width: 0;
        }

        .cart-mobile-name {
            overflow-wrap: anywhere;
        }

        .cart-mobile-details {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .5rem;
            margin-top: 1rem;
        }

        .cart-mobile-details dt {
            margin-bottom: .2rem;
            color: #64748b;
            font-size: .72rem;
            font-weight: 600;
        }

        .cart-mobile-details dd {
            margin: 0;
            font-size: .84rem;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .cart-mobile-details .cart-qty {
            width: 100%;
            min-width: 0;
            padding: .35rem .45rem;
            font-size: .84rem;
        }

        @media (max-width: 767.98px) {
            main.container {
                padding: 2rem 1rem !important;
            }

            .cart-header {
                align-items: stretch !important;
                flex-direction: column;
                gap: 1rem;
            }

            .cart-header .btn,
            .cart-actions .btn {
                width: 100%;
            }

            .product-thumb {
                width: 64px;
                height: 64px;
                flex: 0 0 64px;
            }

            .cart-mobile-details {
                grid-template-columns: 1fr 72px 1fr;
            }
        }
    </style>
</head>
<body>
    @php
        $cartCount = array_sum(session('cart', []));
        $grandTotal = 0;
        $hasStockProblem = false;
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
                        <a class="nav-link active" href="{{ route('cart.index') }}">
                            Keranjang <span class="badge text-bg-light">{{ $cartCount }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="cart-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Keranjang</h1>
                <p class="text-muted mb-0">Ubah qty atau hapus produk sebelum checkout.</p>
            </div>
            <a href="{{ route('products') }}" class="btn btn-outline-primary">Tambah Produk Lagi</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($products->isEmpty())
            <div class="alert alert-info">
                Keranjang masih kosong. Silakan pilih produk terlebih dahulu.
            </div>
        @else
            <form action="{{ route('cart.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive d-none d-md-block">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th style="width: 140px;">Qty</th>
                                        <th class="text-end">Subtotal</th>
                                        <th style="width: 100px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        @php
                                            $qty = $cart[$product->id] ?? 1;
                                            $isOutOfStock = $product->stock < 1;
                                            $isQtyOverStock = $qty > $product->stock;
                                            $hasStockProblem = $hasStockProblem || $isOutOfStock || $isQtyOverStock;
                                            $subtotal = $product->price * $qty;
                                            $grandTotal += $subtotal;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    @if ($product->primaryImage())
                                                        <img src="{{ $product->imageUrl() }}" class="product-thumb" alt="{{ $product->name }}">
                                                    @else
                                                        <div class="product-thumb d-flex align-items-center justify-content-center text-muted small">No Img</div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-semibold">{{ $product->name }}</div>
                                                        <div class="small text-muted">{{ $product->slug }}</div>
                                                        @if ($isOutOfStock || $isQtyOverStock)
                                                            <span class="badge text-bg-danger">STOK HABIS</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $product->category->name ?? '-' }}</td>
                                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                            <td>
                                                <input
                                                    type="number"
                                                    name="qty[{{ $product->id }}]"
                                                    value="{{ $qty }}"
                                                    min="1"
                                                    max="{{ max($product->stock, 1) }}"
                                                    class="form-control cart-qty"
                                                    data-price="{{ $product->price }}"
                                                    data-product-id="{{ $product->id }}"
                                                    @disabled($isOutOfStock)
                                                >
                                                <div class="small text-muted">Stok: {{ $product->stock }}</div>
                                            </td>
                                            <td class="text-end cart-subtotal" data-subtotal-id="{{ $product->id }}">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                            <td>
                                                <button type="submit" form="remove-product-{{ $product->id }}" class="btn btn-danger btn-sm">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Total Sementara</th>
                                        <th class="text-end text-primary cart-grand-total">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mobile-cart-list d-md-none">
                            @foreach ($products as $product)
                                @php
                                    $qty = $cart[$product->id] ?? 1;
                                    $isOutOfStock = $product->stock < 1;
                                    $isQtyOverStock = $qty > $product->stock;
                                    $subtotal = $product->price * $qty;
                                @endphp
                                <article class="cart-mobile-item">
                                    <div class="d-flex align-items-center gap-3">
                                        @if ($product->primaryImage())
                                            <img src="{{ $product->imageUrl() }}" class="product-thumb" alt="{{ $product->name }}">
                                        @else
                                            <div class="product-thumb d-flex align-items-center justify-content-center text-muted small">No Img</div>
                                        @endif
                                        <div class="cart-mobile-product">
                                            <div class="cart-mobile-name fw-semibold">{{ $product->name }}</div>
                                            <div class="small text-muted">{{ $product->category->name ?? '-' }}</div>
                                            @if ($isOutOfStock || $isQtyOverStock)
                                                <span class="badge text-bg-danger mt-1">STOK HABIS</span>
                                            @endif
                                        </div>
                                    </div>

                                    <dl class="cart-mobile-details">
                                        <div>
                                            <dt>Harga</dt>
                                            <dd>Rp {{ number_format($product->price, 0, ',', '.') }}</dd>
                                        </div>
                                        <div>
                                            <dt>Jumlah</dt>
                                            <dd>
                                                <input
                                                    type="number"
                                                    name="qty[{{ $product->id }}]"
                                                    value="{{ $qty }}"
                                                    min="1"
                                                    max="{{ max($product->stock, 1) }}"
                                                    class="form-control cart-qty"
                                                    data-price="{{ $product->price }}"
                                                    data-product-id="{{ $product->id }}"
                                                    @disabled($isOutOfStock)
                                                >
                                            </dd>
                                            <div class="small text-muted mt-1">Stok: {{ $product->stock }}</div>
                                        </div>
                                        <div>
                                            <dt>Subtotal</dt>
                                            <dd class="cart-subtotal" data-subtotal-id="{{ $product->id }}">Rp {{ number_format($subtotal, 0, ',', '.') }}</dd>
                                        </div>
                                    </dl>

                                    <button type="submit" form="remove-product-{{ $product->id }}" class="btn btn-outline-danger btn-sm w-100 mt-3">
                                        Hapus Produk
                                    </button>
                                </article>
                            @endforeach
                        </div>

                        <div class="d-flex d-md-none justify-content-between align-items-center border-top pt-3 mt-2 fw-bold">
                            <span>Total Sementara</span>
                            <span class="text-primary cart-grand-total">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>

                        <div class="cart-actions d-flex flex-column flex-md-row justify-content-between gap-2 mt-3">
                            <div></div>
                            @if ($hasStockProblem)
                                <button type="button" class="btn btn-primary" disabled>Lanjut Checkout</button>
                            @else
                                <a href="{{ route('checkout.index') }}" class="btn btn-primary">Lanjut Checkout</a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>

            @foreach ($products as $product)
                <form id="remove-product-{{ $product->id }}" action="{{ route('cart.remove', $product->slug) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        @endif
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const qtyInputs = Array.from(document.querySelectorAll('.cart-qty'));
        const updateUrl = "{{ route('cart.update') }}";
        const csrfToken = "{{ csrf_token() }}";
        let saveTimer = null;

        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(value);
        }

        function updateCartDisplay() {
            let grandTotal = 0;

            uniqueQtyInputs().forEach((input) => {
                const price = Number(input.dataset.price);
                const qty = input.value === '' ? 0 : Number(input.value);
                const subtotal = price * qty;

                grandTotal += subtotal;
                document.querySelectorAll(`[data-subtotal-id="${input.dataset.productId}"]`).forEach((subtotalCell) => {
                    subtotalCell.textContent = formatRupiah(subtotal);
                });
            });

            document.querySelectorAll('.cart-grand-total').forEach((grandTotalCell) => {
                grandTotalCell.textContent = formatRupiah(grandTotal);
            });
        }

        function uniqueQtyInputs() {
            return qtyInputs.filter((input, index, inputs) =>
                inputs.findIndex((candidate) => candidate.dataset.productId === input.dataset.productId) === index
            );
        }

        function syncProductQty(productId, value) {
            qtyInputs
                .filter((input) => input.dataset.productId === productId)
                .forEach((input) => {
                    input.value = value;
                });
        }

        function collectQtyData() {
            const data = new FormData();
            data.append('_method', 'PATCH');

            uniqueQtyInputs().forEach((input) => {
                data.append(`qty[${input.dataset.productId}]`, input.value || 1);
            });

            return data;
        }

        function hasInvalidQty() {
            return uniqueQtyInputs().some((input) => {
                if (input.value === '') {
                    return true;
                }

                const min = Number(input.min || 1);
                const max = Number(input.max || min);
                const value = Number(input.value);

                return Number.isNaN(value) || value < min || value > max;
            });
        }

        function saveCart() {
            if (hasInvalidQty()) {
                return;
            }

            fetch(updateUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: collectQtyData(),
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Gagal menyimpan keranjang.');
                    }
                })
                .catch(() => {});
        }

        qtyInputs.forEach((input) => {
            input.addEventListener('input', () => {
                if (input.value === '') {
                    syncProductQty(input.dataset.productId, '');
                    updateCartDisplay();
                    clearTimeout(saveTimer);
                    return;
                }

                const min = Number(input.min || 1);
                const max = Number(input.max || min);
                let value = Number(input.value);

                if (value < min) value = min;
                if (value > max) value = max;
                syncProductQty(input.dataset.productId, value);

                updateCartDisplay();

                clearTimeout(saveTimer);
                saveTimer = setTimeout(saveCart, 500);
            });

            input.addEventListener('blur', () => {
                if (input.value === '') {
                    syncProductQty(input.dataset.productId, input.min || 1);
                    updateCartDisplay();
                    saveCart();
                }
            });
        });
    </script>
</body>
</html>
