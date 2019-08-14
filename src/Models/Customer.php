<?php

declare(strict_types=1);

namespace Greenlyst\GreenMoney\Models;

class Customer
{
    /**
     * @var string
     */
    private $nickName;
    /**
     * @var string
     */
    private $firstName;
    /**
     * @var string
     */
    private $lastName;
    /**
     * @var string
     */
    private $phoneWork;
    /**
     * @var string
     */
    private $phoneWorkExtension;
    /**
     * @var string
     */
    private $emailAddress;

    /**
     * Customer constructor.
     *
     * @param $nickName
     * @param $firstName
     * @param $lastName
     * @param $phoneWork
     * @param $phoneWorkExtension
     * @param $emailAddress
     */
    public function __construct($nickName, $firstName, $lastName, $phoneWork, $phoneWorkExtension, $emailAddress)
    {
        $this->nickName = $nickName;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phoneWork = $phoneWork;
        $this->phoneWorkExtension = $phoneWorkExtension;
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'NickName' => $this->nickName,
            'NameFirst' => $this->firstName,
            'NameLast' => $this->lastName,
            'PhoneWork' => $this->phoneWork,
            'PhoneWorkExtension' => $this->phoneWorkExtension,
            'EmailAddress' => $this->emailAddress,
        ];
    }
}
