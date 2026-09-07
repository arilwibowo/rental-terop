<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Produk - Rental Terop Astika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f7fb; }
        .content-card { border: 0; border-radius: 18px; box-shadow: 0 10px 30px rgba(15, 23, 42, .08); }
        .product-preview { width: 160px; height: 120px; object-fit: cover; border-radius: 14px; background: #e5e7eb; }
        .gallery-preview { width: 100%; height: 140px; object-fit: cover; border-radius: 14px; background: #e5e7eb; }
    </style>
</head>
<body>
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 fw-bold mb-1">Edit Produk</h1>
                        <p class="text-muted mb-0">Perbarui data produk rental.</p>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Kembali</a>
                </div>

                <div class="card content-card">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">Kategori</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                        <option value="">Pilih kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama Produk</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control" id="slug" value="{{ $product->slug }}" disabled>
                                <div class="form-text">Slug dibuat otomatis dari nama produk.</div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Harga Sewa</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" min="0" step="1000" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label">Stok</label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="images" class="form-label">Tambah Foto Produk</label>
                                <input type="file" class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" id="images" name="images[]" accept="image/*" multiple @disabled($product->images->count() >= 5)>
                                <div class="form-text">
                                    Upload tambahan tanpa menghapus foto lama. Maksimal total 5 foto.
                                    Saat ini: {{ $product->images->count() ?: ($product->image ? 1 : 0) }} foto.
                                </div>
                                @error('images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $product->is_active))>
                                <label class="form-check-label" for="is_active">Produk aktif dan tampil di website</label>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-light">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card content-card mt-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1">Foto Produk</h5>
                        <p class="text-muted mb-4">Preview seluruh foto produk. Kamu bisa mengganti atau menghapus satu foto tanpa memengaruhi foto lainnya.</p>

                        @if ($product->images->isNotEmpty())
                            <div class="row g-3">
                                @foreach ($product->images as $image)
                                    <div class="col-md-4">
                                        <div class="border rounded-4 p-3 h-100">
                                            <img src="{{ $product->imageUrl($image->image) }}" class="gallery-preview mb-3" alt="{{ $product->name }}">

                                            <form action="{{ route('admin.products.images.replace', $image) }}" method="POST" enctype="multipart/form-data" class="mb-2">
                                                @csrf
                                                @method('PATCH')
                                                <input type="file" name="replacement_image" class="form-control form-control-sm mb-2" accept="image/*" required>
                                                <button type="submit" class="btn btn-warning btn-sm w-100">Ganti Foto</button>
                                            </form>

                                            <form action="{{ route('admin.products.images.destroy', $image) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus foto ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">Hapus Foto</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif ($product->image)
                            <div class="alert alert-info">
                                Produk ini masih memakai foto lama dari kolom <code>products.image</code>. Upload foto baru pada form edit di atas untuk mulai memakai fitur multiple images.
                            </div>
                            <img src="{{ $product->imageUrl() }}" class="product-preview" alt="{{ $product->name }}">
                        @else
                            <div class="alert alert-warning mb-0">Produk ini belum memiliki foto.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
