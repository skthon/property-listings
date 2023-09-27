<?php

namespace App\Http\Controllers;

use App\Http\Resources\PropertyListingResource;
use App\Models\PropertyListing;
use App\Models\PropertyType;
use App\Services\PropertyListingsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PropertyListingController extends Controller
{
    public function index(Request $request)
    {
        $properties = PropertyListingsService::filter($request);

        return Inertia::render('Index', [
            'properties'            => PropertyListingResource::collection($properties),
            'propertyTypes'         => PropertyType::pluck('title')->all(),
            'selectedNumBedrooms'   => (int) $request->get('num_bedrooms'),
            'selectedPrice'         => (int) $request->get('price'),
            'selectedDescription'   => $request->get('description'),
            'selectedAvailableType' => $request->get('available_type'),
            'selectedPropertyType'  => $request->get('property_type'),
        ]);
    }

    public function destroy($id)
    {
        $property = PropertyListing::find($id);
        $property->delete();

        return Inertia::location(route('property_list'));
    }
}