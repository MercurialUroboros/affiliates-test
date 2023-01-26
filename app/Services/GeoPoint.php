<?php

namespace App\Services;
use App\Services\GeoContract;

class GeoPoint implements GeoContract
{
    /**
     * Latitude of the point
     */
    protected float $latitude;
    /**
     * Longitude of the point
     */
    protected float $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * Getter for the latitude
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * Getter for the longitude
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * Jsonified version of the object
     * @return string
     */
    public function __toString(): string
    {
        return "{latitude: {$this->latitude}, longitude: {$this->longitude}}";
    }
}
