<?php

namespace Tests\Feature;

use App\Services\GeoPoint;
use App\Services\Affiliate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class GeoControllerTest extends TestCase
{
    protected $maximumDistance = 100;
    protected $dublinGeoPoint;
    /**
     * Test affiliates not in range of distance.
     *
     * @return void
     */
    public function test_user_not_in_range()
    {
        $affiliatesNotInRange = fetchAffiliatesByProximity($this->createRandomAffiliatesFileNotInRange(), $this->dublinGeoPoint, $this->maximumDistance);
        $this->assertTrue(count($affiliatesNotInRange) == 0);
    }

    /**
     * Test affiliates in range of distance.
     *
     * @return void
     */
    public function test_affiliates_in_range()
    {
        $affiliatesInRange = fetchAffiliatesByProximity($this->createRandomAffiliatesFileInRange(), $this->dublinGeoPoint, $this->maximumDistance);

        $this->assertTrue(count($affiliatesInRange) == 10);
    }

    /**
     * Test affiliates from file
     *
     * @return void
     */
    public function test_file_affiliates()
    {
        $affiliatesFile = Storage::disk('local')->get('affiliates-data/affiliates.txt');

        $affiliatesInRange = fetchAffiliatesByProximity($affiliatesFile, $this->dublinGeoPoint, $this->maximumDistance);

        $this->assertTrue(count($affiliatesInRange) == 16);
    }

    /**
     * Creating affiliates with an arbitrary big latitude/longitude so not to fall within 100km
     * @return string The formatted string, with affiliates divided by break-line
     */
    public function createRandomAffiliatesFileNotInRange()
    {
        $affiliatesString = '';

        for ($x = 0; $x <= 10; $x++) {
            $randomAffiliate = new Affiliate(Str::random($strlentgh = 16), (string) rand(2, 50), (mt_rand() * 100) / mt_getrandmax(), (mt_rand() * 100) / mt_getrandmax());
            $affiliatesString .= $randomAffiliate->__toString() . "\r\n";
        }

        return $affiliatesString;
    }

    /**
     * Generating 10 affiliates always in range
     * @return string The formatted string, with affiliates always in range from dublin divided by break-line
     */
    public function createRandomAffiliatesFileInRange()
    {
        $affilatesString = '';

        for ($x = 0; $x < 10; $x++) {
            $latitude = mt_rand(53.0 * 10, 53.9 * 10) / 10;
            $longitude = mt_rand(-6.9 * 10, -6.0 * 10) / 10;
            $randomAffiliate = new Affiliate(Str::random($strlentgh = 16), (string) rand(2, 50), $latitude, $longitude);
            $affilatesString .= $randomAffiliate->__toString() . "\r\n";
        }

        return $affilatesString;
    }

    protected function setUp() : void {
        parent::setUp();
        $this->dublinGeoPoint = new GeoPoint(53.3340285, -6.2535495);
    }
}
