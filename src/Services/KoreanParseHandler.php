<?php

declare(strict_types=1);

namespace App\Services;

use App\Client\EncarApiClient;
use App\Message\DownloadCarPhotosMessage;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class KoreanParseHandler
{
    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly EncarApiClient $client,
        private readonly CarModelMatcher $carModelMatcher,
        private readonly MessageBusInterface $bus
    ){}

    public function handle(array $context, bool $dryRun = false): array
    {
        if($dryRun){
            $data = $this->client->fetchCars($context, 8, 8);


            foreach ($data as &$car) {
                $car['Brand'] = $this->carModelMatcher->getManufacturerEnglishName($car['Manufacturer']);
                $message = new DownloadCarPhotosMessage($car);
                $this->bus->dispatch($message);
            }
            
            return $data;
        }
        // TODO: DB inserting
        return [];
    }
}