<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessProductData;
use App\Models\Sku;
use Illuminate\Support\Facades\Log;

class ProductDataController extends Controller
{
    /**
     * Retrieve product data from mock JSON file.
     *
     * @return array
     */
    public function index(): array
    {
        $data = file_get_contents(storage_path('app/public') . '/mock_data.json');

        return json_decode($data, true);
    }

    /**
     * Display 20 SKUs including their variants.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showSkus(): \Illuminate\Http\JsonResponse
    {
        try {
            $skus = Sku::with('variants')
                ->whereHas('product', function ($query): void {
                    $query->where('on_sale', true);
                })
                ->take(20)
                ->get();

            $skus = $skus->map(function ($sku) {
                $response = [
                    'SKU' => $sku->SKU,
                    'box_qty' => $sku->box_qty,
                    'variants' => $sku->variants->map(function ($variant) {
                        return [
                            'colour' => $variant->colours,
                            'size' => $variant->size,
                        ];
                    }),
                ];

                if (auth()->check()) {
                    $response['dimensions'] = [
                        'width' => $sku->width,
                        'height' => $sku->height,
                        'length' => $sku->length,
                    ];
                }

                return $response;
            });

            return response()->json($skus);
        } catch (\Exception $e) {
            Log::error('Failed to fetch SKUs: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch SKUs'], 500);
        }
    }

    /**
     * Process product data and dispatch job.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processProductData(Request $request)
    {
        $productData = $request->all();

        // Dispatch the job
        ProcessProductData::dispatch($productData);

        return response()->json(['status' => 'Job dispatched']);
    }
}
