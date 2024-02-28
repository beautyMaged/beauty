<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Model\DeliveryCompany;
use Illuminate\Http\Response;
use App\Model\Admin;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DeliveryCompanyControllerTest extends TestCase
{ /** @test */
    public function testIndex()
    {

        $response = $this->get('/app/delivery-companies');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function testStoreUnauthorized()
    {

        $response = $this->post('/app/delivery-companies');

        $response = $this->followRedirects($response);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    /** @test */
    public function testStore()
    {

        $image = UploadedFile::fake()->image('image.jpg');

        $this->actingAsAdmin();

        $response = $this->post('/app/delivery-companies', [
            'name' => 'Test DeliveryCompanies',
            'logo' => $image
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

    }

    /** @test */
    public function testShow()
    {
        $deliveryCompany = DeliveryCompany::first();

        $response = $this->get('/app/delivery-companies/' . $deliveryCompany->id);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function testUpdateUnauthorized()
    {
        $deliveryCompany = DeliveryCompany::where('name','Test DeliveryCompanies')->first();

        $response = $this->put('/app/delivery-companies/' . $deliveryCompany->id);

        $response = $this->followRedirects($response);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    /** @test */
    public function testUpdate()
    {
        $this->actingAsAdmin();

        $image = UploadedFile::fake()->image('image.jpg');
        
        $deliveryCompany = DeliveryCompany::where('name','Test DeliveryCompanies')->first();

        $response = $this->put('/app/delivery-companies/' . $deliveryCompany->id, [
            'name' => 'Updated DeliveryCompanies',
            'logo' => $image
        ]);

        $response->assertStatus(response::HTTP_OK);
    }

    /** @test */
    public function testDestroyUnauthorized()
    {
        $deliveryCompany = DeliveryCompany::where('name', 'Updated DeliveryCompanies')->first();

        $response = $this->delete('/app/delivery-companies/' . $deliveryCompany->id);

        $response = $this->followRedirects($response);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    /** @test */
    public function testDestroy()
    {
        $this->actingAsAdmin();
        $deliveryCompany = DeliveryCompany::where('name', 'Updated DeliveryCompanies')->first();

        $response = $this->delete('/app/delivery-companies/' . $deliveryCompany->id);

        $response->assertStatus(response::HTTP_OK);

    }






    private function actingAsAdmin()
    {
        $admin = Admin::take(1)->first();
        $this->actingAs($admin, 'admin');
    }
}
