<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Model\Admin;
use Illuminate\Support\Facades\Log;
use App\Model\ConnectionChannel;
use Illuminate\Http\UploadedFile;

class ConnectionChannelControllerTest extends TestCase
{
    /** @test */
    public function index()
    {
        $this->actingAsAdmin();

        $response = $this->get('/connection-channels');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function store()
    {
        $this->actingAsAdmin();
        $logo = UploadedFile::fake()->image('image.png');

        $Data = [
            'logo' => $logo,
            'name' => 'testing global email',
            'type' => 'email',
            'data' => 'testingEmail@email.com',
        ];

        $response = $this->post('/connection-channels', $Data);
        // Log::info($response);

        $response->assertStatus(Response::HTTP_CREATED);

    }

    /** @test */
    public function storeUnauthorized()
    {

        $Data = [
            'name' => 'testing global email',
            'type' => 'email',
            'data' => 'testingEmail@email.com',
        ];

        $response = $this->post('/connection-channels', $Data);
        // Log::info($response);
        $response = $this->followRedirects($response);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    /** @test */
    public function show()
    {

        $connectionChannel = ConnectionChannel::first();

        $response = $this->get("/connection-channels/{$connectionChannel->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function update()
    {
        $this->actingAsAdmin();

        $logo = UploadedFile::fake()->image('image.png');


        $connectionChannel = ConnectionChannel::latest()->first();

        $updatedData = [
            'logo' => $logo,
            'name' => 'testing global email',
            'type' => 'email',
            'data' => 'testingEmail@email.com',
        ];

        $response = $this->put("/connection-channels/{$connectionChannel->id}", $updatedData);

        $response->assertStatus(Response::HTTP_OK);

    }

        /** @test */
        public function destroy()
        {
            $this->actingAsAdmin();
    
            $connectionChannel = ConnectionChannel::latest()->first();
    
            $response = $this->delete("/connection-channels/{$connectionChannel->id}");
    
            $response->assertStatus(Response::HTTP_OK);
        }


    private function actingAsAdmin()
    {
        $admin = Admin::take(1)->first();
        $this->actingAs($admin, 'admin');
    }
}
