<?php
declare(strict_types=1);
namespace Tests\Unit;

use Tests\TestCase;
use App\Jobs\ProcessProductData;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProcessProductDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_processes_product_data()
    {
        $productData = [
            'id' => 'some-uuid',
            'product_name' => 'Sample Product',
            'parent_category' => 'Category1',
            'description' => 'This is a sample product.',
            'on_sale' => true,
            'updated_at' => now(),
        ];

        $job = new ProcessProductData($productData);
        $job->handle();

        $this->assertDatabaseHas('products', [
            'id' => 'some-uuid',
            'product_name' => 'Sample Product',
        ]);
    }
}
