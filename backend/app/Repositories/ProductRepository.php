<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public function paginate(array $params): LengthAwarePaginator
    {

        $query = Product::orderBy('created_at','desc');
        
        if (isset($params['search'])) {
            $query->where('name', 'like', '%' . $params['search'] . '%');
        }
        if (isset($params['category'])) {
            $query->whereHas('categories', function ($query) use ($params) {
                $query->where('name',  $params['category'] );
            });
        }
        if (isset($params['sort_by_name'])) {
            $query->orderBy('name', $params['sort_by_name']);
        }
        if (isset($params['sort_by_price'])) {
            $query->orderBy('price', $params['sort_by_price']);
        }

      

        return $query->paginate($params['per_page'] ?? 10);
    }

    public function create($data, $categories)
    {
        $product = Product::create($data);
        $product->categories()->attach($categories);
        return $product;
    }
    public function update(int $id, array $data, $categories): Product
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        $product->categories()->sync($categories);
        return $product;
    }
    public function delete(int $id): bool
    {
        $product = Product::findOrFail($id);
        $product->categories()->detach();

        return $product->delete();
    }
    public function findById(int $id): Product
    {
        return Product::findOrFail($id);
    }
}
