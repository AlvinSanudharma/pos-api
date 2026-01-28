<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category' => new ProductCategoryResource($this->whenLoaded('category')),
            'image' => $this->whenHas('image', function () {
                            return $this->image
                                ? asset(Storage::url($this->image))
                                : null;
                        }),
            'name' => $this->whenHas('name'),
            'price' => (float)(string) $this->whenHas('price'),
            'stock' => $this->whenHas('stock'),
        ];
    }
}
