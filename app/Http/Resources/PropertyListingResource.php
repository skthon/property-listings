<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyListingResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return [
            'uuid'               => $this->uuid,
            'type'               => $this->type,
            'description'        => $this->description,
            'price'              => $this->price,
            'num_bedrooms'       => $this->num_bedrooms,
            'num_bathrooms'      => $this->num_bathrooms,
            'image_full'         => $this->image_full,
            'image_thumbnail'    => $this->image_thumbnail,
            'county'             => $this->county,
            'country'            => $this->country,
            'town'               => $this->town,
            'address'            => $this->address,
            'latitude'           => $this->latitude,
            'longitude'          => $this->longitude,
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
            'property_type_name' => $this->propertyType?->title,
        ];
    }
}