<?php

namespace Kazinokib\BdappsApi\Services;

use GuzzleHttp\Client;
use Kazinokib\BdappsApi\Exceptions\BdappsApiException;

/**
 * Class SmsService
 *
 * This service handles SMS-related operations for the BDApps API.
 * It provides methods to send SMS messages, receive delivery reports, and handle incoming SMS messages.
 *
 * @package Kazinokib\BdappsApi\Services
 */
class SmsService
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
     * SmsService constructor.
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
     * Send an SMS message to one or more recipients.
     *
     * @param string $message The content of the SMS message
     * @param string|array $addresses The recipient address(es)
     * @param array $options Additional options for the SMS (sourceAddress, deliveryStatusRequest, encoding, version)
     * @return array The API response containing the SMS sending details
     * @throws BdappsApiException If the SMS sending fails due to API error or network issues
     */
    public function send(string $message, $addresses, array $options = []): array
    {
        $endpoint = '/sms/send';

        $payload = [
            'applicationId' => $this->config['app_id'],
            'password' => $this->config['app_password'],
            'message' => $message,
            'destinationAddresses' => is_array($addresses) ? $addresses : [$addresses],
        ];

        // Add optional parameters
        if (isset($options['sourceAddress'])) {
            $payload['sourceAddress'] = $options['sourceAddress'];
        }
        if (isset($options['deliveryStatusRequest'])) {
            $payload['deliveryStatusRequest'] = $options['deliveryStatusRequest'];
        }
        if (isset($options['encoding'])) {
            $payload['encoding'] = $options['encoding'];
        }
        if (isset($options['version'])) {
            $payload['version'] = $options['version'];
        }

        try {
            $response = $this->client->post($endpoint, [
                'json' => $payload
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            throw new BdappsApiException("SMS sending failed: " . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Receive and process an SMS delivery report.
     *
     * @return array The validated delivery report data
     * @throws BdappsApiException If the delivery report is invalid or missing required fields
     */
    public function receiveDeliveryReport(): array
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BdappsApiException("Invalid delivery report received");
        }

        // Validate the input
        $requiredFields = ['destinationAddress', 'timeStamp', 'requestId', 'deliveryStatus'];
        foreach ($requiredFields as $field) {
            if (!isset($input[$field])) {
                throw new BdappsApiException("Missing required field: $field");
            }
        }

        return $input;
    }

    /**
     * Receive and process an incoming SMS message.
     *
     * @return array The validated incoming SMS data
     * @throws BdappsApiException If the incoming SMS is invalid or missing required fields
     */
    public function receiveSms(): array
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BdappsApiException("Invalid SMS received");
        }

        // Validate the input
        $requiredFields = ['message', 'requestId', 'applicationId', 'sourceAddress', 'version'];
        foreach ($requiredFields as $field) {
            if (!isset($input[$field])) {
                throw new BdappsApiException("Missing required field: $field");
            }
        }

        return $input;
    }
}
