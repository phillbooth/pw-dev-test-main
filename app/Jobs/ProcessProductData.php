<?php
declare(strict_types=1);

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessProductData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productData;

    /**
     * Create a new job instance.
     */
    public function __construct(array $productData)
    {
        $this->productData = $productData;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Process the product data and store it in the database
        Product::updateOrCreate(
            ['id' => $this->productData['id']],
            [
                'product_name' => $this->productData['product_name'],
                'parent_category' => $this->productData['parent_category'],
                'description' => $this->productData['description'],
                'on_sale' => $this->productData['on_sale'],
                'updated_at' => $this->productData['updated_at'],
            ]
        );
    }

    public function getProductData()
    {
        return $this->productData;
    }
}
