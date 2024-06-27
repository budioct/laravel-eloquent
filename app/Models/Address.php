<?php

namespace App\Models;

class Address
{

    /**
     * class ini digunakan untuk sebagai DTO untuk column address yang ada di table person
     */

    public string $street;
    public string $city;
    public string $country;
    public string $postal_code;

    public function __construct(string $street, string $city, string $country, string $postal_code)
    {
        $this->street = $street;
        $this->city = $city;
        $this->country = $country;
        $this->postal_code = $postal_code;
    }

}
