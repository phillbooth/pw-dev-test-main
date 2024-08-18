<?php
declare(strict_types=1);
namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ProductDataService;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductDataServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ProductDataService $productDataService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productDataService = $this->app->make(ProductDataService::class);
    }

    public function test_it_fetches_and_stores_product_data(): void
    {
        Http::fake([
            env('API_URL') . '/product-data' => Http::response([
                [
                    'id' => 'some-uuid',
                    'product_name' => 'Sample Product',
                    'SKU' => 'ABC123',
                    'box_qty' => 10,
                    'width' => 20,
                    'height' => 10,
                    'length' => 5,
                    'variant' => 'Standard',
                    'colours' => 'Red',
                    'size' => 'M',
                    'parent_category' => 'Category1',
                    'description' => 'This is a sample product.',
                    'on_sale' => true,
                    'updated_at' => now(),
                ],
            ], 200),
        ]);

        $this->productDataService->fetchAndStoreProductData();

        $this->assertDatabaseHas('products', [
            'product_name' => 'Sample Product',
        ]);

        $this->assertDatabaseHas('skus', [
            'SKU' => 'ABC123',
        ]);

        $this->assertDatabaseHas('variants', [
            'variant' => 'Standard',
        ]);
    }
}
