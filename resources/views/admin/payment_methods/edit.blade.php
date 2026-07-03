<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Pembayaran - Admin Rental Terop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 fw-bold mb-1">Edit Pembayaran</h1>
                        <p class="text-muted mb-0">Perbarui data rekening bank atau QRIS statis.</p>
                    </div>
                    <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-outline-secondary">Kembali</a>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.payment-methods.update', $paymentMethod) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('admin.payment_methods.form', ['paymentMethod' => $paymentMethod])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
