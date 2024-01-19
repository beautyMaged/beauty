<?php
namespace Tests\Feature;

use App\Model\Admin;
use App\Model\Category;
use App\Model\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SellerCategoryControllerTest extends TestCase
{
    use WithFaker;

    public function testIndex()
    {
        $this->actingAsSeller();

        $response = $this->get('/seller/categories');

        $response->assertStatus(200);
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

        $response = $this->post('/seller/categories', $data,['Accept' => 'application/json']);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'Category created successfully']);
    }

    public function testView()
    {
        $this->actingAsSeller();

        $category = Category::first();

        $response = $this->get("/seller/categories/{$category->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure(['category']);
    }



    public function testUpdate()
    {
        $this->actingAsSeller();

        $category = Category::first();

        $data = [
            'status' => 'approved',
        ];

        $response = $this->put("/seller/categories/{$category->id}", $data);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Category updated successfully']);
    }

    public function testDestroy()
    {
        $this->actingAsSeller();

        $category = Category::latest()->first();

        $response = $this->delete("/seller/categories/{$category->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Category deleted successfully']);
    }

    protected function actingAsSeller()
    {
        $seller = Seller::all()->first();
        
        $this->actingAs($seller);
        $this->be($seller);

    }
}
