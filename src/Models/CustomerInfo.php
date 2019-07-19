<?php

namespace Greenlyst\GreenMoney\Models;

class CustomerInfo
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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getPhoneExtension()
    {
        return $this->phoneExtension;
    }

    /**
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }
}