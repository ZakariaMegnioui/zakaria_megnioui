<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

   
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, CategoryProduct::TABLE_NAME, CategoryProduct::PRODUCT_ID_COLUMN, CategoryProduct::CATEGORY_ID_COLUMN);
    }
}
