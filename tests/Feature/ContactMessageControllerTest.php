<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Model\Admin;
use Illuminate\Support\Facades\Log;
use App\Model\ContactMessage;
use Illuminate\Http\UploadedFile;

class ContactMessageControllerTest extends TestCase
{
    /** @test */
    public function index()
    {
        $this->actingAsAdmin();

        $response = $this->get('/app/contact-messages');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function store()
    {
        $attachment = UploadedFile::fake()->image('image.png');

        $Data = [
            'attachment' => $attachment,
            'name' => 'testing global email',
            'phone' => '01234567893',
            'email' => 'testingEmail@email.com',
            'type' => 'customer_service',
            'title' => 'complaint',
            'message' => 'dummy message'

        ];

        $response = $this->post('/app/contact-messages', $Data);
        // Log::info($response);

        $response->assertStatus(Response::HTTP_CREATED);

    }

    /** @test */
    public function indexUnauthorized()
    {

        $response = $this->get('/app/contact-messages');
        // Log::info($response);
        $response = $this->followRedirects($response);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    /** @test */
    public function show()
    {
        $this->actingAsAdmin();

        $contactMessage = ContactMessage::first();

        $response = $this->get("/app/contact-messages/{$contactMessage->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function destroy()
    {
        $this->actingAsAdmin();

        $contactMessage = ContactMessage::latest()->first();

        $response = $this->delete("/app/contact-messages/{$contactMessage->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    private function actingAsAdmin()
    {
        $admin = Admin::take(1)->first();
        $this->actingAs($admin, 'admin');
    }
}
