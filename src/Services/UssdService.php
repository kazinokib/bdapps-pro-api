<?php

namespace Kazinokib\BdappsApi\Services;

use GuzzleHttp\Client;
use Kazinokib\BdappsApi\Exceptions\BdappsApiException;

/**
 * Class UssdService
 *
 * This service handles USSD-related operations for the BDApps API.
 * It provides methods to send USSD messages and receive USSD responses.
 *
 * @package Kazinokib\BdappsApi\Services
 */
class UssdService
{
    /**
     * @var array The configuration array for the service
     */
    private $config;

    /**
     * @var Client The HTTP client used for API requests
     */
    private $client;

    /**
     * UssdService constructor.
     *
     * @param array $config The configuration array containing API credentials and settings
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => $this->config['base_url'],
            'timeout'  => 10.0,
        ]);
    }

    /**
     * Send a USSD message.
     *
     * @param string $message The content of the USSD message
     * @param string $sessionId The session ID for the USSD session
     * @param string $destinationAddress The destination address (usually the phone number)
     * @param string $ussdOperation The USSD operation type (default: 'mt-cont')
     * @param array $options Additional options for the USSD message (encoding, chargingAmount)
     * @return array The API response containing the USSD sending details
     * @throws BdappsApiException If the USSD sending fails due to API error or network issues
     */
    public function send(string $message, string $sessionId, string $destinationAddress, string $ussdOperation = 'mt-cont', array $options = []): array
    {
        $endpoint = '/ussd/send';

        $payload = [
            'applicationId' => $this->config['app_id'],
            'password' => $this->config['app_password'],
            'message' => $message,
            'sessionId' => $sessionId,
            'ussdOperation' => $ussdOperation,
            'destinationAddress' => $destinationAddress
        ];

        // Add optional parameters
        if (isset($options['encoding'])) {
            $payload['encoding'] = $options['encoding'];
        }
        if (isset($options['chargingAmount'])) {
            $payload['chargingAmount'] = $options['chargingAmount'];
        }

        try {
            $response = $this->client->post($endpoint, [
                'json' => $payload
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            throw new BdappsApiException("USSD sending failed: " . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Receive and process an incoming USSD message.
     *
     * @return array The validated incoming USSD data
     * @throws BdappsApiException If the incoming USSD message is invalid or missing required fields
     */
    public function receive(): array
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BdappsApiException("Invalid USSD message received");
        }

        // Validate the input
        $requiredFields = ['message', 'sessionId', 'ussdOperation', 'applicationId', 'sourceAddress'];
        foreach ($requiredFields as $field) {
            if (!isset($input[$field])) {
                throw new BdappsApiException("Missing required field: $field");
            }
        }

        return $input;
    }
}
