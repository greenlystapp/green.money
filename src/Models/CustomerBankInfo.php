<?php

declare(strict_types=1);

namespace Greenlyst\GreenMoney\Models;

class CustomerBankInfo
{
    /**
     * @var
     */
    private $merchantAccountNumber;
    /**
     * @var
     */
    private $bankAccountCompanyName;
    /**
     * @var
     */
    private $bankAccountAddress1;
    /**
     * @var
     */
    private $bankAccountAddress2;
    /**
     * @var
     */
    private $bankAccountCity;
    /**
     * @var
     */
    private $bankAccountState;
    /**
     * @var
     */
    private $bankAccountZip;
    /**
     * @var
     */
    private $bankAccountCountry;
    /**
     * @var
     */
    private $bankAccountName;
    /**
     * @var
     */
    private $note;

    /**
     * CustomerBankInfo constructor.
     *
     * @param $merchantAccountNumber
     * @param $bankAccountCompanyName
     * @param $bankAccountAddress1
     * @param $bankAccountAddress2
     * @param $bankAccountCity
     * @param $bankAccountState
     * @param $bankAccountZip
     * @param $bankAccountCountry
     * @param $bankAccountName
     * @param string $note
     */
    public function __construct($merchantAccountNumber, $bankAccountCompanyName, $bankAccountAddress1, $bankAccountAddress2, $bankAccountCity, $bankAccountState, $bankAccountZip, $bankAccountCountry, $bankAccountName, $note = '')
    {
        $this->merchantAccountNumber = $merchantAccountNumber;
        $this->bankAccountCompanyName = $bankAccountCompanyName;
        $this->bankAccountAddress1 = $bankAccountAddress1;
        $this->bankAccountAddress2 = $bankAccountAddress2;
        $this->bankAccountCity = $bankAccountCity;
        $this->bankAccountState = $bankAccountState;
        $this->bankAccountZip = $bankAccountZip;
        $this->bankAccountCountry = $bankAccountCountry;
        $this->bankAccountName = $bankAccountName;
        $this->note = $note;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'MerchantAccountNumber' => $this->merchantAccountNumber,
            'BankAccountCompanyName' => $this->bankAccountCompanyName,
            'BankAccountAddress1' => $this->bankAccountAddress1,
            'BankAccountAddress2' => $this->bankAccountAddress2,
            'BankAccountCity' => $this->bankAccountCity,
            'BankAccountState' => $this->bankAccountState,
            'BankAccountZip' => $this->bankAccountZip,
            'BankAccountCountry' => $this->bankAccountCountry,
            'BankName' => $this->bankAccountName,
            'Note' => $this->note,
        ];
    }
}
