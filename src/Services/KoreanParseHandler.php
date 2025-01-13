<?php

namespace App\Services;

use App\Client\EncarApiClient;

class KoreanParseHandler
{
    private $client;

    private const CARMAP = [
        'Hyundai' => '현대',
        'Genesis' => '제네시스',
        'Kia' => '기아',
        'Chevrolet (GM Daewoo)' => '쉐보레(GM대우)',
        'Renault Korea (Samsung)' => '르노코리아(삼성)',
        'KG Mobility (SsangYong)' => 'KG모빌리티(쌍용)',
        'Other Manufacturers' => '기타 제조사',
    ];

    public function __construct(EncarApiClient $client)
    {
        $this->client = $client;
    }

    public function handle($context, $dryRun = false)
    {
        if (!$dryRun) {
            return [];
        }

        $carManufacturers = array_flip(self::CARMAP);

        $data = $this->client->fetchCars($context, 8, 8);

        foreach ($data as &$car) {
            $car['Brand'] = $carManufacturers[$car['Manufacturer']];
        }

        return $data;
    }
}