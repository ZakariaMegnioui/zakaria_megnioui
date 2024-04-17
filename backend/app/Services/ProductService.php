<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function paginateProducts(array $params): LengthAwarePaginator
    {
        return $this->productRepository->paginate($params);
    }
   
    public function createProduct($data)
    {
        $categories = $data['categories'];
        unset($data['categories']);

        return $this->productRepository->create($data , $categories );
    }
    public function updateProduct(int $id, array $data): Product
    {
        $categories = $data['categories'];
        unset($data['categories']);
        return $this->productRepository->update($id, $data , $categories );
    }
    public function deleteProduct(int $id): bool
    {
        return $this->productRepository->delete($id);
    }
    public function getProductById(int $id): Product
    {
        return $this->productRepository->findById($id);
    }
}
