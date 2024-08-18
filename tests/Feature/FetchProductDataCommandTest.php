<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FetchProductDataCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_product_data_command()
    {
        $this->artisan('fetch:product-data')
            ->assertExitCode(0);

        $this->assertDatabaseHas('skus', [
            // check some expected SKU data here
        ]);
    }
}
