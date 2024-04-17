<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    public const TABLE_NAME = 'products';
    public const PRIMARY_KEY_COLUMN = 'id';
    public const NAME_COLUMN = 'name';
    public const PRICE_COLUMN = 'price';
    public const DESCRIPTION_COLUMN = 'description';
    public const IMAGE_COLUMN = 'image'; 

    protected $guarded = [];
    /**
     * Getters
     */
    public function getName(): string
    {
        return $this->getAttribute(self::NAME_COLUMN);
    }

    public function getPrice(): float
    {
        return $this->getAttribute(self::PRICE_COLUMN);
    }

    public function getDescription(): string
    {
        return $this->getAttribute(self::DESCRIPTION_COLUMN);
    }

    public function getImage(): string
    {
        return $this->getAttribute(self::IMAGE_COLUMN);
    }

    /**
     * Setters
     */
    public function setName(string $name): self
    {
        $this->setAttribute(self::NAME_COLUMN, $name);
        return $this;
    }

    public function setPrice(float $price): self
    {
        $this->setAttribute(self::PRICE_COLUMN, $price);
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->setAttribute(self::DESCRIPTION_COLUMN, $description);
        return $this;
    }

    public function setImage(string $image): self
    {
        $this->setAttribute(self::IMAGE_COLUMN, $image);
        return $this;
    }

     /**
     * Relationships
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, CategoryProduct::TABLE_NAME, CategoryProduct::PRODUCT_ID_COLUMN, CategoryProduct::CATEGORY_ID_COLUMN);
    }

}
