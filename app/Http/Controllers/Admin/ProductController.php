<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Support\PublicImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['category', 'images'])
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $validated) {
            $uploadedImages = collect($request->file('images', []))
                ->map(fn ($image) => PublicImageStorage::store($image, 'products'))
                ->values();

            $product = Product::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $this->generateUniqueSlug($validated['name']),
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'image' => $uploadedImages->first(),
                'is_active' => $request->boolean('is_active'),
            ]);

            $uploadedImages->each(function (string $image, int $index) use ($product) {
                $product->images()->create([
                    'image' => $image,
                    'sort_order' => $index + 1,
                ]);
            });
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        $product->load('images');
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($product->id),
            ],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'images' => ['nullable', 'array', 'max:' . max(0, 5 - $product->images()->count())],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $validated, $product) {
            $product->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $this->generateUniqueSlug($validated['name'], $product->id),
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'is_active' => $request->boolean('is_active'),
            ]);

            $currentCount = $product->images()->count();

            foreach ($request->file('images', []) as $image) {
                if ($currentCount >= 5) {
                    break;
                }

                $path = PublicImageStorage::store($image, 'products');

                $product->images()->create([
                    'image' => $path,
                    'sort_order' => $currentCount + 1,
                ]);

                if (! $product->image) {
                    $product->update(['image' => $path]);
                }

                $currentCount++;
            }
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image) {
            PublicImageStorage::delete($product->image);
        }

        $product->load('images');
        foreach ($product->images as $image) {
            PublicImageStorage::delete($image->image);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function replaceImage(Request $request, ProductImage $productImage): RedirectResponse
    {
        $validated = $request->validate([
            'replacement_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        DB::transaction(function () use ($validated, $productImage) {
            $oldImage = $productImage->image;
            $newImage = PublicImageStorage::store($validated['replacement_image'], 'products');

            $productImage->update(['image' => $newImage]);

            if ($productImage->product->image === $oldImage) {
                $productImage->product->update(['image' => $newImage]);
            }

            PublicImageStorage::delete($oldImage);
        });

        return back()->with('success', 'Foto produk berhasil diganti.');
    }

    public function destroyImage(ProductImage $productImage): RedirectResponse
    {
        DB::transaction(function () use ($productImage) {
            $product = $productImage->product;
            $imagePath = $productImage->image;

            $productImage->delete();
            PublicImageStorage::delete($imagePath);

            if ($product->image === $imagePath) {
                $nextImage = $product->images()->first()?->image;
                $product->update(['image' => $nextImage]);
            }
        });

        return back()->with('success', 'Foto produk berhasil dihapus.');
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

}
