<?php

// src/Entity/Car.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "cars")]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "integer")]
    private int $year;

    #[ORM\Column(type: "string")]
    private string $car_make;

    // getters and setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;
        return $this;
    }

    public function getCarMake(): string
    {
        return $this->car_make;
    }

    public function setCarMake(string $car_make): self
    {
        $this->car_make = $car_make;
        return $this;
    }
}