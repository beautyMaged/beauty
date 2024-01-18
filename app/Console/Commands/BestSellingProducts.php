<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Category;
use Illuminate\Support\Facades\DB;
class BestSellingProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bestselling:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating best selling products for each category and globally';

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
        // reset all best selling products
        $this->resetBestSelling();
        // get categories with products with order details
        $categories = Category::with(['products' => function ($query) {
            $query->select('id', 'category_id'); 
        }, 'products.order_details' => function ($query) {
            $query->select('id', 'product_id');
        }])->select('id')->get();
         

        $allBestSelling = [];
        
        // get all best selling products using order details
        foreach ($categories as $category) {
            $bestSelling = $this->bestSellingProducts($category);
            array_push($allBestSelling, ...$bestSelling);
        }

        $this->markGlobalBestSelling($allBestSelling);

        $this->info('Best selling products updated successfully.');
    }

    private function resetBestSelling()
    {
        DB::table('category_product')->update([
            'best_selling' => 0,
            'best_selling_globally' => 0,
        ]);
    }

    private function bestSellingProducts($category)
    {
        $bestSelling = $category->products
            ->sortByDesc('order_details.count')
            ->take(5);

        // define best selling products for each category
        $this->updateBestSelling($bestSelling, 'best_selling');

        return $bestSelling;
    }

    private function markGlobalBestSelling($allBestSelling)
    {
        $globalBestSelling = collect($allBestSelling)
            ->sortByDesc('order_details.count')
            ->take(5);
        
        // define best selling products globally
        $this->updateBestSelling($globalBestSelling, 'best_selling_globally');
    }

    // updating pivot table function
    private function updateBestSelling($products, $column)
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
