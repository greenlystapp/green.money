<?php

declare(strict_types=1);

namespace Greenlyst\GreenMoney\Models;

class CustomerBankInfo
{
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
    private $bankName;
    /**
     * @var
     */
    private $note;
    private $bankAccountNumber;
    private $bankRoutingNumber;
    /**
     * @var string
     */
    private $merchantReferenceId;

    /**
     * CustomerBankInfo constructor.
     *
     * @param        $bankAccountCompanyName
     * @param        $bankAccountAddress1
     * @param        $bankAccountAddress2
     * @param        $bankAccountCity
     * @param        $bankAccountState
     * @param        $bankAccountZip
     * @param        $bankAccountCountry
     * @param        $bankName
     * @param        $bankAccountNumber
     * @param        $bankRoutingNumber
     * @param string $merchantReferenceId
     * @param string $note
     */
    public function __construct($bankAccountCompanyName, $bankAccountAddress1, $bankAccountAddress2, $bankAccountCity, $bankAccountState, $bankAccountZip, $bankAccountCountry, $bankName, $bankAccountNumber, $bankRoutingNumber, $merchantReferenceId = '', $note = '')
    {
        $this->bankAccountCompanyName = $bankAccountCompanyName;
        $this->bankAccountAddress1 = $bankAccountAddress1;
        $this->bankAccountAddress2 = $bankAccountAddress2;
        $this->bankAccountCity = $bankAccountCity;
        $this->bankAccountState = $bankAccountState;
        $this->bankAccountZip = $bankAccountZip;
        $this->bankAccountCountry = $bankAccountCountry;
        $this->bankName = $bankName;
        $this->note = $note;
        $this->bankAccountNumber = $bankAccountNumber;
        $this->bankRoutingNumber = $bankRoutingNumber;
        $this->merchantReferenceId = $merchantReferenceId;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'BankAccountCompanyName' => $this->bankAccountCompanyName,
            'BankAccountAddress1'    => $this->bankAccountAddress1,
            'BankAccountAddress2'    => $this->bankAccountAddress2,
            'BankAccountCity'        => $this->bankAccountCity,
            'BankAccountState'       => $this->bankAccountState,
            'BankAccountZip'         => $this->bankAccountZip,
            'BankAccountCountry'     => $this->bankAccountCountry,
            'BankName'               => $this->bankName,
            'RoutingNumber'          => $this->bankRoutingNumber,
            'AccountNumber'          => $this->bankAccountNumber,
            'MerchantAccountNumber'  => $this->merchantReferenceId,
            'Note'                   => $this->note,
        ];
    }
}
