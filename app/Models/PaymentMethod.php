<?php

namespace App\Models;

use App\Support\PublicImageStorage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'account_number',
        'account_holder',
        'qris_image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function qrisImageUrl(): ?string
    {
        return PublicImageStorage::url($this->qris_image);
    }
}
