<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Model\Policy;
use Illuminate\Http\Response;
use App\Model\Admin;
use App\Model\Seller;
use Exception;
use Illuminate\Support\Facades\Log;

class PolicyControllerTest extends TestCase
{

    /** @test */
    public function index()
    {
        $this->actingAsAdmin();

        $response = $this->get('/seller-policies');

        $response->assertStatus(Response::HTTP_OK);
    }

    // unauthenticated
    public function testIndexUnAuthorized()
    {

        $response = $this->get('/seller-policies');

        $response = $this->followRedirects($response);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function store()
    {
        $this->actingAsAdmin();

        $policyData = [
            'name' => 'Test Policy',
            'description' => 'Test Policy Description',
            'type' => 'refund',
        ];

        $response = $this->post('/seller-policies', $policyData);
        // Log::info($response);


        $response->assertStatus(Response::HTTP_OK);

    }

    public function storeForSeller()
    {
        $this->actingAsSeller();

        $policyData = [
            'name' => 'Test Policy',
            'description' => 'Test Policy Description',
            'type' => 'refund',
        ];

        $response = $this->post('/seller/new-policy', $policyData);
        // Log::info($response);


        $response->assertStatus(Response::HTTP_OK);

    }

    /** @test */
    public function show()
    {
        $this->actingAsAdmin();

        $policy = Policy::first();

        $response = $this->get("/seller-policies/{$policy->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_can_update_a_policy()
    {
        $this->actingAsAdmin();

        $policy = Policy::latest()->first();

        $updatedData = [
            'name' => 'Updated Policy Name',
            'description' => 'Updated Policy Description',
            'is_approved' => true,
            'is_global' => true,
            'type' => 'delivery',
        ];

        $response = $this->put("/seller-policies/{$policy->id}", $updatedData);

        $response->assertStatus(Response::HTTP_OK);

    }

    /** @test */
    public function destroy()
    {
        $this->actingAsAdmin();

        $policy = Policy::latest()->first();

        $response = $this->delete("/seller-policies/{$policy->id}");

        $response->assertStatus(Response::HTTP_OK);
    }


    private function actingAsAdmin()
    {
        $admin = Admin::take(1)->first();
        $this->actingAs($admin, 'admin');
    }

    private function actingAsSeller()
    {
        $seller = Seller::take(1)->first();
        $this->actingAs($seller, 'seller');
    }
}
