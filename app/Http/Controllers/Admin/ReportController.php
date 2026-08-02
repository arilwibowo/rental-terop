<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    private const PAID_STATUSES = ['sudah_bayar', 'lunas'];

    public function index(Request $request): View
    {
        return view('admin.reports.index', $this->reportData($request));
    }

    public function exportPdf(Request $request): Response
    {
        $data = $this->reportData($request);
        $pdf = Pdf::loadView('admin.reports.pdf', $data)->setPaper('a4', 'portrait');

        return $pdf->download('laporan-rental-terop-' . now()->format('YmdHis') . '.pdf');
    }

    private function reportData(Request $request): array
    {
        $year = (int) $request->input('year', now()->year);
        $scheduleDateColumn = 'COALESCE(rental_start_date, event_date)';
        $qualifiedScheduleDateColumn = 'COALESCE(bookings.rental_start_date, bookings.event_date)';

        $monthlyBookings = Booking::selectRaw("MONTH($scheduleDateColumn) as month, COUNT(*) as total_booking")
            ->whereYear(DB::raw($scheduleDateColumn), $year)
            ->groupByRaw("MONTH($scheduleDateColumn)")
            ->pluck('total_booking', 'month');

        $monthlyRevenue = Booking::selectRaw("MONTH($scheduleDateColumn) as month, SUM(total_price) as total_revenue")
            ->whereYear(DB::raw($scheduleDateColumn), $year)
            ->whereIn('payment_status', self::PAID_STATUSES)
            ->groupByRaw("MONTH($scheduleDateColumn)")
            ->pluck('total_revenue', 'month');

        $months = collect(range(1, 12))->map(function ($month) use ($monthlyBookings, $monthlyRevenue) {
            return [
                'month' => $month,
                'month_name' => now()->month($month)->translatedFormat('F'),
                'total_booking' => (int) ($monthlyBookings[$month] ?? 0),
                'total_revenue' => (float) ($monthlyRevenue[$month] ?? 0),
            ];
        });

        $totalBookings = Booking::whereYear(DB::raw($scheduleDateColumn), $year)->count();
        $totalRevenue = Booking::whereYear(DB::raw($scheduleDateColumn), $year)
            ->whereIn('payment_status', self::PAID_STATUSES)
            ->sum('total_price');

        $paidStatusesSql = collect(self::PAID_STATUSES)
            ->map(fn ($status) => DB::getPdo()->quote($status))
            ->implode(',');

        $bestSellingProducts = DB::table('booking_items')
            ->join('bookings', 'booking_items.booking_id', '=', 'bookings.id')
            ->join('products', 'booking_items.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(booking_items.quantity) as total_quantity'),
                DB::raw("SUM(CASE WHEN bookings.payment_status IN ($paidStatusesSql) THEN booking_items.subtotal ELSE 0 END) as total_revenue")
            )
            ->whereYear(DB::raw($qualifiedScheduleDateColumn), $year)
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        return compact('year', 'months', 'totalBookings', 'totalRevenue', 'bestSellingProducts');
    }
}
