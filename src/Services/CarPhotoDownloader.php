<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class CarPhotoDownloader
{
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
     * Downloads car photos and saves them in the local storage.
     *
     * @param array $carData The car data array containing the ID and photo URIs.
     * @param string $baseUri The base URI for constructing full photo URLs.
     * @throws RuntimeException If the car data is invalid or the directory cannot be created.
     */
    public function downloadPhotos(array $carData, string $baseUri): void
    {
        $id = $carData['Id'] ?? null;
        $photos = $carData['Photos'] ?? [];

        if (!$id || empty($photos)) {
            throw new RuntimeException('Invalid car data: missing Id or Photos.');
        }

        // Create a folder named after the car ID in the local storage
        $folderPath = $this->storagePath . '/' . $id;
        $this->filesystem->mkdir($folderPath);

        // Download and save each photo
        foreach ($photos as $index => $photoUri) {
            $photoUrl = $baseUri . $photoUri; // Construct the full photo URL
            $photoPath = $folderPath . '/photo_' . ($index + 1) . '.jpg'; // Define the save path for the photo

            $this->downloadPhoto($photoUrl, $photoPath); // Download and save the photo
        }
    }

    /**
     * Downloads a single photo from the given URL and saves it to the specified path.
     *
     * @param string $photoUrl The full URL of the photo to download.
     * @param string $photoPath The path where the photo will be saved.
     * @throws RuntimeException If the photo cannot be downloaded.
     */
    public function downloadPhoto(string $photoUrl, string $photoPath): void
    {
        // TODO Async processing

        $response = $this->client->get($photoUrl, ['sink' => $photoPath]);

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException("Failed to download photo from {$photoUrl}.");
        }
    }
}