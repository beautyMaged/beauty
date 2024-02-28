<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Model\District;
use App\Model\City;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Model\Admin;

class DistrictControllerTest extends TestCase
{
    /** @test */
    public function testIndex()
    {

        $response = $this->get('/app/districts');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function testStoreUnauthorized()
    {

        $response = $this->post('/app/districts');

        $response = $this->followRedirects($response);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    /** @test */
    public function testStore()
    {

        $city = City::first();

        $this->actingAsAdmin();

        $response = $this->post('/app/districts', [
            'name' => 'Test District',
            'city_id' => $city->id
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

    }

    /** @test */
    public function testShow()
    {
        $district = District::first();

        $response = $this->get('/app/districts/' . $district->id);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function testUpdateUnauthorized()
    {
        $district = District::where('name','Test District')->first();

        $response = $this->put('/app/districts/' . $district->id);

        $response = $this->followRedirects($response);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    /** @test */
    public function testUpdate()
    {
        $this->actingAsAdmin();

        $city = City::first();
        
        $district = District::where('name','Test District')->first();

        $response = $this->put('/app/districts/' . $district->id, [
            'name' => 'Updated District',
            'city_id' => $city->id
        ]);

        $response->assertStatus(response::HTTP_OK);
    }

    /** @test */
    public function testDestroyUnauthorized()
    {
        $district = District::where('name', 'Updated District')->first();

        $response = $this->delete('/app/districts/' . $district->id);

        $response = $this->followRedirects($response);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    /** @test */
    public function testDestroy()
    {
        $this->actingAsAdmin();
        $district = District::where('name', 'Updated District')->first();

        $response = $this->delete('/app/districts/' . $district->id);

        $response->assertStatus(response::HTTP_OK);

    }






    private function actingAsAdmin()
    {
        $admin = Admin::take(1)->first();
        $this->actingAs($admin, 'admin');
    }
}
