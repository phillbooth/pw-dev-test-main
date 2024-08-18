<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WebRoutesTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Test that the SKUs route is accessible.
     *
     * @return void
     */
    public function testSkusRouteIsAccessible()
    {
        $response = $this->get('/skus');

        $response->assertStatus(200);
    }

    /**
     * Test that an undefined route returns a 404 error.
     *
     * @return void
     */
    public function testUndefinedRouteReturns404()
    {
        $response = $this->get('/undefined-route');

        $response->assertStatus(404);
        $response->assertSee('Resource not found');
    }
}
