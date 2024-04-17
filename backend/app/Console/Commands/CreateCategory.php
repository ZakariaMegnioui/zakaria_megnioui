<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;

class CreateCategory extends Command
{
    
    protected $signature = 'category:create {name}';
    protected $description = 'Create a new category';
    public function handle()
    {
        $name = $this->argument('name');

        Category::create(['name' => $name]);

        $this->info('Category created successfully.');

        return 0;
    }
}
