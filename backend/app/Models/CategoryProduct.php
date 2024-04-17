<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryProduct extends Pivot
{
    use HasFactory;
    public const TABLE_NAME = 'category_product';
    public const PRIMARY_KEY_COLUMN = 'id';
    public const PRODUCT_ID_COLUMN = 'product_id';
    public const CATEGORY_ID_COLUMN = 'category_id';
    public const CREATED_AT_COLUMN = 'created_at';
    public const UPDATED_AT_COLUMN = 'updated_at';

    protected $guarded = [];
    
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, self::PRODUCT_ID_COLUMN);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, self::CATEGORY_ID_COLUMN);
    }
}
