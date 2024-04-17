<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    public const TABLE_NAME = 'categories';
    public const PRIMARY_KEY_COLUMN = 'id';
    public const NAME_COLUMN = 'name';
    public const PARENT_ID_COLUMN = 'parent_id';
    public const CREATED_AT_COLUMN = 'created_at';
    public const UPDATED_AT_COLUMN = 'updated_at';

    protected $guarded = [];

  
    public function subcategories(): HasMany
    {
        return $this->hasMany(Category::class, self::PARENT_ID_COLUMN);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, CategoryProduct::TABLE_NAME, CategoryProduct::CATEGORY_ID_COLUMN, CategoryProduct::PRODUCT_ID_COLUMN);
    }

    /**
     * Getters
     */
    public function getName(): string
    {
        return $this->getAttribute(self::NAME_COLUMN);
    }

    public function getParentId(): ?int
    {
        return $this->getAttribute(self::PARENT_ID_COLUMN);
    }

    public function getCreatedAt(): ?string
    {
        return $this->getAttribute(self::CREATED_AT_COLUMN);
    }

    public function getUpdatedAt(): ?string
    {
        return $this->getAttribute(self::UPDATED_AT_COLUMN);
    }

    /**
     * Setters
     */
    public function setName(string $name): self
    {
        $this->setAttribute(self::NAME_COLUMN, $name);
        return $this;
    }

    public function setParentId(?int $parentId): self
    {
        $this->setAttribute(self::PARENT_ID_COLUMN, $parentId);
        return $this;
    }

      /**
     * Relationships
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, self::PARENT_ID_COLUMN);
    }


}
