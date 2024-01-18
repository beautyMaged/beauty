<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TopRatedProductsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testHandle()
    {
        $this->artisan('toprated:products')
             ->expectsOutput('Top-rated products updated successfully.')
             ->assertExitCode(0);
    }
}
