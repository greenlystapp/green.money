<?php

declare(strict_types=1);

namespace Greenlyst\GreenMoney\Models;

class PayeeBankInfo
{
    private $routingNumber;
    private $accountNumber;
    private $bankName;

    /**
     * PayeeBankInfo constructor.
     *
     * @param string $routingNumber
     * @param string $accountNumber
     * @param string $bankName
     */
    public function __construct($routingNumber, $accountNumber, $bankName)
    {
        $this->routingNumber = $routingNumber;
        $this->accountNumber = $accountNumber;
        $this->bankName = $bankName;
    }

    public function toArray()
    {
        return [
            'RoutingNumber' => $this->routingNumber,
            'AccountNumber' => $this->accountNumber,
            'BankName' => $this->bankName,
        ];
    }
}
