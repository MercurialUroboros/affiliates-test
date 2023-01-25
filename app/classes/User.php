<?php

namespace App\Classes;
use App\Classes\GeoPoint;

class User extends GeoPoint
{
    protected string $name;
    protected string $affiliate_id;
    protected bool $is_close_to_proximity = false;

    public function __construct(string $name, string $affiliate_id, string $latitude, string $longitude) {
        parent::__construct($latitude, $longitude);
        $this->name = $name;
        $this->affiliate_id = $affiliate_id;
    }

    public function setCloseToProximity(bool $isClose){
        $this->is_close_to_dublin = $isClose;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAffiliateId()
    {
        return $this->affiliate_id;
    }

    public function getIsCloseToProximity()
    {
        return $this->is_close_to_dublin;
    }

  }