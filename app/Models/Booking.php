<?php

namespace App\Models;

use App\Support\PublicImageStorage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'invoice_number',
        'customer_name',
        'phone_number',
        'customer_email',
        'address',
        'event_location',
        'notes',
        'event_date',
        'rental_start_date',
        'rental_end_date',
        'rental_days',
        'quantity',
        'unit_price',
        'total_price',
        'payment_status',
        'payment_proof',
        'payment_verified_at',
        'payment_rejection_reason',
        'booking_status',
        'payment_method_id',
    ];

    protected $casts = [
        'event_date' => 'date',
        'rental_start_date' => 'date',
        'rental_end_date' => 'date',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'payment_verified_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function paymentProofUrl(): ?string
    {
        return PublicImageStorage::url($this->payment_proof);
    }
}
