<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_url',
        'image_public_id',
        'item_name',
        'category',
        'item_description',
        'uploaded_by',
        'address',
        'phone_number',
    ];
}
