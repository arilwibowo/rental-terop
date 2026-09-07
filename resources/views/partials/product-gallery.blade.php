@php
    $galleryImages = $product->galleryImages();
    $mainImage = $galleryImages->first();
    $galleryId = $galleryId ?? 'product-gallery-' . $product->id;
@endphp

@if ($mainImage)
    <img
        id="{{ $galleryId }}"
        src="{{ $product->imageUrl($mainImage) }}"
        class="card-img-top product-image"
        alt="{{ $product->name }}"
    >

    @if ($galleryImages->count() > 1)
        <div class="product-thumbnails d-flex gap-2 p-2">
            @foreach ($galleryImages as $image)
                <button
                    type="button"
                    class="product-thumbnail border-0 p-0 bg-transparent"
                    data-gallery-target="{{ $galleryId }}"
                    data-image-src="{{ $product->imageUrl($image) }}"
                    aria-label="Lihat foto {{ $product->name }}"
                >
                    <img src="{{ $product->imageUrl($image) }}" alt="{{ $product->name }}">
                </button>
            @endforeach
        </div>
    @endif
@else
    <div class="product-placeholder d-flex align-items-center justify-content-center text-muted">
        Belum ada gambar
    </div>
@endif
