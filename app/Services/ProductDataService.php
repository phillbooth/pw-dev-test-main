<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Sku;
use App\Models\Variant;
use App\Services\ErrorHandlingService;
use Sentry;

/**
 * Class ProductDataService
 * This service handles fetching product data from an external API and storing it in the database.
 */
class ProductDataService
{
    private ErrorHandlingService $errorHandlingService;

    /**
     * ProductDataService constructor.
     * @param ErrorHandlingService $errorHandlingService
     */
    public function __construct(ErrorHandlingService $errorHandlingService)
    {
        $this->errorHandlingService = $errorHandlingService;
    }

    /**
     * Fetches product data from an external API and stores it in the database.
     * 
     * @return void
     * @throws \Throwable
     */
    public function fetchAndStoreProductData(): void
    {
        try {
            // Use config() to get the API URL
            $apiUrl = config('services.api.url') . '/product-data';
            echo "Fetching data from: " . $apiUrl . PHP_EOL;
            
            // Retry the API call 3 times with a 1-second delay between attempts
            $response = Http::retry(3, 1000)->get($apiUrl);
    
            // Log the raw response
            echo "API Response: " . $response->body() . PHP_EOL;
    
            if ($response->failed()) {
                Sentry::captureMessage('API Request Failed: ' . $response->status());
                return;
            }
    
            if ($response->ok()) {
                $products = $response->json();
    
                foreach ($products as $productData) {
                    // Validate the product data
                    if (!$this->validateProductData($productData)) {
                        continue; // Skip this product if validation fails
                    }
    
                    // Handle product creation or update
                    $this->storeProductData($productData);
                }
            }
        } catch (\Throwable $e) {
            Sentry::captureException($e);
            $this->errorHandlingService->report($e);
            throw $e;
        } 
    }
    

    /**
     * Validates product data before processing.
     * 
     * @param array $productData
     * @return bool
     */
    public function validateProductData(array $productData): bool
    {
        try {
            $validator = Validator::make($productData, [
                'SKU' => 'required|string|regex:/^[A-Z0-9]{6,12}$/',
                // Add other validations as needed
            ]);

            if ($validator->fails()) {
                $this->logAndReport('Invalid product data: ' . json_encode($validator->errors()));
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            $this->logAndReportException($e);
            return false;
        } 
    }

    /**
     * Stores the validated product data in the database.
     * 
     * @param array $productData
     * @return void
     */
    private function storeProductData(array $productData): void
    {
        try {
            $product = Product::updateOrCreate(
                ['id' => $productData['id']],
                [
                    'product_name' => $productData['product_name'],
                    'parent_category' => $productData['parent_category'],
                    'description' => $productData['description'],
                    'on_sale' => $productData['on_sale'],
                    'updated_at' => $productData['updated_at'],
                ]
            );

            $sku = Sku::updateOrCreate(
                ['SKU' => $productData['SKU']],
                [
                    'product_id' => $product->id,
                    'box_qty' => $productData['box_qty'],
                    'width' => $productData['width'],
                    'height' => $productData['height'],
                    'length' => $productData['length'],
                ]
            );

            Variant::updateOrCreate(
                ['variant' => $productData['variant']],
                [
                    'sku_id' => $sku->id,
                    'colours' => $productData['colours'],
                    'size' => $productData['size'],
                ]
            );
        } catch (\Illuminate\Database\QueryException $e) {
            $this->logAndReport('Database error for SKU: ' . $productData['SKU'] . '. Error: ' . $e->getMessage());
        } catch (\Throwable $e) {
            $this->logAndReportException($e);
            throw $e;
        }
    }

    /**
     * Logs and reports a message.
     * 
     * @param string $message
     * @return void
     */
    private function logAndReport(string $message): void
    {
        echo "Warning: " . $message . PHP_EOL;
        Sentry::captureMessage($message);
    }

    /**
     * Logs and reports an exception.
     * 
     * @param \Throwable $e
     * @return void
     */
    private function logAndReportException(\Throwable $e): void
    {
        Sentry::captureException($e);
        $this->errorHandlingService->report($e);
    }
}
