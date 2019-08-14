<?php

declare(strict_types=1);

namespace Greenlyst\GreenMoney\Models;

class CustomerCheck
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
     * PayeeCheck constructor.
     *
     * @param string $checkMemo
     * @param string $checkAmount
     * @param string $checkDate
     */
    public function __construct($checkMemo, $checkAmount, $checkDate)
    {
        $this->checkMemo = $checkMemo;
        $this->checkAmount = $checkAmount;
        $this->checkDate = $checkDate;
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
        ];
    }
}
