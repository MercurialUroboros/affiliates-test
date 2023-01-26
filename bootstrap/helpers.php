<?php

use App\Services\GeoPoint;
use App\Services\Affiliate;

const EARTH_RADIUS = 6371;

/**
 * @param float deg Degree we want to transfrom into radians
 */
if (!function_exists('convertDegToRad')) {
    function convertDegToRad(float $deg):float
    {
        return ($deg * pi()) / 180;
    }
}

/**
 * Calculate distance in KM from one geopoint to another
 *
 * @param GeoPoint fromPoint
 * @param GeoPoint toPoint
 * @return int
 */
if (!function_exists('getDistanceFromLatLonInKm')) {
    function getDistanceFromLatLonInKm(GeoPoint $point1, GeoPoint $point2): float
    {
        $lat1 = $point1->getLatitude();
        $lat2 = $point2->getLatitude();
        $lon1 = $point1->getLongitude();
        $lon2 = $point2->getLongitude();

        $dLat = convertDegToRad($lat2 - $lat1);
        $dLon = convertDegToRad($lon2 - $lon1);

        $squarehalfChordLength = sin($dLat / 2) * sin($dLat / 2) + cos(convertDegToRad($lat1)) * cos(convertDegToRad($lat2)) * sin($dLon / 2) * sin($dLon / 2);

        $angularDistance = 2 * atan2(sqrt($squarehalfChordLength), sqrt(1 - $squarehalfChordLength));
        $distance = EARTH_RADIUS * $angularDistance;
        return $distance;
    }
}

/**
 * Given a file with json formatted strings, following the Affiliate properties, divided by breakline
 * Will parse the file, and filter the affiliates by proximity and sorted by ID
 *
 * @param string input file
 * @param GeoPoint edgePoint (the point we would like to calculate the distance from)
 * @return float maximumDistance allowed distance from the edgePoint
 */
if (!function_exists('fetchAffiliatesByProximity')) {
    function fetchAffiliatesByProximity(string $file, GeoPoint $edgePoint, float $maximumDistance): array
    {
        $affiliatesArray = [];

        foreach (explode("\n", $file) as $key => $line) {
            $jsonUser = json_decode($line);
            if (is_null($jsonUser)) {
                continue;
            }
            $affiliatesArray[$key] = new Affiliate($jsonUser->name, $jsonUser->affiliate_id, $jsonUser->latitude, $jsonUser->longitude);
            $affiliatesArray[$key]->setCloseToProximity(getDistanceFromLatLonInKm($affiliatesArray[$key], $edgePoint) <= $maximumDistance);
        }

        // Filtering by who is eligible for the proximity check
        $affiliatesCloseToProximity = array_filter($affiliatesArray, function ($item) {
            return $item->getIsCloseToProximity() === true;
        });

        usort($affiliatesCloseToProximity, function ($x, $y) {
            return $x->getAffiliateId() - $y->getAffiliateId();
        });

        return $affiliatesCloseToProximity;
    }
}
