<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Model\Category;
use App\Model\Seller;
use App\Model\Product;
use App\Model\Admin;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CommissionControllerTest extends TestCase
{
    /** @test */
    public function updateCategoryCommissionTest()
    {
        $this->actingAsAdmin();

        $category = Category::first();
        
        $data = ['category_commission'=> 10 ];

        $response = $this->patch('admin/category-commission/'.$category->id, $data);
        // $response = $this->json('PATCH', route('admin.updateCategoryCommission', ['id' => $category->id]), [
        //     'category_commission' => 10,
        // ]);
 
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'commission edited',
                'category' => $category->name,
                'commission' => 10,
            ]);
    }

    /** @test */
    public function setSellerCategoryCommissionTest()
    {
        $this->actingAsAdmin();

        $category = Category::first();
        
        $seller =  Seller::first();

        $data = [ 'commission' => 10];

        $response = $this->post('admin/category-commission/'.$category->id.'/seller/'.$seller->id, $data);
        Log::info('admin/category-commission/'.$category->id.'/seller/'.$seller->id);
        
        // $response = $this->json('POST', route('admin.setSellerCategoryCommission', ['cId' => $category->id, 'sId' => $seller->id]), [
        //     'commission' => 20,
        // ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'commission set',
                'category' => $category->name,
                'seller' => $seller->f_name . " " . $seller->l_name,
            ]);
    }

    /** @test */
    public function updateSellerCategoryCommissionTest()
    {
        $this->actingAsAdmin();

        $category = Category::first();
        
        $seller =  Seller::first();

        $data = [ 'commission' => 10];

        $response = $this->patch('admin/category-commission/'.$category->id.'/seller/'.$seller->id, $data);
 

        // $response = $this->json('PATCH', route('admin.updateSellerCategoryCommission', ['cId' => $category->id, 'sId' => $seller->id]), [
        //     'commission' => 15,
        // ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'commission edited',
                'category' => $category->name,
                'seller' => $seller->f_name . " " . $seller->l_name,
            ]);
    }



    /** @test */
    public function updateProductCommissionTest()
    {
        $this->actingAsAdmin();

        $product = Product::first();
        
        $data = ['product_commission' => 25];

        $response = $this->patch('admin/product-commission/'.$product->id, $data);

 

        // $response = $this->json('PATCH', route('admin.updateProductCommission', ['id' => $product->id]), [
        //     'product_commission' => 25,
        // ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Product commission updated',
                'product_name' => $product->name,
                'product_commission' => 25,
            ]);
    }
    /** @test */
    public function deleteSellerCategoryCommissionTest(){
        $this->actingAsAdmin();
        $category = Category::first();
        
        $seller =  Seller::first();

        $response = $this->delete('admin/category-commission/'.$category->id.'/seller/'.$seller->id);

        $response->assertStatus(200)
        ->assertJson([
            'message' => 'commission deleted successfully'
        ]);

    }

    private function actingAsAdmin()
    {
        $admin = Admin::take(1)->first();
        $this->actingAs($admin,'admin');

    }

    

}
