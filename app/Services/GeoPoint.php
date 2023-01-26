<?php

namespace App\Services;

class GeoPoint
{
    protected float $latitude;
    protected float $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function __toString()
    {
        return "{latitude: {$this->latitude}, longitude: {$this->longitude}}";
    }
}
