<?php

declare(strict_types=1);

namespace Greenlyst\GreenMoney\Models;

class Payee
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $emailAddress;
    /**
     * @var string
     */
    private $phone;
    /**
     * @var string
     */
    private $phoneExtension;
    /**
     * @var string
     */
    private $address1;
    /**
     * @var string
     */
    private $address2;
    /**
     * @var string
     */
    private $city;
    /**
     * @var string
     */
    private $state;
    /**
     * @var string
     */
    private $zip;
    /**
     * @var string
     */
    private $country;

    /**
     * CustomerInfo constructor.
     *
     * @param string $name
     * @param string $emailAddress
     * @param string $phone
     * @param string $phoneExtension
     * @param string $address1
     * @param string $address2
     * @param string $city
     * @param string $state
     * @param string $zip
     * @param string $country
     */
    public function __construct($name, $emailAddress, $phone, $phoneExtension, $address1, $address2, $city, $state, $zip, $country)
    {
        $this->name = $name;
        $this->emailAddress = $emailAddress;
        $this->phone = $phone;
        $this->phoneExtension = $phoneExtension;
        $this->address1 = $address1;
        $this->address2 = $address2;
        $this->city = $city;
        $this->state = $state;
        $this->zip = $zip;
        $this->country = $country;
    }

    public function toArray()
    {
        return [
            'Name' => $this->name,
            'EmailAddress' => $this->emailAddress,
            'Address1' => $this->address1,
            'Address2' => $this->address2,
            'Phone' => $this->phone,
            'PhoneExtension' => $this->phoneExtension,
            'City' => $this->city,
            'State' => $this->state,
            'Zip' => $this->zip,
            'Country' => $this->country,
        ];
    }
}
