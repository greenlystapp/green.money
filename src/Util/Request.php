<?php


namespace Greenlyst\GreenMoney\Util;


use Exception;
use SoapClient;
use SoapFault;

class Request
{
    /**
     * @var string
     */
    private $clientId;
    /**
     * @var string
     */
    private $apiPassword;
    /**
     * @var bool
     */
    private $live;
    /**
     * @var string
     */
    private $endpoint;
    /**
     * @var string
     */
    private $lastError;


    public const TYPE_CHECK = "E-CHECK";

    public const TYPE_NOTIFICATION = "NOTIFICATION";

    public const TYPE_REPORT = "REPORT";

    private const API_E_CHECK_PRODUCTION_URL = "https://greenbyphone.com/echeck.asmx";

    private const API_E_CHECK_SANDBOX_URL = "https://cpsandbox.com/echeck.asmx";

    private const API_REPORT_PRODUCTION_URL = "https://greenbyphone.com/report.asmx";

    private const API_REPORT_SANDBOX_URL = "https://cpsandbox.com/report.asmx";

    private const API_NOTIFICATION_PRODUCTION_URL = "https://greenbyphone.com/enotification.asmx";

    private const API_NOTIFICATION_SANDBOX_URL = "https://cpsandbox.com/enotification.asmx";

    /**
     * Gateway constructor.
     *
     * @param string $type
     * @param string $clientId
     * @param string $apiPassword
     * @param bool $live
     */
    public function __construct(string $type, string $clientId, string $apiPassword, bool $live = true)
    {
        $this->clientId = $clientId;
        $this->apiPassword = $apiPassword;
        $this->live = $live;
        $this->assignEndpointByType($type);
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @param string $type
     */
    private function assignEndpointByType(string $type): void
    {
        switch ($type) {
            case self::TYPE_CHECK:
                if ($this->live) {
                    $this->endpoint = self::API_E_CHECK_PRODUCTION_URL;
                } else {
                    $this->endpoint = self::API_E_CHECK_SANDBOX_URL;
                }
                break;

            case self::TYPE_NOTIFICATION:
                if ($this->live) {
                    $this->endpoint = self::API_NOTIFICATION_PRODUCTION_URL;
                } else {
                    $this->endpoint = self::API_NOTIFICATION_SANDBOX_URL;
                }
                break;

            case self::TYPE_REPORT:
                if ($this->live) {
                    $this->endpoint = self::API_REPORT_PRODUCTION_URL;
                } else {
                    $this->endpoint = self::API_REPORT_SANDBOX_URL;
                }
                break;
        }
    }

    /**
     * @return string
     */
    public function getLastError(): string
    {
        return $this->lastError;
    }

    /**
     * @param string $lastError
     */
    public function assignLastError(string $lastError): void
    {
        $this->lastError = $lastError;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getApiPassword()
    {
        return $this->apiPassword;
    }

    /**
     * A default method used to generate API Calls
     *
     * This method is used internally by all other methods to generate API calls easily. This method can be used externally to create a request to any API method available if we haven't created a simple method for it in the class
     *
     * @param string $method The name of the API method to call at the endpoint (ex. OneTimeDraftRTV, CheckStatus, etc.)
     * @param array $options An array of "APIFieldName" => "Value" pairs. Must include the Client_ID and ApiPassword variable
     * @param array $resultArray
     *
     * @return array|bool|string
     */
    public function request(string $method, array $options, $resultArray = array())
    {
        if (!isset($options['Client_ID'])) {
            $options["Client_ID"] = $this->getClientID();
        }
        if (!isset($options['ApiPassword'])) {
            $options['ApiPassword'] = $this->getApiPassword();
        }
        //Test whether they want the delimited return or not to start with
        $returnDelimiter = ($options['x_delim_data'] === "TRUE");
        //Now let's actually set delim to TRUE because we always want to get a delimited string back from the API so we can parse it
        $options["x_delim_data"] = "TRUE";
        try {
            $curlObj = curl_init();
            if ($curlObj === FALSE) {
                throw new Exception('Failed to initialize cURL');
            }
            curl_setopt($curlObj, CURLOPT_URL, $this->getEndpoint() . '/' . $method);
            curl_setopt($curlObj, CURLOPT_POST, 1);
            curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curlObj, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($curlObj, CURLOPT_POSTFIELDS, http_build_query($options));
            $response = curl_exec($curlObj);
            if ($response === FALSE) {
                throw new Exception(curl_error($curlObj), curl_errno($curlObj));
            }
            curl_close($curlObj);
        } catch (Exception $e) {
            $this->assignLastError(sprintf('Curl failed with error #%d: %s', $e->getCode(), $e->getMessage()));
            return false;
        }
        try {
            if ($returnDelimiter) {
                return $response;
            } else {
                return $this->resultToArray($response, $options['x_delim_char'], $resultArray);
            }
        } catch (Exception $exception) {
            $this->assignLastError("An error occurred while attempting to parse the API result: " . $exception->getMessage());
            return false;
        }
    }

    /**
     * Function takes result string from API and parses into PHP associative Array
     *
     * If a return is specified to be returned as delimited, it will return the string. Otherwise, this function will be called to
     * return the result as an associative array in the format specified by the API documentation.
     *
     * @param string $result The result string as returned by cURL
     * @param string $delim_char The character used to delimit the string in cURL
     * @param array $keys An array containing the key names for the result variable as specified by the API docs
     *
     * @return array Associative array of key=>values pair described by the API docs as the return for the called method
     */
    private function resultToArray(string $result, string $delim_char, array $keys): array
    {
        $split = explode($delim_char, $result);
        $resultArray = array();
        foreach ($keys as $key => $keyName) {
            $resultArray[$keyName] = $split[$key];
        }
        return $resultArray;
    }

    /**
     * @param string $method
     * @param array $options
     *
     * @return array|bool|string
     *
     * @throws SoapFault
     */
    public function requestSOAP(string $method, array $options)
    {
        if (!isset($options['Client_ID'])) {
            $options["Client_ID"] = $this->getClientID();
        }
        if (!isset($options['ApiPassword'])) {
            $options['ApiPassword'] = $this->getApiPassword();
        }
        //Test whether they want the delimited return or not to start with
        $returnDelim = ($options['x_delim_data'] === "TRUE");
        //Now let's actually set delim to FALSE because calling by SOAP requires we get a response in XML
        $options["x_delim_data"] = "";
        $client = new SoapClient($this->getEndpoint() . "?wsdl", array("trace" => 1));
        try {
            $result = $client->__soapCall($method, array($options));
            $resultArray = (array)$result;
            $resultInnerArray = (array)reset($resultArray); //cheat to return the first element in the array without needing the key for it
            if ($returnDelim) {
                //We need to take it's arguments and turn them into a delimited string
                return implode($options['x_delim_char'], array_values($resultInnerArray));
            } else {
                //Return it as an array
                return $resultInnerArray;
            }
        } catch (Exception $exception) {
            $this->assignLastError(sprintf('SOAP Request failed with error #%d: %s <br/> %s <br/> %s', $exception->getCode(), $exception->getMessage(), $client->__getLastRequest(), $client->__getLastResponse()));
            return false;
        }
    }
}