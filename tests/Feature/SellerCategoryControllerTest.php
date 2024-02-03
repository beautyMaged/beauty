<?php

namespace Tests\Feature;

use App\Model\Admin;
use App\Model\Category;
use App\Model\Seller;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Http\Response;

class SellerCategoryControllerTest extends TestCase
{
    use WithFaker;

    // unauthenticated
    public function testIndexUnAuthorized()
    {

        $response = $this->get('/seller/categories');

        $response = $this->followRedirects($response);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testIndex()
    {
        $this->actingAsAdmin();

        $response = $this->get('/seller/categories');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure(['categories']);
    }

    public function testStore()
    {
        $this->actingAsSeller();

        $data = [
            'lang' => ['ar', 'sa'],
            'name' => ['category_name', 'اسم القسم'],
            'image' => UploadedFile::fake()->image('image.jpg'),
            'priority' => $this->faker->randomNumber,
            'position' => $this->faker->randomElement([1, 2, 3]),
            'parent_id' => Category::where('position', '<', 3)->first()->id,
        ];

        $response = $this->post('/seller/categories', $data, ['Accept' => 'application/json']);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'Category created successfully']);
    }

    public function testView()
    {
        $this->actingAsAdmin();

        $category = Category::first();

        $response = $this->get("/seller/categories/{$category->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['category']);
    }



    public function testUpdate()
    {
        $this->actingAsAdmin();

        $category = Category::first();

        $data = [
            'status' => 'approved',
        ];

        $response = $this->put("/seller/categories/{$category->id}", $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['message' => 'Category updated successfully']);
    }

    public function testDestroy()
    {
        $this->actingAsAdmin();

        $category = Category::latest()->first();

        $response = $this->delete("/seller/categories/{$category->id}");

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(['message' => 'Category deleted successfully']);
    }

    private function actingAsSeller()
    {
        $seller = Seller::take(1)->first();
        $this->actingAs($seller, 'seller');
    }

    private function actingAsAdmin()
    {
        $admin = Admin::take(1)->first();
        $this->actingAs($admin, 'admin');
    }
}
