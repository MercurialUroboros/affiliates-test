<?php

namespace App\Services;
use App\Services\GeoContract;

class GeoPoint implements GeoContract
{
    protected float $latitude;
    protected float $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function __toString(): string
    {
        return "{latitude: {$this->latitude}, longitude: {$this->longitude}}";
    }
}
