<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalCategories' => DB::table('categories')->count(),
            'totalProducts' => DB::table('products')->count(),
            'activeProducts' => DB::table('products')->where('is_active', true)->count(),
            'latestProducts' => DB::table('products')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->select(
                    'products.name',
                    'products.price',
                    'products.stock',
                    'products.is_active',
                    'categories.name as category_name'
                )
                ->latest('products.created_at')
                ->limit(5)
                ->get(),
        ]);
    }
}
