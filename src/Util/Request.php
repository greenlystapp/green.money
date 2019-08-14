<?php

declare(strict_types=1);

namespace Greenlyst\GreenMoney\Util;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

final class Request
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

    public const TYPE_CHECK = "ECHECK";

    public const TYPE_NOTIFICATION = "NOTIFICATION";

    public const TYPE_REPORT = "REPORT";
    /**
     * @var string
     */
    private $type;

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
        $this->type = $type;
    }

    /**
     * Returns the endpoint based on the type and environment
     *
     * @param $type
     *
     * @return string
     */
    private function getEndpoint($type): string
    {
        return sprintf('https://%s/%s.asmx', ($this->live ? 'greenbyphone.com' : 'cpsandbox.com'), strtolower($type));
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
     *
     * @throws Exception
     */
    public function request(string $method, array $options, $resultArray = []): array
    {
        $client = new Client([
            'base_uri' => $this->getEndpoint($this->type),
        ]);

        try {
            $response = $client->request('POST', $method, [
                'body' => array_merge($options, [
                    'Client_ID' => $this->getClientId(),
                    'ApiPassword' => $this->getApiPassword(),
                    'x_delim_data' => 'true',
                    'x_delim_char' => ',',
                ]),
            ]);

            return $this->parseResponse($response, $resultArray);

        } catch (GuzzleException $exception) {
            throw new Exception(sprintf('Failed with error #%d: %s', $exception->getCode(), $exception->getMessage()));
        }
    }

    /**
     * Parses the response and returns and array
     *
     * @param ResponseInterface $response
     * @param $resultArray
     *
     * @return array
     *
     * @throws Exception
     */
    private function parseResponse(ResponseInterface $response, $resultArray): array
    {
        if ($response->getStatusCode() === 200) {
            try {

                return $this->resultToArray($response->getBody()->getContents(), ',', $resultArray);
            } catch (Exception $exception) {

                throw new Exception('An error occurred while attempting to parse the API result: ' . $exception->getMessage());
            }
        } else {
            throw new Exception('Invalid Status Code Returned');
        }
    }

    /**
     * Function takes result string from API and parses into PHP associative Array
     *
     * If a return is specified to be returned as delimited, it will return the string.
     * Otherwise, this function will be called to
     * return the result as an associative array in the format specified by the API documentation.
     *
     * @param string $result The result string as returned by cURL
     * @param string $delim_char The character used to delimit the string in cURL
     * @param array $keys An array containing the key names for the result variable
     * as specified by the API docs
     *
     * @return array Associative array of key=>values pair described by the API docs as the return for the called method
     */
    private function resultToArray(string $result, string $delim_char, array $keys): array
    {
        $split = explode($delim_char, $result);
        $resultArray = [];
        foreach ($keys as $key => $keyName) {
            $resultArray[$keyName] = $split[$key];
        }
        return $resultArray;
    }
}
