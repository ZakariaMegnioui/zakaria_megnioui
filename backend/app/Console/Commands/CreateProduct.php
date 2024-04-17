<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class CreateProduct extends Command
{
    protected $signature = 'product:create {name} {description} {price} {image}';
    protected $description = 'Create a new product';
    public function handle()
    {
        $name = $this->argument('name');
        $description = $this->argument('description');
        $price = $this->argument('price');
        $image = $this->argument('image');


        Product::create([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'image' => $image,
        ]);

        $this->info('Product created successfully.');

        return 0;
    }
}
