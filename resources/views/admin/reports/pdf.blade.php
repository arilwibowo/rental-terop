<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Rental Terop {{ $year }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        h1, h2, h3 { margin: 0; }
        .muted { color: #6b7280; }
        .summary { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #d1d5db; padding: 8px; }
        th { background: #f3f4f6; }
        .right { text-align: right; }
        .center { text-align: center; }
        .section { margin-top: 24px; }
    </style>
</head>
<body>
    <h1>Laporan Rental Terop Astika</h1>
    <p class="muted">Tahun {{ $year }} - berdasarkan Tanggal Pasang / Tanggal Acara</p>

    <div class="summary">
        <p><strong>Total Booking:</strong> {{ $totalBookings }}</p>
        <p><strong>Pendapatan Sudah Dibayar:</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>

    <div class="section">
        <h3>Booking per Bulan & Pendapatan</h3>
        <p class="muted">Dihitung berdasarkan Tanggal Pasang / Tanggal Acara, bukan tanggal transaksi.</p>
        <table>
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th class="center">Jumlah Booking</th>
                    <th class="right">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($months as $month)
                    <tr>
                        <td>{{ $month['month_name'] }}</td>
                        <td class="center">{{ $month['total_booking'] }}</td>
                        <td class="right">Rp {{ number_format($month['total_revenue'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Produk Terlaris</h3>
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="center">Total Jumlah Dibooking</th>
                    <th class="right">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bestSellingProducts as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td class="center">{{ $product->total_quantity }}</td>
                        <td class="right">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="center">Belum ada data produk terlaris.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
