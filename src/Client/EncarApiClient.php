<?php

declare(strict_types=1);

namespace App\Client;

use GuzzleHttp\Client;
use App\Services\CarModelMatcher;
use GuzzleHttp\Exception\GuzzleException;

class EncarApiClient
{
    private $client;
    private $baseUrl;

    public function __construct(private readonly CarModelMatcher $carModelMatcher)
    {
        $this->client = new Client();
        $this->baseUrl = 'https://api.encar.com/search/car/list/general';
    }

    /**
     * Fetch cars with pagination support.
     *
     * @param array $context Manufacturer context (e.g., 'Hyundai', 'Genesis').
     * @param int $limit Number of records per request (max 40).
     * @param int $maxRecords Maximum number of records to fetch (0 for all).
     * @return array
     * @throws GuzzleException
     */
    public function fetchCars(array $context, int $limit = 40, int $maxRecords = 0): array
    {
        $manufacturer = $this->carModelMatcher->getManufacturerKoreanName($context['brand']);
        $carModel = $this->carModelMatcher->getModelKoreanName($context['car'] ?? '');

        if (null !== $carModel) {
            $qFilter = "(And.(And.Hidden.N._.(C.CarType.Y._.(C.Manufacturer.{$manufacturer}._.ModelGroup.{$carModel}.)))_.AdType.A.)";
        } else {
            $qFilter = "(And.(And.Hidden.N._.(C.CarType.Y._.Manufacturer.{$manufacturer}.))_.AdType.A.)";
        }

        if (!$manufacturer) {
            throw new \InvalidArgumentException('Invalid manufacturer context.');
        }

        $allCars = [];
        $offset = 0;
        $limit = min($limit, 40); // Ensure limit does not exceed 40

        while (true) {
            try {
                $response = $this->client->get($this->baseUrl, [
                    'query' => [
                        'count' => 'true',
                        'q' => $qFilter,
                        'sr' => "|ModifiedDate|{$offset}|{$limit}",
                    ],
                    'headers' => [
                        'Accept' => 'application/json, text/javascript, */*; q=0.01',
                        'Accept-Encoding' => 'gzip, deflate, br',
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
                    ],
                ]);

                if ($response->getStatusCode() === 200) {
                    $body = $response->getBody()->getContents();
                    $data = json_decode($body, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new \Exception('Failed to decode JSON: ' . json_last_error_msg());
                    }

                    if (empty($data['SearchResults'])) {
                        break;
                    }

                    foreach ($data['SearchResults'] as $car) {
                        $allCars[] = $car;
                    }

                    $offset += $limit;
                } else {
                    throw new \Exception('Failed to retrieve data. Status code: ' . $response->getStatusCode());
                }
            } catch (\Exception $e) {
                throw new \Exception('API request failed: ' . $e->getMessage());
            }

            if ($maxRecords > 0 && count($allCars) >= $maxRecords) {
                break;
            }
            sleep(1);
        }

        return $allCars;
    }
}