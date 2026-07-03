<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $booking->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #d1d5db; padding: 8px; }
        th { background: #f3f4f6; }
        .no-border td { border: 0; padding: 3px 0; }
        .right { text-align: right; }
        .center { text-align: center; }
        .muted { color: #6b7280; }
        .title { font-size: 20px; font-weight: bold; }
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

    <table class="no-border">
        <tr>
            <td>
                <div class="title">Rental Terop Astika</div>
                <div class="muted">Invoice Booking Rental</div>
            </td>
            <td class="right">
                <strong>{{ $booking->invoice_number }}</strong><br>
                {{ str_replace('_', ' ', strtoupper($booking->payment_status)) }}
            </td>
        </tr>
    </table>

    <table class="no-border">
        <tr>
            <td>
                <strong>Customer</strong><br>
                {{ $booking->customer_name }}<br>
                {{ $booking->phone_number }}<br>
                {{ $booking->address }}
            </td>
            <td>
                <strong>Jadwal Sewa</strong><br>
                Tanggal Pasang: {{ ($booking->rental_start_date ?? $booking->event_date)->format('d/m/Y') }}<br>
                Tanggal Bongkar: {{ ($booking->rental_end_date ?? $booking->event_date)->format('d/m/Y') }}<br>
                Lama Sewa: {{ $booking->rental_days ?? 1 }} hari
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th class="center">Qty</th>
                <th class="right">Harga per Hari</th>
                <th class="center">Lama Sewa</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoiceItems as $item)
                <tr>
                    <td>{{ $item->product->name ?? '-' }}</td>
                    <td class="center">{{ $item->quantity }}</td>
                    <td class="right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="center">{{ $item->rental_days }} hari</td>
                    <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="right">Grand Total</th>
                <th class="right">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <p class="muted">Dicetak pada {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>
