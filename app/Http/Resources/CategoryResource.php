<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'parent_id' => $this->parent_id,
            'position' => $this->position,
            'home_status' => $this->home_status,
            'priority' => $this->priority,
            'status' => $this->status,
            'products_count' => $this->products->count(),
            'children' => CategoryResource::collection($this->childes),
        ];
    }
}
