<?php

namespace App\Services;

use App\Models\PropertyListing;
use App\Models\PropertyType;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

class PropertyListingsService
{
    public static function filter(Request $request): Paginator
    {
        $propertyTypeId = $request->get('property_type') 
            ? PropertyType::where('title', $request->get('property_type'))->pluck('id')->first()
            : null;

        $properties = PropertyListing::with(['propertyType'])
            ->when($request->get('num_bedrooms'), function($query) use ($request) {
                return $query->where('num_bedrooms', $request->get('num_bedrooms'));
            })
            ->when($request->get('price'), function($query) use ($request) {
                return $query->where('price', $request->get('price'));
            })
            ->when($request->get('description'), function($query) use ($request) {
                return $query->where('description', 'like', '%' .  $request->get('description') . '%');
            })
            ->when($request->get('available_type'), function($query) use ($request) {
                return $query->where('type', $request->get('available_type'));
            })
            ->when($propertyTypeId, function($query) use ($propertyTypeId) {
                return $query->where('property_type_id', $propertyTypeId);
            })
            ->paginate()
            ->appends(request()->query());

        return $properties;
    }
}