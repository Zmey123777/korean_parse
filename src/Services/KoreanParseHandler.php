<?php

declare(strict_types=1);

namespace App\Services;

use App\Client\EncarApiClient;
use Symfony\Component\HttpKernel\KernelInterface;

class KoreanParseHandler
{
    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly EncarApiClient $client,
        private readonly CarModelMatcher $carModelMatcher
    ){}

    public function handle(array $context, bool $dryRun = false): array
    {
        if($dryRun){
            $data = $this->client->fetchCars($context, 8, 8);

            $downloader = new CarPhotoDownloader($this->kernel);

            foreach ($data as &$car) {
                $car['Brand'] = $this->carModelMatcher->getManufacturerEnglishName($car['Manufacturer']);
                $downloader->downloadPhotos($car);
            }



            return $data;
        }
        // TODO: DB inserting
        return [];
    }
}