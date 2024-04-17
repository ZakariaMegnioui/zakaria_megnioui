<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\ProductService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {

        $params = $request->only(['per_page', 'search', 'category', 'sort_by_price', 'sort_by_name']);


        $products = $this->productService->paginateProducts($params);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data  = $request->all();
            $product = $this->productService->createProduct($data);

            return new ProductResource($product);
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), [

                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return new ProductResource($this->productService->getProductById($id));
        }catch (\Throwable $e) {
            Log::error($e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            return new ProductResource($this->productService->updateProduct($id, $request->all()));
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), [

                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $isDeleted = $this->productService->deleteProduct($id);

            return response(['success' => $isDeleted]);
        }catch (\Throwable $e) {
            Log::error($e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        
    }
}
