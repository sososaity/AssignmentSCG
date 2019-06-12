<?php
namespace RestaurantsAPI\Model;

class Address
{
    public $placeName;
    public $detail;

    public function exchangeArray(array $data)
    {
        $this->placeName   = !empty($data['placeName']) ? $data['placeName'] : null;
        $this->detail      = !empty($data['detail']) ? $data['detail'] : null;

    }
}
