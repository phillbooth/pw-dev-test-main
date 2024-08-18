<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SkuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'SKU' => $this->SKU,
            'box_qty' => $this->box_qty,
            'dimensions' => auth()->check() ? [
                'width' => $this->width,
                'height' => $this->height,
                'length' => $this->length,
            ] : null,
            'variants' => VariantResource::collection($this->variants),
        ];
    }
}
