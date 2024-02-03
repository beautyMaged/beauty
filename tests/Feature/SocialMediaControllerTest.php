<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Model\SocialMedia;
use Illuminate\Http\UploadedFile;
use App\Model\Admin;
use Tests\TestCase;

class SocialMediaControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get('/app/social-media');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function testStoreWithAuthentication()
    {
        $this->actingAsAdmin();

        $data = [
            'name' => 'Test Social Media',
            'link' => 'https://example.com',
            'icon' => UploadedFile::fake()->image('icon.jpg'),
        ];

        $response = $this->postJson('/app/social-media', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['data']);
    }

    public function testStoreWithoutAuthentication()
    {
        $data = [
            'name' => 'Test Social Media',
            'link' => 'https://example.com',
            'icon' => UploadedFile::fake()->image('icon.jpg'),
        ];

        $response = $this->postJson('/app/social-media', $data);
        $response = $this->followRedirects($response);

        $response->assertStatus(401); 
    }

    public function testShow()
    {
        $socialMedia =  SocialMedia::first();


        $response = $this->get("/app/social-media/{$socialMedia->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }



    public function testUpdateWithAuthentication()
    {
        $this->actingAsAdmin();
    
        $socialMedia = SocialMedia::first();
    
        $data = [
            'name' => 'Updated Social Media',
            'link' => 'https://updated-example.com',
            'icon' => UploadedFile::fake()->image('updated_icon.jpg'),
        ];
    
        $response = $this->putJson("/app/social-media/{$socialMedia->id}", $data);
    
        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }
    
    public function testUpdateWithoutAuthentication()
    {
        $socialMedia = SocialMedia::latest()->first();
    
        $data = [
            'name' => 'Updated Social Media',
            'link' => 'https://updated-example.com',
            'icon' => UploadedFile::fake()->image('updated_icon.jpg'),
        ];
    
        $response = $this->putJson("/app/social-media/{$socialMedia->id}", $data);
        $response = $this->followRedirects($response);
        $response->assertStatus(401); 
    }

    public function testDestroyWithAuthentication()
{
    $this->actingAsAdmin();

    $socialMedia = SocialMedia::latest()->first();

    $response = $this->delete("/app/social-media/{$socialMedia->id}");

    $response->assertStatus(200)
        ->assertJson(['message' => 'Social Media deleted successfully']);
}

public function testDestroyWithoutAuthentication()
{
    $socialMedia = SocialMedia::latest()->first();

    $response = $this->delete("/app/social-media/{$socialMedia->id}");

    $response = $this->followRedirects($response);

    $response->assertStatus(401); 
}

    

    private function actingAsAdmin()
    {
        $admin = Admin::take(1)->first();
        $this->actingAs($admin,'admin');

    }
}
