<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Produk - Rental Terop Astika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f7fb; }
        .content-card { border: 0; border-radius: 18px; box-shadow: 0 10px 30px rgba(15, 23, 42, .08); }
    </style>
</head>
<body>
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 fw-bold mb-1">Tambah Produk</h1>
                        <p class="text-muted mb-0">Tambahkan produk rental baru.</p>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Kembali</a>
                </div>

                @if ($categories->isEmpty())
                    <div class="alert alert-warning">
                        Kamu belum punya kategori. Buat kategori dulu sebelum menambahkan produk.
                        <a href="{{ route('admin.categories.create') }}" class="alert-link">Tambah kategori</a>.
                    </div>
                @endif

                <div class="card content-card">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">Kategori</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                        <option value="">Pilih kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
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
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Terop 4x6" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Deskripsi singkat produk">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Harga Sewa</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', 0) }}" min="0" step="1000" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label">Stok</label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="images" class="form-label">Foto Produk</label>
                                <input type="file" class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" id="images" name="images[]" accept="image/*" multiple>
                                <div class="form-text">Pilih maksimal 5 foto. Format: JPG, PNG, WEBP. Maksimal 2MB per foto.</div>
                                @error('images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', true))>
                                <label class="form-check-label" for="is_active">Produk aktif dan tampil di website</label>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" @disabled($categories->isEmpty())>Simpan</button>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-light">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
