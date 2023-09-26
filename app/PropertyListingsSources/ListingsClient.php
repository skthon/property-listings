<?php

namespace App\PropertyListingsSources;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ListingsClient
{
    private int $pageNumber;

    private int $pageSize;

    private array $headers = [
        'Content-type' => 'application/json'
    ];

    public function setPageNumber(int $pageNumber = 1): self
    {
        $this->pageNumber = $pageNumber;
        return $this;
    }

    public function setPageSize(int $pageSize = 30): self
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    private function getConfiguration(): array
    {
        if (! config('listings.source.url') || ! config('listings.source.api_key')) {
            throw new Exception('Please set client url and api key in env configuration');
        }

        return [config('listings.source.url'), config('listings.source.api_key')];
    }

    public function sendRequest(): Response
    {
        list($url, $apiKey) = $this->getConfiguration();

        $response = Http::withHeaders($this->headers)->get($url, [
            'api_key'      => $apiKey,
            'page[number]' => $this->pageNumber,
            'page[size]'   => $this->pageSize,
        ]);

        if ($response->status() != 200) {
            throw new Exception('StatusCode: ' . $response->status() . ' ResponseBody:' . $response->body());
        }

        return $response;
    }
}