<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class DeleteProduct extends Command
{
    protected $signature = 'product:delete {id}';
    protected $description = 'Delete a product by ID';
    public function handle()
    {
        $id = $this->argument('id');
        $product = Product::findOrFail($id);
        $product->categories()->detach();

        $product->delete();

        $this->info('Product deleted successfully.');

        return 0;
    }
}
