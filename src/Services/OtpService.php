<?php

namespace Kazinokib\BdappsApi\Services;

use GuzzleHttp\Client;
use Kazinokib\BdappsApi\Exceptions\BdappsApiException;

/**
 * Class OtpService
 *
 * This service handles OTP (One-Time Password) related operations for the BDApps API.
 * It provides methods to request and verify OTPs for subscribers.
 *
 * @package Kazinokib\BdappsApi\Services
 */
class OtpService
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
     * OtpService constructor.
     *
     * @param array $config The configuration array containing API credentials and settings
     */
    public function __construct(array $config): void
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => $this->config['base_url'],
            'timeout'  => 10.0,
        ]);
    }

    /**
     * Request an OTP for the given subscriber.
     *
     * This method sends a request to the BDApps API to generate and send an OTP to the specified subscriber.
     *
     * @param string $subscriberId The unique identifier of the subscriber
     * @param array $applicationMetaData Additional metadata for the application (optional)
     * @return array The API response containing the OTP request details
     * @throws BdappsApiException If the OTP request fails due to API error or network issues
     */
    public function requestOtp(string $subscriberId, array $applicationMetaData = []): array
    {
        $endpoint = '/subscription/otp/request';

        $payload = [
            'applicationId' => $this->config['app_id'],
            'password' => $this->config['app_password'],
            'subscriberId' => $subscriberId,
        ];

        if (!empty($applicationMetaData)) {
            $payload['applicationMetaData'] = $applicationMetaData;
        }

        try {
            $response = $this->client->post($endpoint, [
                'json' => $payload
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            throw new BdappsApiException("OTP request failed: " . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Verify an OTP provided by the subscriber.
     *
     * This method sends a request to the BDApps API to verify the OTP entered by the subscriber.
     *
     * @param string $referenceNo The reference number of the original OTP request
     * @param string $otp The OTP entered by the subscriber
     * @return array The API response containing the verification result
     * @throws BdappsApiException If the OTP verification fails due to API error or network issues
     */
    public function verifyOtp(string $referenceNo, string $otp): array
    {
        $endpoint = '/subscription/otp/verify';

        $payload = [
            'applicationId' => $this->config['app_id'],
            'password' => $this->config['app_password'],
            'referenceNo' => $referenceNo,
            'otp' => $otp
        ];

        try {
            $response = $this->client->post($endpoint, [
                'json' => $payload
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            throw new BdappsApiException("OTP verification failed: " . $e->getMessage(), $e->getCode());
        }
    }
}
