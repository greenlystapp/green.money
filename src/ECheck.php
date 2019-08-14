<?php

declare(strict_types=1);

namespace Greenlyst\GreenMoney;

use Exception;
use Greenlyst\GreenMoney\Models\Customer;
use Greenlyst\GreenMoney\Models\CustomerBankInfo;
use Greenlyst\GreenMoney\Models\CustomerCheck;
use Greenlyst\GreenMoney\Models\Payee;
use Greenlyst\GreenMoney\Models\PayeeBankInfo;
use Greenlyst\GreenMoney\Models\PayeeCheck;
use Greenlyst\GreenMoney\Models\RecurringInfo;
use Greenlyst\GreenMoney\Util\Request;

final class ECheck
{
    /**
     * @var Request $client
     */
    private $client;

    /**
     * ECheck constructor.
     *
     * @param string $clientId
     * @param string $apiPassword
     * @param bool $live
     */
    public function __construct($clientId, $apiPassword, $live = true)
    {
        $this->client = new Request(Request::TYPE_CHECK, $clientId, $apiPassword, $live);
    }

    /**
     * Inserts a single check
     *
     * Inserts a single draft from your customer's bank account to the default US bank account on file with your
     * merchant account for the specified amount/date.
     *
     * @param Payee $payee
     * @param PayeeBankInfo $payeeBankInfo
     * @param PayeeCheck $payeeCheck
     * @param bool $realtime
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function singleCheck(Payee $payee, PayeeBankInfo $payeeBankInfo, PayeeCheck $payeeCheck, $realtime = true)
    {
        return $this->client->request($realtime ? 'OneTimeDraftRTV' : 'OneTimeDraftBV',
            array_merge(
                $payee->toArray(),
                $payeeBankInfo->toArray(),
                $payeeCheck->toArray()
            ), [
                'Result',
                'ResultDescription',
                'VerifyResult',
                'VerifyResultDescription',
                'CheckNumber',
                'Check_ID',
            ]);
    }

    /**
     * Inserts a recurring check
     *
     * Inserts the first check in the series and then each time this series is processed, inserts a new check
     * for the specified RecurringType, Offset, and until it hits the number of RecurringPayments.
     * Ex. Once a month for 12 payments would be: $recur_type = "M", $recur_offset = "1", $recur_payments = "12"
     * Every other day for 10 payments would be: $recur_type = "D", $recur_offset = "2", $recur_payments = "10"
     *
     * @param Payee $payee
     * @param PayeeBankInfo $payeeBankInfo
     * @param PayeeCheck $payeeCheck
     * @param RecurringInfo $recurringInfo
     * @param bool $realtime
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function recurringCheck(Payee $payee, PayeeBankInfo $payeeBankInfo, PayeeCheck $payeeCheck,
                                   RecurringInfo $recurringInfo, $realtime = true)
    {
        return $this->client->request($realtime ? 'RecurringDraftRTV' : 'RecurringDraftBV',
            array_merge(
                $payee->toArray(),
                $payeeBankInfo->toArray(),
                $payeeCheck->toArray(),
                $recurringInfo->toArray()
            ),
            [
                'Result',
                'ResultDescription',
                'VerifyResult',
                'VerifyResultDescription',
                'CheckNumber',
                'Check_ID',
            ]);
    }

    /**
     * Enters a single check with check signature.
     *
     * Method enters checks only in Real Time Verification mode. Image data must be passed in
     * jpeg/jpg format through a base64 encoded string
     *
     * @param Payee $payee
     * @param PayeeBankInfo $payeeBankInfo
     * @param PayeeCheck $payeeCheck
     * @param $image
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function singleCheckWithSignature(Payee $payee, PayeeBankInfo $payeeBankInfo, PayeeCheck $payeeCheck, $image)
    {
        return $this->client->request('OneTimeDraftWithSignatureImage',
            array_merge(
                $payee->toArray(),
                $payeeBankInfo->toArray(),
                $payeeCheck->toArray(),
                ['ImageData' => $image]
            ), [
                'Result',
                'ResultDescription',
                'VerifyResult',
                'VerifyResultDescription',
                'CheckNumber',
                'Check_ID',
            ]
        );
    }

    /**
     * Enters a single check with check signature.
     *
     * Method enters checks only in Real Time Verification mode.
     * NOTE: Because this method requires a base64Binary type to be sent, we cannot use the POST request
     * function. This method must use a SOAP client to generate the request
     *
     * @param Payee $payee
     * @param PayeeBankInfo $payeeBankInfo
     * @param PayeeCheck $payeeCheck
     * @param RecurringInfo $recurringInfo
     * @param $image
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function recurringCheckWithSignature(Payee $payee, PayeeBankInfo $payeeBankInfo,
                                                PayeeCheck $payeeCheck, RecurringInfo $recurringInfo, $image)
    {
        return $this->client->request('RecurringDraftWithSignatureImage',
            array_merge(
                $payee->toArray(),
                $payeeBankInfo->toArray(),
                $payeeCheck->toArray(),
                $recurringInfo->toArray(),
                ['ImageData' => $image]
            ), [
                'Result',
                'ResultDescription',
                'VerifyResult',
                'VerifyResultDescription',
                'CheckNumber',
                'Check_ID',
            ]
        );
    }

    /**
     * Return the status results for a check that was previously input
     * Will return a status string that contains the results of eVerification, processing status, deletion/rejection
     * status and dates, and other relevant information
     *
     * @param string $checkId The numeric Check_ID of the previously entered check you want the status for
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function checkStatus($checkId)
    {
        return $this->client->request('CheckStatus',
            array_merge(
                ['Check_ID' => $checkId]
            ),
            [
                'Result',
                'ResultDescription',
                'VerifyResult',
                'VerifyResultDescription',
                'VerifyOverridden',
                'Deleted',
                'DeletedDate',
                'Processed',
                'ProcessedDate',
                'Rejected',
                'RejectedDate',
                'CheckNumber',
                'Check_ID',
            ]);
    }

    /**
     * Cancels a previously entered check
     *
     * This function allows you to cancel any previously entered check as long as it has NOT already been processed.
     * NOTE: For recurring checks, this function cancels the entire series of payments.
     *
     * @param string $checkId The numeric Check_ID of the previously entered check you want the status for
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function cancelCheck($checkId)
    {
        return $this->client->request('CancelCheck',
            array_merge(
                ['Check_ID' => $checkId]
            ), [
                'Result',
                'ResultDescription',
            ]);
    }

    /**
     * Issue a refund for a single check previously entered. Allows you to start the process of entering a refund.
     * On a successful result, the refund will be processed at the next batch and sent to the customer.
     *
     * @param string $checkId The numeric Check_ID of the previously entered check you want the refund for
     * @param string $refundMemo Memo to appear on the refund
     * @param string $refundAmount Refund amount in the format ##.##. Do not include monetary symbols
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function refundCheck($checkId, $refundMemo, $refundAmount)
    {
        return $this->client->request('RefundCheck',
            array_merge(
                [
                    'Check_ID' => $checkId,
                    'RefundMemo' => $refundMemo,
                    'RefundAmount' => $refundAmount,
                ]
            ), [
                'Result',
                'ResultDescription',
                'RefundCheckNumber',
                'RefundCheck_ID',
            ]);
    }

    /**
     * Insert a note for a previously entered check
     * Creates a check note for the check which can be viewed using the Check System Tracking pages in your Green Portal.
     *
     * @param string $checkId The numeric Check_ID of the previously entered check
     * @param string $note The actual note to enter, limit of 2000 characters
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function checkNote($checkId, $note)
    {
        if (strlen($note) > 2000) {
            $note = substr($note, 0, 2000);
        }
        return $this->client->request('CheckNote',
            array_merge(
                [
                    'Check_ID' => $checkId,
                    'Note' => $note,
                ]
            ), [
                'Result',
                'ResultDescription',
            ]);
    }

    /**
     * Upload a signature image for a previously entered check.
     *
     * Image data must be provided to the API as a jpeg/jpg file in the form of a base64 encoded string.
     * NOTE: Because this method requires a base64Binary type to be sent, we cannot use the POST request
     * function. This method must use a SOAP client to generate the request
     *
     * @param string $checkId The Check_ID for the previously entered check
     * @param string $image The jpeg data for a document with the client’s signature in base64Binary format
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function uploadCheckSignature($checkId, $image)
    {
        return $this->client->request(
            'UploadSignatureImage',
            ['Check_ID' => $checkId, 'ImageData' => $image,],
            ['Result', 'ResultDescription']
        );
    }

    /**
     * Allows you to request that the system flag a check so that no phone verification is done.
     *
     * @param $checkId
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function checkWithNoPhoneVerification($checkId)
    {
        return $this->client->request('CheckNoPhoneVerification', [
            'Check_ID' => $checkId,
        ], [
            'Result',
            'ResultDescription',
        ]);
    }

    /**
     * Return the verification status of a check that was previously input
     *
     * @param string $checkId The numeric Check_ID of the previously entered check you want the status for
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function verificationResult($checkId)
    {
        return $this->client->request('VerificationResult', [
            'Check_ID' => $checkId,
        ], [
            'Result',
            'ResultDescription',
            'VerifyResult',
            'VerifyResultDescription',
            'CheckNumber',
            'Check_ID',
        ]);
    }

    /**
     * Override the verification code of a check previously entered
     *
     * If a check gets returned by eVerification as Risky/Bad and has an overridable response code, this function allows you
     * to override the code and process the check at the next awaiting batch.
     *
     * @param string $checkId
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function overrideVerification($checkId)
    {
        return $this->client->request('VerificationOverride', [
            'Check_ID' => $checkId,
        ], [
            'Result',
            'ResultDescription',
            'VerifyResult',
            'VerifyResultDescription',
            'CheckNumber',
            'Check_ID',
        ]);
    }

    /**
     * Send a single payment from your bank account to another person or company.
     *
     * Most banks offer this feature already, however, if you'd like to integrate this into your system to handles
     * rebates, incentives, et. al this is the feature you need!
     *
     * @param Payee $payee
     * @param PayeeBankInfo $payeeBankInfo
     * @param PayeeCheck $payeeCheck
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function singleBillPay(Payee $payee, PayeeBankInfo $payeeBankInfo, PayeeCheck $payeeCheck)
    {
        return $this->client->request('BillPayCheck',
            array_merge(
                $payee->toArray(),
                $payeeBankInfo->toArray(),
                $payeeCheck->toArray()
            ), [
                'Result',
                'ResultDescription',
                'CheckNumber',
                'Check_ID',
            ]);
    }

    /**
     * Allows you to enter a single payment from your bank account TO another person or company.
     *
     * @param Payee $payee
     * @param PayeeCheck $payeeCheck
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function singleBillPayWithoutBank(Payee $payee, PayeeCheck $payeeCheck)
    {
        return $this->client->request('BillPayCheckNoBankInfo',
            array_merge(
                $payee->toArray(),
                $payeeCheck->toArray()
            ), [
                'Result',
                'ResultDescription',
                'CheckNumber',
                'Check_ID',
            ]);
    }

    /**
     * Enter a recurring payment from your bank account TO another person or companyName
     *
     * Enters a recurring bill pay check using similar methods to
     *
     * @param Payee $payee
     * @param PayeeBankInfo $payeeBankInfo
     * @param PayeeCheck $payeeCheck
     * @param RecurringInfo $recurringInfo
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function recurringBillPay(Payee $payee, PayeeBankInfo $payeeBankInfo, PayeeCheck $payeeCheck, RecurringInfo $recurringInfo)
    {
        return $this->client->request('RecurringBillPayCheck',
            array_merge(
                $payee->toArray(),
                $payeeBankInfo->toArray(),
                $payeeCheck->toArray(),
                $recurringInfo->toArray()
            ), [
                'Result',
                'ResultDescription',
                'CheckNumber',
                'Check_ID',
            ]);
    }

    /**
     * Allows you to enter a recurring billpay check that will be withdrawn for your merchant’s default bank account on file to another merchant,
     * customer, or company’s bank account. Most banks offer this feature already, however, if you would like to integrate this into your system
     * to handle rebates or incentives this is the feature you need
     *
     * @param Payee $payee
     * @param PayeeCheck $payeeCheck
     * @param RecurringInfo $recurringInfo
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function recurringBillPayWithNoBankInfo(Payee $payee, PayeeCheck $payeeCheck, RecurringInfo $recurringInfo)
    {
        return $this->client->request('RecurringBillPayCheckNoBankInfo',
            array_merge(
                $payee->toArray(),
                $payeeCheck->toArray(),
                $recurringInfo->toArray()
            ), [
                'Result',
                'ResultDescription',
                'CheckNumber',
                'Check_ID',
            ]);
    }

    /**
     * Enters a single invoice that sends the customer an invoice via email.
     *
     * @param string $payorName Name of person paying
     * @param string $email Email to be sent the invoice
     * @param string $itemName Name of the Item
     * @param string $itemDescription Description of the Item
     * @param string $amount Initial Amount
     * @param string $date Payment date
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function singleInvoice($payorName, $email, $itemName, $itemDescription, $amount, $date)
    {
        return $this->client->request('OneTimeInvoice', [
            'PayorName' => $payorName,
            'EmailAddress' => $email,
            'ItemName' => $itemName,
            'ItemDescription' => $itemDescription,
            'Amount' => $amount,
            'PaymentDate' => $date,
        ], [
            'Result',
            'ResultDescription',
            'PaymentResult',
            'PaymentResultDescription',
            'Invoice_ID',
            'Check_ID',
        ]);
    }

    /**
     * RecurringInvoice allows you to enter a single invoice that sends your customer an invoice via email for a recurring draft.
     *
     * RecurringInvoice shares similar inputs and the same outputs as OneTimeInvoice.
     *
     * @param string $payorName Name of person paying
     * @param string $email Email to be sent the invoice
     * @param string $itemName Name of the Item
     * @param string $itemDescription Description of the Item
     * @param string $amount Dollar amount on the invoice
     * @param string $date Payment date
     * @param RecurringInfo $recurringInfo
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function recurringInvoice($payorName, $email, $itemName, $itemDescription, $amount, $date, RecurringInfo $recurringInfo)
    {
        return $this->client->request('RecurringInvoice',
            array_merge(
                [
                    'PayorName' => $payorName,
                    'EmailAddress' => $email,
                    'ItemName' => $itemName,
                    'ItemDescription' => $itemDescription,
                    'Amount' => $amount,
                    'PaymentDate' => $date,
                ],
                $recurringInfo->toArray()
            ), [
                'Result',
                'ResultDescription',
                'PaymentResult',
                'PaymentResultDescription',
                'Invoice_ID',
                'Check_ID',
            ]);
    }

    /**
     * Inserts a combination invoice
     *
     * @param string $payorName Customer's Full Name on their checking account
     * @param string $email Customer's email address. If provided, will be notified with receipt of payment. If not provided, customer will be notified via US Mail at additional cost to your Green Account
     * @param string $itemName The name of the item on the invoice
     * @param string $itemDescription A full description of the item on the invoice
     * @param string $initialAmount The initial payment amount
     * @param string $initialDate The initial payment date of the invoice check
     * @param string $recurringAmount The amount of each check in the recurring series
     * @param string $recurringInitialDate The initial date of the first recurring check in the series
     * @param RecurringInfo $recurringInfo valid values are "M" for month, "W" for week, and "D" for day
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function combinationInvoice($payorName, $email, $itemName, $itemDescription, $initialAmount, $initialDate, $recurringAmount,
                                       $recurringInitialDate, RecurringInfo $recurringInfo)
    {
        return $this->client->request('CombinationInvoice',
            array_merge(
                [
                    'PayorName' => $payorName,
                    'EmailAddress' => $email,
                    'ItemName' => $itemName,
                    'ItemDescription' => $itemDescription,
                    'InitialAmount' => $initialAmount,
                    'InitialPaymentDate' => $initialDate,
                    'RecurringAmount' => $recurringAmount,
                    'RecurringPaymentDate' => $recurringInitialDate,
                ],
                $recurringInfo->toArray()
            ), ['Result', 'ResultDescription', 'PaymentResult', 'PaymentResultDescription', 'Invoice_ID', 'Check_ID',]
        );
    }

    /**
     * InvoiceStatus allows you to retrieve payment status on a previously entered invoice.
     *
     * @param string $invoiceId Number that identifies the invoice
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function invoiceStatus($invoiceId)
    {
        return $this->client->request('InvoiceStatus', [
            'Invoice_ID' => $invoiceId,
        ], [
            'Result',
            'ResultDescription',
            'PaymentResult',
            'PaymentResultDescription',
            'Invoice_ID',
            'Check_ID',
        ]);
    }

    /**
     * Allows the cancellation of an invoice if not already processed
     *
     * @param $invoiceId
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function cancelInvoice($invoiceId)
    {
        return $this->client->request('CancelInvoice', [
            'Invoice_ID' => $invoiceId,
        ], [
            'Result',
            'ResultDescription',
        ]);
    }

    /**
     * Resend the email asking for routing number, account number, and the invoice to be signed
     *
     * @param $invoiceId
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function resendInvoiceNotification($invoiceId)
    {
        return $this->client->request('ResendInvoiceNotification', [
            'Invoice_ID' => $invoiceId,
        ], [
            'Result',
            'ResultDescription',
        ]);
    }

    /**
     * Creates a payor in the Green system that holds all personally identifiable information along with
     * routing number and account number
     *
     * @param Customer $customer
     * @param CustomerBankInfo $customerBankInfo
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function createCustomer(Customer $customer, CustomerBankInfo $customerBankInfo)
    {
        return $this->client->request('CreatCustomer',
            array_merge(
                $customer->toArray(),
                $customerBankInfo->toArray()
            ), [
                'Result',
                'ResultDescription',
                'Payor_ID',
            ]
        );
    }

    /**
     * Edit a payor in the system that holds all personally identifiable information along with routing number and account number
     *
     * @param $payorId
     * @param Customer $customer
     * @param CustomerBankInfo $customerBankInfo
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function editCustomer($payorId, Customer $customer, CustomerBankInfo $customerBankInfo)
    {
        return $this->client->request('EditCustomer',
            array_merge(
                ['Payor_ID' => $payorId],
                $customer->toArray(),
                $customerBankInfo->toArray()
            ), [
                'Result',
                'ResultDescription',
                'Payor_ID',
            ]
        );
    }

    /**
     * Deletes a payor from the system and optionally deletes that payor’s pending checks in the system
     *
     * @param $payorId
     * @param bool $deletePendingChecks
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function deleteCustomer($payorId, $deletePendingChecks = true)
    {
        return $this->client->request('DeleteCustomer', [
            'Payor_ID' => $payorId,
            'DeletePendingChecks' => ($deletePendingChecks ? 'true' : 'false'),
        ], ['Result', 'ResultDescription', 'Payor_ID',]
        );
    }

    /**
     * Inserts a single draft from your customer's bank account to the default US bank account on file with
     * your merchant account for the specified amount/date.
     *
     * @param $payorId
     * @param CustomerCheck $customerCheck
     * @param bool $realtime
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function customerSingleCheck($payorId, CustomerCheck $customerCheck, $realtime = true)
    {
        return $this->client->request($realtime ? 'CustomerOneTimeDraftRTV' : 'CustomerOneTimeDraftBV',
            array_merge(
                [
                    'Payor_ID' => $payorId,
                ],
                $customerCheck->toArray()
            ), [
                'Result',
                'ResultDescription',
                'VerifyResult',
                'VerifyResultDescription',
                'CheckNumber',
                'Check_ID',
            ]
        );
    }

    /**
     * Inserts a recurring check for the customer
     *
     * Inserts the first check in the series and then each time this series is processed, inserts a new check
     * for the specified RecurringType, Offset, and until it hits the number of RecurringPayments.
     * Ex. Once a month for 12 payments would be: $recur_type = "M", $recur_offset = "1", $recur_payments = "12"
     * Every other day for 10 payments would be: $recur_type = "D", $recur_offset = "2", $recur_payments = "10"
     *
     * @param $payorId
     * @param CustomerCheck $customerCheck
     * @param RecurringInfo $recurringInfo
     * @param bool $realtime
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function customerRecurringCheck($payorId, CustomerCheck $customerCheck, RecurringInfo $recurringInfo, $realtime = true)
    {
        return $this->client->request($realtime ? 'CustomerRecurringDraftRTV' : 'CustomerRecurringDraftBV',
            array_merge(
                [
                    'Payor_ID' => $payorId,
                ],
                $customerCheck->toArray(),
                $recurringInfo->toArray()
            ), [
                'Result',
                'ResultDescription',
                'VerifyResult',
                'VerifyResultDescription',
                'CheckNumber',
                'Check_ID',
            ]
        );
    }

    /**
     * Enter a single invoice that sends the customer an invoice via email that they can use to pay you
     * through Green Payments.
     *
     * @param $payorId
     * @param CustomerCheck $customerCheck
     * @param $itemName
     * @param $itemDescription
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function customerSingleInvoice($payorId, CustomerCheck $customerCheck, $itemName, $itemDescription)
    {
        return $this->client->request('CustomerOneTimeInvoice',
            array_merge(
                [
                    'Payor_ID' => $payorId,
                    'ItemName' => $itemName,
                    'ItemDescription' => $itemDescription,
                ],
                $customerCheck->toArray()
            ), [
                'Result',
                'ResultDescription',
                'PaymentResult',
                'PaymentResultDescription',
                'Invoice_ID',
                'Check_ID',
            ]);
    }

    /**
     * Enter a single invoice that sends the customer an invoice via email for a recurring draft.
     * CustomerRecurringInvoice shares similar inputs and the same outputs as CustomerOneTimeInvoice
     *
     * @param $payorId
     * @param CustomerCheck $customerCheck
     * @param RecurringInfo $recurringInfo
     * @param $itemName
     * @param $itemDescription
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function customerRecurringInvoice($payorId, CustomerCheck $customerCheck, RecurringInfo $recurringInfo, $itemName, $itemDescription)
    {
        return $this->client->request('CustomerRecurringInvoice',
            array_merge(
                [
                    'Payor_ID' => $payorId,
                    'ItemName' => $itemName,
                    'ItemDescription' => $itemDescription,
                ],
                $recurringInfo->toArray(),
                $customerCheck->toArray()
            ), [
                'Result',
                'ResultDescription',
                'PaymentResult',
                'PaymentResultDescription',
                'Invoice_ID',
                'Check_ID',
            ]);
    }

    /**
     * Allows you to enter a single bill pay check that will be withdrawn for your merchant’s default bank account on file to
     * another merchant, customer, or company’s bank account. Most banks offer this feature already, however, if you would like
     * to integrate this into your system to handle rebates or incentives this is the feature you need
     *
     * @param $payorId
     * @param CustomerCheck $customerCheck
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function customerSingleBillPay($payorId, CustomerCheck $customerCheck)
    {
        return $this->client->request('CustomerOneTimeBillpay',
            array_merge(
                [
                    'Payor_ID' => $payorId,
                ],
                $customerCheck->toArray()
            ), [
                'Result',
                'ResultDescription',
                'CheckNumber',
                'Check_ID',
            ]);
    }

    /**
     * Allows you to enter a recurring bill pay check that will be withdrawn for your merchant’s default bank account on file
     * to another merchant, customer, or company’s bank account. Most banks offer this feature already, however,
     * if you would like to integrate this into your system to handle rebates or incentives this is the feature you need
     *
     * @param $payorId
     * @param CustomerCheck $customerCheck
     * @param RecurringInfo $recurringInfo
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function customerRecurringBillPay($payorId, CustomerCheck $customerCheck, RecurringInfo $recurringInfo)
    {
        return $this->client->request('CustomerRecurringBillpay',
            array_merge(
                [
                    'Payor_ID' => $payorId,
                ],
                $customerCheck->toArray(),
                $recurringInfo->toArray()
            ), [
                'Result',
                'ResultDescription',
                'CheckNumber',
                'Check_ID',
            ]);
    }

    /**
     * Retrieve full customer information on a single Payor using the unique Payor Id
     *
     * @param $payorId
     *
     * @return array Returns associative array
     *
     * @throws Exception
     */
    public function getCustomer($payorId)
    {
        return $this->client->request('CustomerRecurringBillpay',
            array_merge(
                [
                    'Payor_ID' => $payorId,
                ]
            ), [
                'Result', 'ResultDescription', 'Payor_ID', 'Client_ID', 'NickName',
                'PhoneWork', 'PhoneWorkExtension', 'EmailAddress', 'MerchantAccountNumber',
                'NameFirst', 'NameLast', 'BankAccountCompanyName', 'BankAccountAddress1',
                'BankAccountAddress2', 'BankAccountCity', 'BankAccountState', 'BankAccountZip',
                'BankAccountCountry', 'DefaultMemo', 'BankName', 'RoutingNumber', 'AccountNumber',
            ]);
    }
}
