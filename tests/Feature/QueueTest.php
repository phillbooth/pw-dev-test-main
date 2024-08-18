<?php
declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ProcessProductData;

class QueueTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_dispatches_a_job_to_the_queue(): void
    {
        Queue::fake();

        $productData = [
            'id' => 'some-uuid',
            'product_name' => 'Sample Product',
            'parent_category' => 'Category1',
            'description' => 'This is a sample product.',
            'on_sale' => true,
            'updated_at' => now(),
        ];

        // Dispatch the job
        ProcessProductData::dispatch($productData);

        // Assert that the job was pushed with the correct product data
        Queue::assertPushed(ProcessProductData::class, function ($job) use ($productData) {
            return $job->getProductData()['product_name'] === $productData['product_name'];
        });
    }
}
