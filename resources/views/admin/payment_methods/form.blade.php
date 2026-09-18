<div class="mb-3">
    <label for="bank_name" class="form-label">Nama Bank</label>
    <input type="text" class="form-control @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name" value="{{ old('bank_name', $paymentMethod->bank_name ?? '') }}" required>
    @error('bank_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="account_number" class="form-label">Nomor Rekening</label>
    <input type="text" class="form-control @error('account_number') is-invalid @enderror" id="account_number" name="account_number" value="{{ old('account_number', $paymentMethod->account_number ?? '') }}" required>
    @error('account_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="account_holder" class="form-label">Nama Pemilik Rekening</label>
    <input type="text" class="form-control @error('account_holder') is-invalid @enderror" id="account_holder" name="account_holder" value="{{ old('account_holder', $paymentMethod->account_holder ?? '') }}" required>
    @error('account_holder')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="qris_image" class="form-label">Upload Gambar QRIS</label>
    <div id="qris-preview-wrap" class="mb-2 @if (! $paymentMethod?->qris_image) d-none @endif">
        <img
            id="qris-preview"
            src="{{ $paymentMethod?->qrisImageUrl() }}"
            alt="Preview QRIS"
            class="img-fluid rounded border"
            style="max-height: 180px;"
        >
    </div>
    <input type="file" class="form-control @error('qris_image') is-invalid @enderror" id="qris_image" name="qris_image" accept="image/*">
    <div class="form-text">Format JPG, PNG, WEBP. Maksimal 2MB.</div>
    @error('qris_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="form-check form-switch mb-4">
    <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $paymentMethod->is_active ?? true))>
    <label class="form-check-label" for="is_active">Aktif dan tampil di invoice</label>
</div>

<div class="d-flex gap-2">
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-light">Batal</a>
</div>

<script>
    document.getElementById('qris_image')?.addEventListener('change', (event) => {
        const image = event.target.files?.[0];
        if (! image) return;

        document.getElementById('qris-preview').src = URL.createObjectURL(image);
        document.getElementById('qris-preview-wrap').classList.remove('d-none');
    });
</script>
