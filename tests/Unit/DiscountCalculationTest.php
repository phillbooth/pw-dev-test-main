<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str; // Import Str class

class DiscountCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_discount_calculation(): void
    {
        // Create a product with a name and price
        $product = Product::create([
            'id' => (string) Str::uuid(),
            'product_name' => 'Test Product',
            'price' => 100,
            'parent_category' => 'Test Category',
            'description' => 'A test product description',
            'on_sale' => true,
        ]);

        // Apply a discount
        $discountedPrice = $product->applyDiscount(10); // Apply a 10% discount

        // Assert the discounted price is as expected
        $this->assertEquals(90.00, $discountedPrice);
    }
}
