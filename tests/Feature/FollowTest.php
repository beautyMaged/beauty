<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Model\Seller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;


class FollowTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserCanFollowSeller()
    {
        $user = User::first();

        $seller = Seller::first();

        $this->actingAsUser();

        $response = $this->post('app/customer/follow/' . $seller->id);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('seller_user', [
            'user_id' => $user->id,
            'seller_id' => $seller->id,
        ]);
    }

    public function testUserCanUnfollowSeller()
    {
        $user = User::first();

        $seller = Seller::first();

        $this->actingAsUser();

        $response = $this->post('app/customer/unfollow/' . $seller->id);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('seller_user', [
            'user_id' => $user->id,
            'seller_id' => $seller->id,
        ]);
    }

    private function actingAsUser()
    {
        $user = User::take(1)->first();
        $this->actingAs($user, 'customer');
    }
}
