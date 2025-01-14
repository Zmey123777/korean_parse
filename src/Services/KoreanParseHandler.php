<?php

declare(strict_types=1);

namespace App\Services;

use App\Client\EncarApiClient;

class KoreanParseHandler
{
    public function __construct(
        private readonly EncarApiClient $client,
        private readonly CarModelMatcher $carModelMatcher
    ){}

    public function handle($context, $dryRun = false): array
    {
        if (!$dryRun) {
            // DB repository inserting logic
            return [];
        }

        $data = $this->client->fetchCars($context, 8, 8);

        foreach ($data as &$car) {
            $car['Brand'] = $this->carModelMatcher->getManufacturerEnglishName($car['Manufacturer']);
        }

        return $data;
    }
}