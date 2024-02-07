<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Model\Country;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Model\Admin;

class CountryControllerTest extends TestCase
{
    /** @test */
    public function testIndex()
    {

        $response = $this->get('/app/countries');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function testStoreUnauthorized()
    {

        $response = $this->post('/app/countries');

        $response = $this->followRedirects($response);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    /** @test */
    public function testStore()
    {
        // Storage::fake('public');

        $this->actingAsAdmin();

        $file = UploadedFile::fake()->image('flag.png');

        $response = $this->post('/app/countries', [
            'name' => 'Test Country',
            'code' => 'TC',
            'flag' => $file,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

    }

    /** @test */
    public function testShow()
    {
        $country = Country::first();

        $response = $this->get('/app/countries/' . $country->id);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function testUpdateUnauthorized()
    {
        $country = Country::where('name','Test Country')->first();

        $response = $this->put('/app/countries/' . $country->id);

        $response = $this->followRedirects($response);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    /** @test */
    public function testUpdate()
    {
        $this->actingAsAdmin();
        $country = Country::where('name','Test Country')->first();

        $response = $this->put('/app/countries/' . $country->id, [
            'name' => 'Updated Country',
            'code' => 'UC',
        ]);

        $response->assertStatus(response::HTTP_OK);
    }

    /** @test */
    public function testDestroyUnauthorized()
    {
        $country = Country::where('name', 'Updated Country')->first();

        $response = $this->delete('/app/countries/' . $country->id);

        $response = $this->followRedirects($response);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    /** @test */
    public function testDestroy()
    {
        $this->actingAsAdmin();
        $country = Country::where('name', 'Updated Country')->first();

        $response = $this->delete('/app/countries/' . $country->id);

        $response->assertStatus(response::HTTP_OK);

    }






    private function actingAsAdmin()
    {
        $admin = Admin::take(1)->first();
        $this->actingAs($admin, 'admin');
    }
}
