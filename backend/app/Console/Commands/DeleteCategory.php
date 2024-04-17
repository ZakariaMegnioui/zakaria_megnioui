<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;

class DeleteCategory extends Command
{
    protected $signature = 'category:delete {id}';
    protected $description = 'Delete a category by ID';
    public function handle()
    {
        $id = $this->argument('id');
        $category = Category::findOrFail($id);
        $category->products()->detach();

        $category->delete();
       

        $this->info('Category deleted successfully.');

        return 0;
    }
}
