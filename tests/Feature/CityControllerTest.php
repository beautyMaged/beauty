<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Model\City;
use App\Model\Country;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Model\Admin;

class CityControllerTest extends TestCase
{
    /** @test */
    public function testIndex()
    {

        $response = $this->get('/app/cities');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function testStoreUnauthorized()
    {

        $response = $this->post('/app/cities');

        $response = $this->followRedirects($response);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    /** @test */
    public function testStore()
    {

        $country = Country::first();

        $this->actingAsAdmin();

        $response = $this->post('/app/cities', [
            'name' => 'Test City',
            'country_id' => $country->id
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

    }

    /** @test */
    public function testShow()
    {
        $city = City::first();

        $response = $this->get('/app/cities/' . $city->id);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function testUpdateUnauthorized()
    {
        $city = City::where('name','Test City')->first();

        $response = $this->put('/app/cities/' . $city->id);

        $response = $this->followRedirects($response);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    /** @test */
    public function testUpdate()
    {
        $this->actingAsAdmin();

        $country = Country::first();
        
        $city = City::where('name','Test City')->first();

        $response = $this->put('/app/cities/' . $city->id, [
            'name' => 'Updated City',
            'country_id' => $country->id
        ]);

        $response->assertStatus(response::HTTP_OK);
    }

    /** @test */
    public function testDestroyUnauthorized()
    {
        $city = City::where('name', 'Updated City')->first();

        $response = $this->delete('/app/cities/' . $city->id);

        $response = $this->followRedirects($response);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    /** @test */
    public function testDestroy()
    {
        $this->actingAsAdmin();
        $city = City::where('name', 'Updated City')->first();

        $response = $this->delete('/app/cities/' . $city->id);

        $response->assertStatus(response::HTTP_OK);

    }






    private function actingAsAdmin()
    {
        $admin = Admin::take(1)->first();
        $this->actingAs($admin, 'admin');
    }
}
