<?php


namespace Greenlyst\GreenMoney\Models;


class BankInfo
{
    private $routingNumber;
    private $accountNumber;
    private $bankName;

    /**
     * BankInfo constructor.
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

    /**
     * @return string
     */
    public function getRoutingNumber()
    {
        return $this->routingNumber;
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }
}