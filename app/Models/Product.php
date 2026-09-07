<?php

namespace App\Models;

use App\Support\PublicImageStorage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function galleryImages(): Collection
    {
        if ($this->relationLoaded('images') && $this->images->isNotEmpty()) {
            return $this->images->pluck('image');
        }

        if ($this->image) {
            return collect([$this->image]);
        }

        return collect();
    }

    public function primaryImage(): ?string
    {
        return $this->galleryImages()->first();
    }

    public function imageUrl(?string $image = null): ?string
    {
        $image ??= $this->primaryImage();

        if (! $image) {
            return null;
        }

        return PublicImageStorage::url($image);
    }
}
