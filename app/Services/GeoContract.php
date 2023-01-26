<?php

namespace App\Services;

/**
 * Defines a contract for a Geo Localazitation point
 */
interface GeoContract
{
    /**
     * @return float latitude
     */
    public function getLatitude(): float;
    /**
     * @return float longitude
     */
    public function getLongitude(): float;
    /**
     * @return float stringified version of the object
     */
    public function __toString(): string;
}
