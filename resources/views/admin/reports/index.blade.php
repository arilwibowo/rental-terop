<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan - Admin Rental Terop</title>
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
                <h1 class="h3 fw-bold mb-1">Laporan Rental</h1>
                <p class="text-muted mb-0">Booking per bulan, pendapatan, dan produk terlaris berdasarkan tanggal pasang.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
        </div>

        <div class="card content-card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Tahun</label>
                        <input type="number" name="year" class="form-control" value="{{ $year }}" min="2020" max="2100">
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary">Tampilkan</button>
                        <a href="{{ route('admin.reports.pdf', ['year' => $year]) }}" class="btn btn-danger">Export PDF</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card content-card">
                    <div class="card-body">
                        <div class="text-muted">Total Booking {{ $year }}</div>
                        <div class="display-6 fw-bold">{{ $totalBookings }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card content-card">
                    <div class="card-body">
                        <div class="text-muted">Pendapatan Sudah Dibayar</div>
                        <div class="display-6 fw-bold text-primary">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.reports.partials.tables')
    </main>
</body>
</html>
