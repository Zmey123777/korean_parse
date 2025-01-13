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
        $carManufacturers = [
            'Hyundai' => '현대',
            'Genesis' => '제네시스',
            'Kia' => '기아',
            'Chevrolet (GM Daewoo)' => '쉐보레(GM대우)',
            'Renault Korea (Samsung)' => '르노코리아(삼성)',
            'KG Mobility (SsangYong)' => 'KG모빌리티(쌍용)',
            'Other Manufacturers' => '기타 제조사',
        ];

        $carModels = [
            'Grandeur' => '그랜저',
            'Avante' => '아반떼',
            'Sonata' => '쏘나타',
            'Santa Fe' => '싼타페',
            'Palisade' => '팰리세이드',
            'Starex' => '스타렉스',
            'i30' => 'i30',
            'i40' => 'i40',
            'ST' => 'ST',
            'Galloper' => '갤로퍼',
            'Granada' => '그라나다',
            'Grace' => '그레이스',
            'Nexo' => '넥쏘',
            'Dynasty' => '다이너스티',
            'Lavita' => '라비타',
            'Marcha' => '마르샤',
            'Maxcruze' => '맥스크루즈',
            'Venu' => '베뉴',
            'Veracruz' => '베라크루즈',
            'Verna' => '베르나',
            'Veloster' => '벨로스터',
            'BlueOn' => '블루온',
            'Santamo' => '산타모',
            'Scoop' => '스쿠프',
            'Staria' => '스타리아',
            'Stella' => '스텔라',
            'Solaris' => '쏠라티',
            'Aslan' => '아슬란',
            'Ioniq' => '아이오닉',
            'Ioniq 5' => '아이오닉5',
            'Ioniq 6' => '아이오닉6',
            'Atos' => '아토스',
            'Equus' => '에쿠스',
            'Accent' => '엑센트',
            'Excel' => '엑셀',
            'Elantra' => '엘란트라',
            'Genesis' => '제네시스',
            'Casper' => '캐스퍼',
            'Kona' => '코나',
            'Cortina' => '코티나',
            'Click' => '클릭',
            'Terracan' => '테라칸',
            'Tuscani' => '투스카니',
            'Tucson' => '투싼',
            'Trajet XG' => '트라제 XG',
            'Tiburon' => '티뷰론',
            'Pony' => '포니',
            'Presto' => '프레스토',
        ];

        $manufacturer = $carManufacturers[$context['brand']] ?? null;
        $carModel = $carModels[$context['car']] ?? null;

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
                //'q' => ,
                'q' => $qFilter,
                'sr' => "|ModifiedDate|{$offset}|{$limit}",
            ];

            try {
                $response = $this->client->get($this->baseUrl, [
                    'query' => $params,
                    'headers' => $headers,
                ]);

                if ($response->getStatusCode() == 200) {
                    $data = json_decode($response->getBody(), true);
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