<?php

declare(strict_types=1);

namespace App\Client;

use GuzzleHttp\Client;
use App\Services\CarModelMatcher;

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
     */
    public function fetchCars(array $context, int $limit = 40, int $maxRecords = 0): array
    {

        $manufacturer = $this->carModelMatcher->getManufacturerKoreanName($context['brand']);
        $carModel = $this->carModelMatcher->getModelKoreanName($context['car'] ?? '');

        if(null !== $carModel) {
            $qFilter = "(And.(And.Hidden.N._.(C.CarType.Y._.(C.Manufacturer.{$manufacturer}._.ModelGroup.{$carModel}.)))_.AdType.A.)";
        } else {
            $qFilter = "(And.(And.Hidden.N._.(C.CarType.Y._.Manufacturer.{$manufacturer}.))_.AdType.A.)";
        }


        if (!$manufacturer) {
            throw new \InvalidArgumentException('Invalid manufacturer context.');
        }

        $headers = [
            'Accept' => 'application/json, text/javascript, */*; q=0.01',
            'Accept-Encoding' => 'gzip, deflate, br, zstd',
            'Accept-Language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
            'Connection' => 'keep-alive',
            'Host' => 'api.encar.com',
            'Origin' => 'http://www.encar.com',
            'Referer' => 'http://www.encar.com/',
            'Sec-Ch-Ua' => '"Google Chrome";v="131", "Chromium";v="131", "Not_A Brand";v="24"',
            'Sec-Ch-Ua-Mobile' => '?0',
            'Sec-Ch-Ua-Platform' => '"Windows"',
            'Sec-Fetch-Dest' => 'empty',
            'Sec-Fetch-Mode' => 'cors',
            'Sec-Fetch-Site' => 'cross-site',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
        ];

        $allCars = [];
        $offset = 0;
        $limit = min($limit, 40); // Ensure limit does not exceed 40

        do {
            $params = [
                'count' => 'true',
                'q' => $qFilter,
                'sr' => "|ModifiedDate|{$offset}|{$limit}",
            ];

            try {
                $response = $this->client->get($this->baseUrl, [
                    'query' => $params,
                    'headers' => $headers,
                ]);

                if ($response->getStatusCode() == 200) {
                    $data = json_decode($response->getBody()->getContents(), true);
                    if (isset($data['SearchResults']) && !empty($data['SearchResults'])) {
                        $allCars = array_merge($allCars, $data['SearchResults']);
                        $offset += $limit; // Increment offset for the next request
                    } else {
                        break; // No more records
                    }
                } else {
                    throw new \Exception('Failed to retrieve data. Status code: ' . $response->getStatusCode());
                }
            } catch (\Exception $e) {
                throw new \Exception('API request failed: ' . $e->getMessage());
            }

            // Stop if maxRecords is reached
            if ($maxRecords > 0 && count($allCars) >= $maxRecords) {
                break;
            }

        } while (true);

        return $allCars;
    }
}