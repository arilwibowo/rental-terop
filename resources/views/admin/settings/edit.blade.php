<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan Website - Admin Rental Terop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 fw-bold mb-1">Pengaturan Website</h1>
                        <p class="text-muted mb-0">Nomor WhatsApp admin untuk tombol customer.</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="admin_whatsapp" class="form-label">Nomor WhatsApp Admin</label>
                                <input type="text" class="form-control @error('admin_whatsapp') is-invalid @enderror" id="admin_whatsapp" name="admin_whatsapp" value="{{ old('admin_whatsapp', $setting->admin_whatsapp) }}" required>
                                <div class="form-text">Gunakan format 62, contoh: 6281234567890.</div>
                                @error('admin_whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button class="btn btn-primary">Simpan Pengaturan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
