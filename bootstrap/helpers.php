<?php

use App\Classes\GeoPoint;
use App\Classes\User;

const EARTH_RADIUS = 6371;

if (!function_exists('convertDegToRad')) {
    function convertDegToRad(float $deg)
    {
        return ($deg * pi()) / 180;
    }
}

/**
 * Parse the give path as array json and return an array or array
 *
 * @param float  $latFrom
 * @param float  $lngFrom
 * @param float  $latTo
 * @param float  $lngTo
 * @param int  $radius
 *
 * @return array
 */
if (!function_exists('getDistanceFromLatLonInKm')) {
    function getDistanceFromLatLonInKm(GeoPoint $point1, GeoPoint $point2)
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

if (!function_exists('fetchUsersByProximity')) {
    function fetchUsersByProximity(string $file, GeoPoint $edgePoint, float $maximumDistance)
    {
        $usersArray = [];



        foreach (explode("\n", $file) as $key => $line) {
            $jsonUser = json_decode($line);
            if(is_null($jsonUser))continue;
            $usersArray[$key] = new User($jsonUser->name, $jsonUser->affiliate_id, $jsonUser->latitude, $jsonUser->longitude);
            $usersArray[$key]->setCloseToProximity(getDistanceFromLatLonInKm($usersArray[$key], $edgePoint) <= $maximumDistance);
        }

        
        // Filtering by who is eligible for the proximity check
        $usersCloseToProximity = array_filter($usersArray, function ($item) {
            return $item->getIsCloseToProximity() === true;
        });

        usort($usersCloseToProximity, function ($x, $y) {
            return $x->getAffiliateId() - $y->getAffiliateId();
        });

        return $usersCloseToProximity;
    }
}
