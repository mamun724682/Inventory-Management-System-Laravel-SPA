<?php

namespace App\Models;

use App\Helpers\BaseHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const PHOTO_PATH = "products";

    protected $casts = [
        "buying_price"  => "double",
        "selling_price" => "double",
        "quantity"      => "integer",
    ];

    protected function photo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => BaseHelper::storageLink(
                fileName: $value,
                folderPath: self::PHOTO_PATH
            ),
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
