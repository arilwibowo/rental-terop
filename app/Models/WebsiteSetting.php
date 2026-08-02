<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_whatsapp',
    ];

    public static function current(): self
    {
        return self::firstOrCreate([], [
            'admin_whatsapp' => '6281333561155',
        ]);
    }
}
