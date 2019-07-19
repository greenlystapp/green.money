<?php


namespace Greenlyst\GreenMoney;


use Greenlyst\GreenMoney\Models\BankInfo;
use Greenlyst\GreenMoney\Models\CheckInfo;
use Greenlyst\GreenMoney\Models\CustomerInfo;
use Greenlyst\GreenMoney\Models\RecurringObject;
use Greenlyst\GreenMoney\Util\Request;
use SoapFault;

class ECheck
{
    /**
     * @var Request $client
     */
    private $client;
    /**
     * @var bool
     */
    private $delimitData;
    /**
     * @var string
     */
    private $delimitCharacter;

    /**
     * ECheck constructor.
     * @param string $clientId
     * @param string $apiPassword
     * @param bool $live
     * @param bool $delimitData
     * @param string $delimitCharacter
     */
    public function __construct($clientId, $apiPassword, $live = true, $delimitData = false, $delimitCharacter = ",")
    {
        $this->client = new Request(Request::TYPE_CHECK, $clientId, $apiPassword, $live);
        $this->delimitData = $delimitData;
        $this->delimitCharacter = $delimitCharacter;
    }

    /**
     * Inserts a single check
     *
     * Inserts a single draft from your customer's bank account to the default US bank account on file with your merchant account for the specified amount/date.
     *
     * @param CustomerInfo $customerInfo
     * @param BankInfo $bankInfo
     * @param CheckInfo $checkInfo
     * @param bool $realtime
     * @return array|bool|string
     */
    public function singleCheck(CustomerInfo $customerInfo, BankInfo $bankInfo, CheckInfo $checkInfo, $realtime = true)
    {
        $method = 'OneTimeDraftBV';
        if ($realtime) {
            $method = 'OneTimeDraftRTV';
        }

        return $this->client->request($method, [
            'Name' => $customerInfo->getName(),
            'EmailAddress' => $customerInfo->getEmailAddress(),
            'Address1' => $customerInfo->getAddress1(),
            'Address2' => $customerInfo->getAddress2(),
            'Phone' => $customerInfo->getPhone(),
            'PhoneExtension' => $customerInfo->getPhoneExtension(),
            'City' => $customerInfo->getCity(),
            'State' => $customerInfo->getState(),
            'Zip' => $customerInfo->getZip(),
            'Country' => $customerInfo->getCountry(),
            'RoutingNumber' => $bankInfo->getRoutingNumber(),
            'AccountNumber' => $bankInfo->getAccountNumber(),
            'BankName' => $bankInfo->getBankName(),
            'CheckMemo' => $checkInfo->getCheckMemo(),
            'CheckAmount' => $checkInfo->getCheckAmount(),
            'CheckDate' => $checkInfo->getCheckDate(),
            'CheckNumber' => $checkInfo->getCheckNumber(),
            'x_delim_data' => $this->delimitData ? 'TRUE' : '',
            'x_delim_char' => $this->delimitCharacter
        ], [
            'Result',
            'ResultDescription',
            'VerifyResult',
            'VerifyResultDescription',
            'CheckNumber',
            'Check_ID'
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
     * @param CustomerInfo $customerInfo
     * @param BankInfo $bankInfo
     * @param CheckInfo $checkInfo
     * @param RecurringObject $recurringObject
     * @param bool $realtime
     * @return array|bool|string
     */
    public function recurringCheck(CustomerInfo $customerInfo, BankInfo $bankInfo, CheckInfo $checkInfo, RecurringObject $recurringObject, $realtime = TRUE)
    {
        $method = 'RecurringDraftBV';
        if ($realtime) {
            $method = 'RecurringDraftRTV';
        }
        return $this->client->request($method, [
            'Name' => $customerInfo->getName(),
            'EmailAddress' => $customerInfo->getEmailAddress(),
            'Address1' => $customerInfo->getAddress1(),
            'Address2' => $customerInfo->getAddress2(),
            'Phone' => $customerInfo->getPhone(),
            'PhoneExtension' => $customerInfo->getPhoneExtension(),
            'City' => $customerInfo->getCity(),
            'State' => $customerInfo->getState(),
            'Zip' => $customerInfo->getZip(),
            'Country' => $customerInfo->getCountry(),
            'RoutingNumber' => $bankInfo->getRoutingNumber(),
            'AccountNumber' => $bankInfo->getAccountNumber(),
            'BankName' => $bankInfo->getBankName(),
            'CheckMemo' => $checkInfo->getCheckMemo(),
            'CheckAmount' => $checkInfo->getCheckAmount(),
            'CheckDate' => $checkInfo->getCheckDate(),
            'CheckNumber' => $checkInfo->getCheckNumber(),
            'RecurringType' => $recurringObject->getRecurringType(),
            'RecurringOffset' => $recurringObject->getRecurringOffset(),
            'RecurringPayments' => $recurringObject->getRecurringPayments(),
            'x_delim_data' => $this->delimitData ? 'TRUE' : '',
            'x_delim_char' => $this->delimitCharacter
        ], [
            'Result',
            'ResultDescription',
            'VerifyResult',
            'VerifyResultDescription',
            'CheckNumber',
            'Check_ID'
        ]);
    }

    /**
     * Enters a single check with check signature.
     *
     * Method enters checks only in Real Time Verification mode. Image data must be passed in
     * jpeg/jpg format through a base64 encoded string
     *
     * @param CustomerInfo $customerInfo
     * @param BankInfo $bankInfo
     * @param CheckInfo $checkInfo
     * @param $image
     * @return mixed
     * @throws SoapFault
     */
    public function singleCheckWithSignature(CustomerInfo $customerInfo, BankInfo $bankInfo, CheckInfo $checkInfo, $image)
    {
        return $this->client->requestSOAP('OneTimeDraftWithSignatureImage', [
            'Name' => $customerInfo->getName(),
            'EmailAddress' => $customerInfo->getEmailAddress(),
            'Address1' => $customerInfo->getAddress1(),
            'Address2' => $customerInfo->getAddress2(),
            'Phone' => $customerInfo->getPhone(),
            'PhoneExtension' => $customerInfo->getPhoneExtension(),
            'City' => $customerInfo->getCity(),
            'State' => $customerInfo->getState(),
            'Zip' => $customerInfo->getZip(),
            'Country' => $customerInfo->getCountry(),
            'RoutingNumber' => $bankInfo->getRoutingNumber(),
            'AccountNumber' => $bankInfo->getAccountNumber(),
            'BankName' => $bankInfo->getBankName(),
            'CheckMemo' => $checkInfo->getCheckMemo(),
            'CheckAmount' => $checkInfo->getCheckAmount(),
            'CheckDate' => $checkInfo->getCheckDate(),
            'CheckNumber' => $checkInfo->getCheckNumber(),
            'ImageData' => $image,
            'x_delim_data' => $this->delimitData ? 'TRUE' : '',
            'x_delim_char' => $this->delimitCharacter
        ]);
    }

    /**
     * Enters a single check with check signature.
     *
     * Method enters checks only in Real Time Verification mode.
     * NOTE: Because this method requires a base64Binary type to be sent, we cannot use the POST request
     * function. This method must use a SOAP client to generate the request
     *
     * @param CustomerInfo $customerInfo
     * @param BankInfo $bankInfo
     * @param CheckInfo $checkInfo
     * @param RecurringObject $recurringObject
     * @return array|bool|string
     * @throws SoapFault
     */
    public function recurringCheckWithSignature(CustomerInfo $customerInfo, BankInfo $bankInfo, CheckInfo $checkInfo, RecurringObject $recurringObject)
    {
        return $this->client->requestSOAP('RecurringDraftWithSignatureImage', [
            'Name' => $customerInfo->getName(),
            'EmailAddress' => $customerInfo->getEmailAddress(),
            'Address1' => $customerInfo->getAddress1(),
            'Address2' => $customerInfo->getAddress2(),
            'Phone' => $customerInfo->getPhone(),
            'PhoneExtension' => $customerInfo->getPhoneExtension(),
            'City' => $customerInfo->getCity(),
            'State' => $customerInfo->getState(),
            'Zip' => $customerInfo->getZip(),
            'Country' => $customerInfo->getCountry(),
            'RoutingNumber' => $bankInfo->getRoutingNumber(),
            'AccountNumber' => $bankInfo->getAccountNumber(),
            'BankName' => $bankInfo->getBankName(),
            'CheckMemo' => $checkInfo->getCheckMemo(),
            'CheckAmount' => $checkInfo->getCheckAmount(),
            'CheckDate' => $checkInfo->getCheckDate(),
            'CheckNumber' => $checkInfo->getCheckNumber(),
            'RecurringType' => $recurringObject->getRecurringType(),
            'RecurringOffset' => $recurringObject->getRecurringOffset(),
            'RecurringPayments' => $recurringObject->getRecurringPayments(),
            'x_delim_data' => $this->delimitData ? 'TRUE' : '',
            'x_delim_char' => $this->delimitCharacter
        ]);
    }

    /**
     * Return the status results for a check that was previously input
     *
     * Will return a status string that contains the results of eVerification, processing status, deletion/rejection status and dates, and other relevant information
     *
     * @param string $checkId The numeric Check_ID of the previously entered check you want the status for
     * @return array|bool|string Returns associative array or delimited string on success OR cURL error string on failure
     */
    public function checkStatus($checkId)
    {
        return $this->client->request("CheckStatus", [
            'Check_ID' => $checkId,
            'x_delim_data' => $this->delimitData ? 'TRUE' : '',
            'x_delim_char' => $this->delimitCharacter
        ], [
            "Result",
            "ResultDescription",
            "VerifyResult",
            "VerifyResultDescription",
            "VerifyOverridden",
            "Deleted",
            "DeletedDate",
            "Processed",
            "ProcessedDate",
            "Rejected",
            "RejectedDate",
            "CheckNumber",
            "Check_ID"
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
     * @return mixed                  Returns associative array or delimited string on success OR cURL error string on failure
     */
    public function cancelCheck($checkId)
    {
        return $this->client->request('CancelCheck', [
            'Check_ID' => $checkId,
            'x_delim_data' => $this->delimitData ? 'TRUE' : '',
            'x_delim_char' => $this->delimitCharacter
        ], [
            "Result",
            "ResultDescription"
        ]);
    }

    /**
     * Issue a refund for a single check previously entered
     *
     * Allows you to start the process of entering a refund. On a successful result, the refund will be processed at the next batch and sent to the customer.
     *
     * @param string $checkId The numeric Check_ID of the previously entered check you want the refund for
     * @param string $refundMemo Memo to appear on the refund
     * @param string $refundAmount Refund amount in the format ##.##. Do not include monetary symbols
     *
     * @return mixed Returns associative array or delimited string on success OR cURL error string on failure
     */
    public function refundCheck($checkId, $refundMemo, $refundAmount)
    {
        return $this->client->request('RefundCheck', [
            'Check_ID' => $checkId,
            'RefundMemo' => $refundMemo,
            'RefundAmount' => $refundAmount,
            'x_delim_data' => $this->delimitData ? 'TRUE' : '',
            'x_delim_char' => $this->delimitCharacter
        ], [
            'Result',
            'ResultDescription',
            'RefundCheckNumber',
            'RefundCheck_ID'
        ]);
    }

    /**
     * Insert a note for a previously entered check
     *
     * Creates a check note for the check which can be viewed using the Check System Tracking pages in your Green Portal.
     *
     * @param string $checkId The numeric Check_ID of the previously entered check
     * @param string $note The actual note to enter, limit of 2000 characters
     *
     * @return mixed                  Returns associative array or delimited string on success OR cURL error string on failure
     */
    public function checkNote($checkId, $note)
    {
        if (strlen($note) > 2000) {
            $note = substr($note, 0, 2000);
        }
        return $this->client->request('CheckNote', [
            'Check_ID' => $checkId,
            'Note' => $note,
            'x_delim_data' => $this->delimitData ? 'TRUE' : '',
            'x_delim_char' => $this->delimitCharacter
        ], [
            'Result',
            'ResultDescription'
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
     * @return mixed               Returns associative array or delimited string on success OR cURL error string on failure
     * @throws SoapFault
     */
    public function uploadCheckSignature($checkId, $image)
    {
        return $this->client->requestSOAP('UploadSignatureImage', [
            'Check_ID' => $checkId,
            'ImageData' => $image,
            'x_delim_data' => $this->delimitData ? 'TRUE' : '',
            'x_delim_char' => $this->delimitCharacter
        ]);
    }

    /**
     * Return the verification status of a check that was previously input
     *
     * @param string $checkId The numeric Check_ID of the previously entered check you want the status for
     *
     * @return mixed Returns associative array or delimited string on success OR cURL error string on failure
     *
     * @see self::checkStatus but returns only the result of verification
     */
    public function verificationResult($checkId)
    {
        return $this->client->request('VerificationResult', [
            'Check_ID' => $checkId,
            'x_delim_data' => $this->delimitData ? 'TRUE' : '',
            'x_delim_char' => $this->delimitCharacter
        ], [
            'Result',
            'ResultDescription',
            'VerifyResult',
            'VerifyResultDescription',
            'CheckNumber',
            'Check_ID'
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
     * @return mixed                  Returns associative array or delimited string on success OR cURL error string on failure
     */
    public function overrideVerification($checkId)
    {
        return $this->client->request('VerificationOverride', [
            'Check_ID' => $checkId,
            'x_delim_data' => $this->delimitData ? 'TRUE' : '',
            'x_delim_char' => $this->delimitCharacter
        ], [
            'Result',
            'ResultDescription',
            'VerifyResult',
            'VerifyResultDescription',
            'CheckNumber',
            'Check_ID'
        ]);
    }

    /**
     * Send a single payment from your bank account to another person or company.
     *
     * Most banks offer this feature already, however, if you'd like to integrate this into your system to handles
     * rebates, incentives, et. al this is the feature you need!
     *
     * @param CustomerInfo $customerInfo
     * @param BankInfo $bankInfo
     * @param CheckInfo $checkInfo
     * @return mixed  Returns associative array or delimited string on success OR cURL error string on failure
     */
    public function singleBillPay(CustomerInfo $customerInfo, BankInfo $bankInfo, CheckInfo $checkInfo)
    {
        return $this->client->request("BillPayCheck", [
            'Name' => $customerInfo->getName(),
            'EmailAddress' => $customerInfo->getEmailAddress(),
            'Address1' => $customerInfo->getAddress1(),
            'Address2' => $customerInfo->getAddress2(),
            'Phone' => $customerInfo->getPhone(),
            'PhoneExtension' => $customerInfo->getPhoneExtension(),
            'City' => $customerInfo->getCity(),
            'State' => $customerInfo->getState(),
            'Zip' => $customerInfo->getZip(),
            'Country' => $customerInfo->getCountry(),
            'RoutingNumber' => $bankInfo->getRoutingNumber(),
            'AccountNumber' => $bankInfo->getAccountNumber(),
            'BankName' => $bankInfo->getBankName(),
            'CheckMemo' => $checkInfo->getCheckMemo(),
            'CheckAmount' => $checkInfo->getCheckAmount(),
            'CheckDate' => $checkInfo->getCheckDate(),
            'CheckNumber' => $checkInfo->getCheckNumber(),
            'x_delim_data' => $this->delimitData ? 'TRUE' : '',
            'x_delim_char' => $this->delimitCharacter
        ], [
            'Result',
            'ResultDescription',
            'CheckNumber',
            'Check_ID'
        ]);
    }

    /**
     * Allows you to enter a single payment from your bank account TO another person or company.
     *
     * Like
     * @param CustomerInfo $customerInfo
     * @param CheckInfo $checkInfo
     * @return array|bool|string  Returns associative array or delimited string on success OR cURL error string on failure @see CheckGateway::singleBillpay but requires no bank information.
     * Since we don't have the bank info, we cannot deposit these checks directly
     */
    public function singleBillPayWithoutBank(CustomerInfo $customerInfo, CheckInfo $checkInfo)
    {
        return $this->client->request("BillPayCheckNoBankInfo", [
            'Name' => $customerInfo->getName(),
            'EmailAddress' => $customerInfo->getEmailAddress(),
            'Address1' => $customerInfo->getAddress1(),
            'Address2' => $customerInfo->getAddress2(),
            'Phone' => $customerInfo->getPhone(),
            'PhoneExtension' => $customerInfo->getPhoneExtension(),
            'City' => $customerInfo->getCity(),
            'State' => $customerInfo->getState(),
            'Zip' => $customerInfo->getZip(),
            'Country' => $customerInfo->getCountry(),
            'CheckMemo' => $checkInfo->getCheckMemo(),
            'CheckAmount' => $checkInfo->getCheckAmount(),
            'CheckDate' => $checkInfo->getCheckDate(),
            'CheckNumber' => $checkInfo->getCheckNumber(),
            'x_delim_data' => $this->delimitData ? 'TRUE' : '',
            'x_delim_char' => $this->delimitCharacter
        ], [
            'Result',
            'ResultDescription',
            'CheckNumber',
            'Check_ID'
        ]);
    }

    /**
     * Enter a recurring payment from your bank account TO another person or companyName
     *
     * Enters a recurring bill pay check using similar methods to
     *
     * @param CustomerInfo $customerInfo
     * @param BankInfo $bankInfo
     * @param CheckInfo $checkInfo
     * @param RecurringObject $recurringObject
     *
     * @return mixed  Returns associative array or delimited string on success OR cURL error string on failure @see CheckGateway::singleBillpay combined with @see CheckGateway::recurringCheck
     */
    public function recurringBillPay(CustomerInfo $customerInfo, BankInfo $bankInfo, CheckInfo $checkInfo, RecurringObject $recurringObject)
    {
        return $this->client->request("RecurringBillPayCheck", [
            'Name' => $customerInfo->getName(),
            'EmailAddress' => $customerInfo->getEmailAddress(),
            'Address1' => $customerInfo->getAddress1(),
            'Address2' => $customerInfo->getAddress2(),
            'Phone' => $customerInfo->getPhone(),
            'PhoneExtension' => $customerInfo->getPhoneExtension(),
            'City' => $customerInfo->getCity(),
            'State' => $customerInfo->getState(),
            'Zip' => $customerInfo->getZip(),
            'Country' => $customerInfo->getCountry(),
            'RoutingNumber' => $bankInfo->getRoutingNumber(),
            'AccountNumber' => $bankInfo->getAccountNumber(),
            'BankName' => $bankInfo->getBankName(),
            'CheckMemo' => $checkInfo->getCheckMemo(),
            'CheckAmount' => $checkInfo->getCheckAmount(),
            'CheckDate' => $checkInfo->getCheckDate(),
            'CheckNumber' => $checkInfo->getCheckNumber(),
            'RecurringType' => $recurringObject->getRecurringType(),
            'RecurringOffset' => $recurringObject->getRecurringOffset(),
            'RecurringPayments' => $recurringObject->getRecurringPayments(),
            'x_delim_data' => $this->delimitData ? 'TRUE' : '',
            'x_delim_char' => $this->delimitCharacter
        ], [
            'Result',
            'ResultDescription',
            'CheckNumber',
            'Check_ID'
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
     * @return mixed                     Returns associative array or delimited string on success OR cURL error string on failure
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
            'x_delim_data' => $this->delimitData ? 'TRUE' : '',
            'x_delim_char' => $this->delimitCharacter
        ], [
            'Result',
            'ResultDescription',
            'PaymentResult',
            'PaymentResultDescription',
            'Invoice_ID',
            'Check_ID'
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
     * @param RecurringObject $recurringObject
     *
     * @return mixed                     Returns associative array or delimited string on success OR cURL error string on failure
     */
    public function recurringInvoice($payorName, $email, $itemName, $itemDescription, $amount, $date, RecurringObject $recurringObject)
    {
        return $this->client->request('RecurringInvoice', [
            'PayorName' => $payorName,
            'EmailAddress' => $email,
            'ItemName' => $itemName,
            'ItemDescription' => $itemDescription,
            'Amount' => $amount,
            'PaymentDate' => $date,
            'RecurringType' => $recurringObject->getRecurringType(),
            'RecurringOffset' => $recurringObject->getRecurringOffset(),
            'RecurringPayments' => $recurringObject->getRecurringPayments(),
            'x_delim_data' => $this->delimitData ? 'TRUE' : '',
            'x_delim_char' => $this->delimitCharacter
        ], [
            'Result',
            'ResultDescription',
            'PaymentResult',
            'PaymentResultDescription',
            'Invoice_ID',
            'Check_ID'
        ]);
    }

    /**
     * Inserts a combination invoice
     *
     * Shares inputs with
     * @param string $payorName Customer's Full Name on their checking account
     * @param string $email Customer's email address. If provided, will be notified with receipt of payment. If not provided, customer will be notified via US Mail at additional cost to your Green Account
     * @param string $itemName The name of the item on the invoice
     * @param string $itemDescription A full description of the item on the invoice
     * @param string $initialAmount The initial payment amount
     * @param string $initialDate The initial payment date of the invoice check
     * @param string $recurringAmount The amount of each check in the recurring series
     * @param string $recurringInitialDate The initial date of the first recurring check in the series
     * @param RecurringObject $recurringObject valid values are "M" for month, "W" for week, and "D" for day
     *
     * @return mixed                    Returns associative array or delimited string on success OR cURL error string on failure
     * @see CheckGateway::recurringInvoice(). Function enters an invoice that sends your
     * customer an invoice via email for a down payment and a recurring draft.
     *
     */
    public function combinationInvoice($payorName, $email, $itemName, $itemDescription, $initialAmount, $initialDate, $recurringAmount, $recurringInitialDate, RecurringObject $recurringObject)
    {
        return $this->client->request('CombinationInvoice', [
            'PayorName' => $payorName,
            'EmailAddress' => $email,
            'ItemName' => $itemName,
            'ItemDescription' => $itemDescription,
            'InitialAmount' => $initialAmount,
            'InitialPaymentDate' => $initialDate,
            'RecurringAmount' => $recurringAmount,
            'RecurringPaymentDate' => $recurringInitialDate,
            'RecurringType' => $recurringObject->getRecurringType(),
            'RecurringOffset' => $recurringObject->getRecurringOffset(),
            'RecurringPayments' => $recurringObject->getRecurringPayments(),
            'x_delim_data' => $this->delimitData ? 'TRUE' : '',
            'x_delim_char' => $this->delimitCharacter
        ], [
            'Result',
            'ResultDescription',
            'PaymentResult',
            'PaymentResultDescription',
            'Invoice_ID',
            'Check_ID'
        ]);
    }

    /**
     * InvoiceStatus allows you to retrieve payment status on a previously entered invoice.
     *
     * @param string $invoiceId Number that identifies the invoice
     *
     * @return mixed    Returns associative array or delimited string on success OR cURL error string on failure
     */
    public function invoiceStatus($invoiceId)
    {
        return $this->client->request('InvoiceStatus', [
            'Invoice_ID' => $invoiceId,
            'x_delim_data' => $this->delimitData ? 'TRUE' : '',
            'x_delim_char' => $this->delimitCharacter
        ], [
            'Result',
            'ResultDescription',
            'PaymentResult',
            'PaymentResultDescription',
            'Invoice_ID',
            'Check_ID'
        ]);
    }
}