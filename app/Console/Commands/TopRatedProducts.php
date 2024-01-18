<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Category;
use Illuminate\Support\Facades\DB;
class TopRatedProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'toprated:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating top rated products for each category and globally';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        // reset all top rated products
        $this->resetTopRated();
        // get categories with products with reviews
        $categories = Category::with(['products' => function ($query) {
            $query->select('id', 'category_id'); 
        }, 'products.reviews' => function ($query) {
            $query->select('id', 'product_id', 'rating');
        }])->select('id')->get();
         

        $allTopRated = [];
        
        // get all top rated products using reviews
        foreach ($categories as $category) {
            $topRated = $this->topRatedProducts($category);
            array_push($allTopRated, ...$topRated);
        }

        $this->markGlobalTopRated($allTopRated);

        $this->info('Top-rated products updated successfully.');
    }

    private function resetTopRated()
    {
        DB::table('category_product')->update([
            'top_rated' => 0,
            'top_rated_globally' => 0,
        ]);
    }

    private function topRatedProducts($category)
    {
        $topRated = $category->products
            ->sortByDesc('reviews.average_rating')
            ->take(5);

        // define top rated products for each category
        $this->updateTopRated($topRated, 'top_rated');

        return $topRated;
    }

    private function markGlobalTopRated($allTopRated)
    {
        $globalTopRated = collect($allTopRated)
            ->sortByDesc('reviews.average_rating')
            ->take(5);
        
        // define top rated products globally
        $this->updateTopRated($globalTopRated, 'top_rated_globally');
    }

    // updating pivot table function
    private function updateTopRated($products, $column)
    {
        try {
            DB::transaction(function () use ($products, $column) {
                foreach ($products as $product) {
                    $product->pivot->$column = 1;
                    $product->pivot->save();
                }
            });
        } catch (\Exception $e) {
            $this->error("An error occurred while updating $column: " . $e->getMessage());
        }
    }    
}
