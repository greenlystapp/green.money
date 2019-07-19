<?php


namespace Greenlyst\GreenMoney\Models;


class RecurringObject
{
    private $recurringType;
    private $recurringOffset;
    private $recurringPayments;

    public const RECURRING_TYPE_MONTHLY = 'M';
    public const RECURRING_TYPE_WEEKLY = 'W';
    public const RECURRING_TYPE_DAILY = 'D';

    /**
     * RecurringObject constructor.
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
     * @return string
     */
    public function getRecurringType()
    {
        return $this->recurringType;
    }

    /**
     * @return string
     */
    public function getRecurringOffset()
    {
        return $this->recurringOffset;
    }

    /**
     * @return string
     */
    public function getRecurringPayments()
    {
        return $this->recurringPayments;
    }
}