<?php

namespace App\Client;

use GuzzleHttp\Client;

class EncarApiClient
{
    private $client;
    private $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = 'https://api.encar.com/search/car/list/general';
    }

    public function fetchCars()
    {
        $params = [
            'count' => 'true',
            'q' => '(And.(And.Hidden.N._.(C.CarType.Y._.Manufacturer.현대.))_.AdType.A.)',
            'sr' => '|ModifiedDate|0|8',
        ];

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

        try {
            $response = $this->client->get($this->baseUrl, [
                'query' => $params,
                'headers' => $headers,
            ]);

            if ($response->getStatusCode() == 200) {
                return json_decode($response->getBody(), true);
            }

            throw new \Exception('Failed to retrieve data. Status code: ' . $response->getStatusCode());
        } catch (\Exception $e) {
            throw $e;
        }
    }
}