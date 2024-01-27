<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use App\Model\Seller;
use App\Model\Admin;
use App\Model\BestProduct;
use App\Model\Product;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BestProductsTest extends TestCase
{
    use WithFaker;

    // unauthenticated
    public function testIndexUnAuthorized()
    {

        $response = $this->get('/seller/best-products');
        
        $response = $this->followRedirects($response);
        
        $response->assertStatus(401);
    }

    public function testIndex()
    {
        $this->actingAsAdmin();

        $response = $this->get('/seller/best-products');

        $response->assertStatus(200);

        $response->assertJsonStructure(['bestProducts']);
    }

    public function testStore()
    {
        $this->actingAsSeller();
        $seller_id = Auth::user()->id;
        $data = [
            'product_id' => Product::where('user_id', $seller_id)->inRandomOrder()->first()->id,
        ];

        $response = $this->post('/seller/best-products', $data,['Accept' => 'application/json']);
        $response->assertStatus(201);
        $response->assertJson(['message' => 'Best product created successfully']);
    }

    public function testUpdate()
    {
        $this->actingAsAdmin();

        $bestProductId = BestProduct::latest()->first()->product_id;
        $product = Product::find($bestProductId);

        $data = [
            'status' => 'approved',
        ];

        $response = $this->put("/seller/best-products/{$product->id}", $data);
        Log::info('Response: ' . $response->content());
        $response->assertStatus(200);
        $response->assertJson(['message' => 'product updated successfully']);
    }

    public function testDestroy()
    {
        $this->actingAsAdmin();

        $bestProductId = BestProduct::latest()->first()->product_id;
        $product = Product::find($bestProductId);

        $response = $this->delete("/seller/best-products/{$product->id}");

        $response->assertStatus(200);

        $response->assertJson(['message' => 'Best product deleted successfully']);
    }

    private function actingAsSeller()
    {
        $seller = Seller::take(1)->first();
        $this->actingAs($seller,'seller');


    }

    private function actingAsAdmin()
    {
        $admin = Admin::take(1)->first();
        $this->actingAs($admin,'admin');

    }
}
