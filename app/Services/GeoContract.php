<?php

namespace App\Services;

/**
 * Defines a contract for a Geo Localazitation point
 */
interface GeoContract
{
    public function getLatitude(): float;
    public function getLongitude(): float;
    public function __toString(): string;
}
