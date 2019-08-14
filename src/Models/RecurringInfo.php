<?php

declare(strict_types=1);

namespace Greenlyst\GreenMoney\Models;

class RecurringInfo
{
    private $recurringType;
    private $recurringOffset;
    private $recurringPayments;

    public const RECURRING_TYPE_MONTHLY = 'M';
    public const RECURRING_TYPE_WEEKLY = 'W';
    public const RECURRING_TYPE_DAILY = 'D';

    /**
     * RecurringInfo constructor.
     *
     * @param string $recurringType
     * @param string $recurringOffset
     * @param string $recurringPayments
     */
    public function __construct($recurringType, $recurringOffset, $recurringPayments)
    {
        $this->recurringType = $recurringType;
        $this->recurringOffset = $recurringOffset;
        $this->recurringPayments = $recurringPayments;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'RecurringType' => $this->recurringType,
            'RecurringOffset' => $this->recurringOffset,
            'RecurringPayments' => $this->recurringPayments,
        ];
    }
}
