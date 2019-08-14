<?php

declare(strict_types=1);

namespace Greenlyst\GreenMoney\Models;

class PayeeCheck
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
     * PayeeCheck constructor.
     *
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
     * @return array
     */
    public function toArray()
    {
        return [
            'CheckMemo' => $this->checkMemo,
            'CheckAmount' => $this->checkAmount,
            'CheckDate' => $this->checkDate,
            'CheckNumber' => $this->checkNumber,
        ];
    }
}
