<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Model\Admin;
use Tests\TestCase;
use Illuminate\Http\Response;
use App\Model\CommonQuestion;


class CommonQuestionControllerTest extends TestCase
{

    /** @test */
    public function index()
    {
        $this->actingAsAdmin();

        $response = $this->get('/app/common-questions');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function store()
    {
        $this->actingAsAdmin();

        $commonQuestionData = [
            'for' => 'customers',
            'type' => 'general',
            'question' => 'Test Question',
            'answer' => 'Test Answer',
        ];

        $response = $this->post('/app/common-questions', $commonQuestionData);


        $response->assertStatus(Response::HTTP_CREATED);

    }

    /** @test */
    public function storeUnauthorized()
    {

        $commonQuestionData = [
            'for' => 'customers',
            'type' => 'general',
            'question' => 'Test Question',
            'answer' => 'Test Answer',
        ];

        $response = $this->post('/app/common-questions', $commonQuestionData);
        // Log::info($response);
        $response = $this->followRedirects($response);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    /** @test */
    public function show()
    {

        $commonQuestion = CommonQuestion::first();

        $response = $this->get("/app/common-questions/{$commonQuestion->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function update()
    {
        $this->actingAsAdmin();


        $commonQuestion = CommonQuestion::latest()->first();

        $updatedData = [
            'for' => 'customers',
            'type' => 'general',
            'question' => 'Updated Question',
            'answer' => 'Updated Answer',
        ];

        $response = $this->put("/app/common-questions/{$commonQuestion->id}", $updatedData);

        $response->assertStatus(Response::HTTP_OK);

    }


    /** @test */
    public function destroy()
    {
        $this->actingAsAdmin();

        $commonQuestion = CommonQuestion::latest()->first();

        $response = $this->delete("/app/common-questions/{$commonQuestion->id}");

        $response->assertStatus(Response::HTTP_OK);
    }


    /** @test */
    public function getByType()
    {


        $response = $this->get('/app/common-questions/general/customers');

        $response->assertStatus(Response::HTTP_OK);
    }

    private function actingAsAdmin()
    {
        $admin = Admin::take(1)->first();
        $this->actingAs($admin, 'admin');
    }

}
