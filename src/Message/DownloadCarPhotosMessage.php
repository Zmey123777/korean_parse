<?php

declare(strict_types=1);

namespace App\Message;

class DownloadCarPhotosMessage
{
    private $carData;

    public function __construct(array $carData)
    {
        $this->carData = $carData;
    }

    public function getCarData(): array
    {
        return $this->carData;
    }
}
