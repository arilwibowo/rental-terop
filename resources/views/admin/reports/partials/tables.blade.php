<div class="card content-card mb-4">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Booking per Bulan & Pendapatan</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Bulan</th>
                        <th class="text-center">Jumlah Booking</th>
                        <th class="text-end">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($months as $month)
                        <tr>
                            <td>{{ $month['month_name'] }}</td>
                            <td class="text-center">{{ $month['total_booking'] }}</td>
                            <td class="text-end">Rp {{ number_format($month['total_revenue'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card content-card">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Produk Terlaris</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th class="text-center">Total Jumlah Dibooking</th>
                        <th class="text-end">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bestSellingProducts as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td class="text-center">{{ $product->total_quantity }}</td>
                            <td class="text-end">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Belum ada data produk terlaris.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
