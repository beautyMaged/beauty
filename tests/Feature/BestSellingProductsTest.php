<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BestSellingProductsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testHandle()
    {
        $this->artisan('bestselling:products')
             ->expectsOutput('Best selling products updated successfully.')
             ->assertExitCode(0);
    }
}
