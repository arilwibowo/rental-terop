<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->limit(6)
            ->get();

        return view('home', compact('products'));
    }

    public function products(Request $request): View
    {
        $categories = Category::withCount([
            'products' => fn ($query) => $query->where('is_active', true),
        ])->orderBy('name')->get();

        $products = Product::with('category')
            ->where('is_active', true)
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', function ($categoryQuery) use ($request) {
                    $categoryQuery->where('slug', $request->category);
                });
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $selectedCategory = $request->category;

        return view('products', compact('categories', 'products', 'selectedCategory'));
    }
}
