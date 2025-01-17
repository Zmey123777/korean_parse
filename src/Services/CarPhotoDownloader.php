<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class CarPhotoDownloader
{
    private const BASE_URI = 'https://ci.encar.com';
    private Client $client;
    private Filesystem $filesystem;
    private string $storagePath;

    public function __construct(KernelInterface $kernel)
    {
        $this->client = new Client();
        $this->filesystem = new Filesystem();
        $this->storagePath = $kernel->getProjectDir() . '/var/storage';
    }

    /**
     * Downloads car photos and saves them in the local storage asynchronously.
     *
     * @param array $carData The car data array containing the ID and photo URIs.
     * @throws RuntimeException If the car data is invalid or the directory cannot be created.
     */
    public function downloadPhotos(array $carData): void
    {
        $id = $carData['Id'] ?? null;
        $photos = $carData['Photos'] ?? [];

        if (!$id || empty($photos)) {
            throw new RuntimeException('Invalid car data: missing Id or Photos.');
        }

        // Create a folder named after the car ID in the local storage
        $folderPath = $this->storagePath . '/' . $id;
        $this->filesystem->mkdir($folderPath);

        $promises = [];

        // Download and save each photo asynchronously
        foreach ($photos as $index => $photoUri) {
            $photoUrl = self::BASE_URI . $photoUri['location'];
            $photoPath = $folderPath . '/photo_' . ($index + 1) . '.jpg';

            $promises[$photoPath] = $this->client->getAsync($photoUrl, ['sink' => $photoPath]);
        }


        $results = Promise\Utils::settle($promises)->wait();

        foreach ($results as $photoPath => $result) {
            if ($result['state'] === 'rejected') {
                throw new RuntimeException("Failed to download photo to {$photoPath}.");
            }
        }
    }
}