<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\DownloadCarPhotosMessage;
use App\Services\CarPhotoDownloader;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DownloadCarPhotosMessageHandler
{
    private $downloader;

    public function __construct(CarPhotoDownloader $downloader)
    {
        $this->downloader = $downloader;
    }

    public function __invoke(DownloadCarPhotosMessage $message)
    {
        $carData = $message->getCarData();
        $this->downloader->downloadPhotos($carData);
    }
}