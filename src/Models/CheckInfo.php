<?php


namespace Greenlyst\GreenMoney\Models;


class CheckInfo
{

    /**
     * @var string
     */
    private $checkMemo;
    /**
     * @var string
     */
    private $checkAmount;
    /**
     * @var string
     */
    private $checkDate;
    /**
     * @var string
     */
    private $checkNumber;

    /**
     * CheckInfo constructor.
     * @param string $checkMemo
     * @param string $checkAmount
     * @param string $checkDate
     * @param string $checkNumber
     */
    public function __construct($checkMemo, $checkAmount, $checkDate, $checkNumber)
    {
        $this->checkMemo = $checkMemo;
        $this->checkAmount = $checkAmount;
        $this->checkDate = $checkDate;
        $this->checkNumber = $checkNumber;
    }

    /**
     * @return string
     */
    public function getCheckMemo()
    {
        return $this->checkMemo;
    }

    /**
     * @return string
     */
    public function getCheckAmount()
    {
        return $this->checkAmount;
    }

    /**
     * @return string
     */
    public function getCheckDate()
    {
        return $this->checkDate;
    }

    /**
     * @return string
     */
    public function getCheckNumber()
    {
        return $this->checkNumber;
    }
}