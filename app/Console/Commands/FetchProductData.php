<?php
declare(strict_types=1);
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProductDataService;

class FetchProductData extends Command
{
    protected $signature = 'fetch:product-data';
    protected $description = 'Fetch product data from the API and store it in the database';
    protected $productDataService;

    public function __construct(ProductDataService $productDataService)
    {
        parent::__construct();
        $this->productDataService = $productDataService;
    }

    public function handle()
    {
        $this->productDataService->fetchAndStoreProductData();
        $this->info('Product data fetched and stored successfully.');
    }
}
