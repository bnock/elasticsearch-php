<?php

namespace BNock\ElasticsearchPHP\Models;

use Illuminate\Contracts\Support\Arrayable;

class Coordinate implements Arrayable
{
    public static function create(float $latitude, float $longitude): static
    {
        return new static($latitude, $longitude);
    }

    public function __construct(protected float $latitude, protected float $longitude)
    {
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function toArray(): array
    {
        return [$this->longitude, $this->latitude];
    }
}
