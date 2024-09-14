<?php

namespace Kazinokib\BdappsApi\Services;

use GuzzleHttp\Client;
use Kazinokib\BdappsApi\Exceptions\BdappsApiException;

/**
 * CaasService handles Charging as a Service (CaaS) operations for BDApps API.
 *
 * This service provides methods to query balance and perform direct debit operations
 * on subscriber accounts using the BDApps CaaS API.
 */

class CaasService
{
    protected $config;
    protected $client;

    /**
     * CaasService constructor.
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
     * Query the balance of a subscriber.
     *
     * @param string $subscriberId The unique identifier of the subscriber
     * @param string $paymentInstrumentName The name of the payment instrument (default: "Mobile Account")
     * @return array The API response containing the balance details
     * @throws BdappsApiException If the balance query fails
     */
    public function queryBalance($subscriberId, $paymentInstrumentName = "Mobile Account")
    {
        $endpoint = '/caas/balance/query';

        $payload = [
            'applicationId' => $this->config['app_id'],
            'password' => $this->config['app_password'],
            'subscriberId' => $subscriberId,
            'paymentInstrumentName' => $paymentInstrumentName
        ];

        try {
            $response = $this->client->post($endpoint, [
                'json' => $payload
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            throw new BdappsApiException("Balance query failed: " . $e->getMessage());
        }
    }

    /**
     * Perform a direct debit operation on a subscriber's account.
     *
     * @param string $externalTrxId The unique identifier for the transaction
     * @param string $subscriberId The unique identifier of the subscriber
     * @param float $amount The amount to be debited
     * @param string $paymentInstrumentName The name of the payment instrument (default: "Mobile Account")
     * @return array The API response containing the direct debit details
     * @throws BdappsApiException If the direct debit operation fails
     */
    public function directDebit($externalTrxId, $subscriberId, $amount, $paymentInstrumentName = "Mobile Account")
    {
        $endpoint = '/caas/direct/debit';

        $payload = [
            'applicationId' => $this->config['app_id'],
            'password' => $this->config['app_password'],
            'externalTrxId' => $externalTrxId,
            'subscriberId' => $subscriberId,
            'amount' => $amount,
            'paymentInstrumentName' => $paymentInstrumentName
        ];

        try {
            $response = $this->client->post($endpoint, [
                'json' => $payload
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            throw new BdappsApiException("Direct debit failed: " . $e->getMessage());
        }
    }

    /**
     * Get the list of payment instruments for a subscriber.
     *
     * @param string $subscriberId The unique identifier of the subscriber
     * @param string $type The type of payment instruments to retrieve (default: 'all')
     * @return array The API response containing the payment instrument list
     * @throws BdappsApiException If the payment instrument list retrieval fails
     */
    public function getPaymentInstrumentList($subscriberId, $type = 'all')
    {
        $endpoint = '/caas/list/pi';

        $payload = [
            'applicationId' => $this->config['app_id'],
            'password' => $this->config['app_password'],
            'subscriberId' => $subscriberId,
            'type' => $type
        ];

        try {
            $response = $this->client->post($endpoint, [
                'json' => $payload
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            throw new BdappsApiException("Get payment instrument list failed: " . $e->getMessage());
        }
    }
}
