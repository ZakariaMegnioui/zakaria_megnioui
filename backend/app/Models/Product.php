<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    public const TABLE_NAME = 'products';
    public const PRIMARY_KEY_COLUMN = 'id';
    public const NAME_COLUMN = 'name';
    public const PRICE_COLUMN = 'price';
    public const DESCRIPTION_COLUMN = 'description';

    protected $guarded = [];

  
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, CategoryProduct::TABLE_NAME, CategoryProduct::PRODUCT_ID_COLUMN, CategoryProduct::CATEGORY_ID_COLUMN);
    }

}
