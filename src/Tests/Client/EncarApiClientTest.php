<?php

declare(strict_types=1);

namespace App\Tests\Client;

use App\Client\EncarApiClient;
use App\Services\CarModelMatcher;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Psr\Http\Message\ResponseInterface;

class EncarApiClientTest extends KernelTestCase
{
    private $carModelMatcher;
    private $client;
    private $encarApiClient;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->carModelMatcher = $this->createMock(CarModelMatcher::class);
        $this->client = $this->createMock(Client::class);

        $this->encarApiClient = new EncarApiClient($this->carModelMatcher);

        $reflection = new \ReflectionClass($this->encarApiClient);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($this->encarApiClient, $this->client);
    }

    public function testFetchCars(): void
    {
        $context = ['brand' => 'Hyundai', 'car' => 'Sonata'];
        $limit = 10;
        $maxRecords = 20;

        $this->carModelMatcher->method('getManufacturerKoreanName')
            ->with('Hyundai')
            ->willReturn('현대');

        $this->carModelMatcher->method('getModelKoreanName')
            ->with('Sonata')
            ->willReturn('소나타');

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(200);
        $mockResponse->method('getBody')->willReturn(json_encode([
            'SearchResults' => [
                ['id' => 1, 'name' => 'Car 1'],
                ['id' => 2, 'name' => 'Car 2'],
            ],
        ]));

        $this->client->method('get')
            ->willReturn($mockResponse);

        $result = $this->encarApiClient->fetchCars($context, $limit, $maxRecords);

        $this->assertCount(2, $result);
        $this->assertEquals('Car 1', $result[0]['name']);
        $this->assertEquals('Car 2', $result[1]['name']);
    }

    public function testFetchCarsWithInvalidManufacturer(): void
    {
        $context = ['brand' => 'InvalidBrand'];
        $limit = 10;
        $maxRecords = 20;

        $this->carModelMatcher->method('getManufacturerKoreanName')
            ->with('InvalidBrand')
            ->willReturn(null);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid manufacturer context.');

        $this->encarApiClient->fetchCars($context, $limit, $maxRecords);
    }

    public function testFetchCarsWithApiError(): void
    {
        $context = ['brand' => 'Hyundai', 'car' => 'Sonata'];
        $limit = 10;
        $maxRecords = 20;

        $this->carModelMatcher->method('getManufacturerKoreanName')
            ->with('Hyundai')
            ->willReturn('현대');

        $this->carModelMatcher->method('getModelKoreanName')
            ->with('Sonata')
            ->willReturn('소나타');

        $this->client->method('get')
            ->willThrowException(new \Exception('API request failed'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('API request failed: API request failed');

        $this->encarApiClient->fetchCars($context, $limit, $maxRecords);
    }
}