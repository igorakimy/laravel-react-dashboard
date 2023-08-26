<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PizzaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'ingredients' => Str::limit($this->ingredients, 20, ''),
            'price' => $this->price,
            'photo_name' => $this->photo_name,
            'sold_out' => $this->sold_out,
        ];
    }
}
