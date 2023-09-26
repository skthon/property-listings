<?php

namespace App\Console\Commands;

use App\Models\PropertyListing;
use App\PropertyListingsSources\ListingsClient;
use App\Models\PropertyType;
use Exception;
use Illuminate\Console\Command;

class ImportListingsCommand extends Command
{
    protected $propertyTypesCache = [];

    protected $signature = 'import:listings {--page_no=1} {--page_size=30}';

    protected $description = 'Command for importing property listings from third party api';

    public function handle()
    {
        $pageNumber = $this->option('page_no');
        $pageSize = $this->option('page_size');

        $totalProcessed = 0;
        $lastPage = 0;

        // Cache property types;
        $this->propertyTypesCache = PropertyType::pluck('id')->all();

        while (true) {
            $response = $this->getPropertiesFromApi($pageNumber, $pageSize);
            $properties = $response['data'];
            $lastPage = $response['last_page']; 

            if (count($properties) == 0) {
                $this->info("No properties found");
                break;
            }

            $this->insertProperties($properties);
            $totalProcessed += count($properties);

            $this->info("Total property listings processed: $totalProcessed/{$response['total']}, PageNo: {$pageNumber}");
            $pageNumber = $response['current_page'] + 1;

            if ($lastPage < $pageNumber) {
                $this->info('Finished processing');
                break;
            }
        }

        return Command::SUCCESS;
    }

    public function getPropertiesFromApi(int $pageNumber, int $pageSize): array
    {
        try {
            $response = (new ListingsClient())->setPageNumber($pageNumber)
                ->setPageSize($pageSize)
                ->sendRequest();
        } catch (Exception $ex) {
            $this->error("Failed to fetch property listings from api, pageNo: {$pageNumber} "
                . " pageSize:{$pageSize}"
                . " message:{$ex->getMessage()}"
            );
            exit();
        }

        return $response->json();
    }

    private function insertProperties(array $properties)
    {
        foreach ($properties as $property) {
            try {
                // Save property type
                $this->insertOrIgnorePropertyType($property);

                // Save property listings
                $this->insertOrIgnorePropertyListing($property);
            } catch (Exception $ex) {
                $this->error('Failed to save property in database, error message: ' . $ex->getMessage()
                    . ' Property:' . print_r($property, true)
                );
                exit();
            }
        }
    }

    private function insertOrIgnorePropertyListing(array $property): void
    {
        $fillData = collect($property)->only([
            'uuid',
            'property_type_id',
            'county',
            'country',
            'town',
            'description',
            'address',
            'image_full',
            'image_thumbnail',
            'latitude',
            'longitude',
            'num_bedrooms',
            'num_bathrooms',
            'price',
            'type',
            'created_at',
            'updated_at',
        ])->all();

        PropertyListing::updateOrCreate(['uuid' => $property['uuid']], $fillData);
    }

    private function insertOrIgnorePropertyType(array $property): void
    {
        if (in_array($property['property_type']['id'], $this->propertyTypesCache)) {
            return;
        }

        $propertyType = new PropertyType();
        $propertyType->fill(
            collect($property['property_type'])->only([
                'id',
                'title',
                'description',
                'created_at',
                'updated_at',    
            ])->all()
        );
        $propertyType->save();

        array_push($this->propertyTypesCache, $property['property_type']['id']);
    }
}
