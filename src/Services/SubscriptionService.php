<?php

namespace Kazinokib\BdappsApi\Services;

use GuzzleHttp\Client;
use Kazinokib\BdappsApi\Exceptions\BdappsApiException;

class SubscriptionService
{
    protected $config;
    protected $client;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => $this->config['base_url'],
            'timeout'  => $this->config['timeout'],
            'verify'   => $this->config['verify_ssl'],
        ]);
    }

    /**
     * Get the subscription status of a subscriber.
     *
     * @param string $subscriberId The unique identifier of the subscriber
     * @return string The subscription status
     * @throws BdappsApiException If the status check fails
     */
    public function getStatus(string $subscriberId): string
    {
        $endpoint = '/subscription/getstatus';

        $payload = [
            'applicationId' => $this->config['app_id'],
            'password' => $this->config['app_password'],
            'subscriberId' => $subscriberId
        ];

        try {
            $response = $this->client->post($endpoint, ['json' => $payload]);
            $result = json_decode($response->getBody(), true);
            return $result['subscriptionStatus'] ?? '';
        } catch (\Exception $e) {
            throw new BdappsApiException("Failed to get subscription status: " . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Subscribe a user to the service.
     *
     * @param string $subscriberId The unique identifier of the subscriber
     * @return string The subscription status after attempting to subscribe
     * @throws BdappsApiException If the subscription fails
     */
    public function subscribe(string $subscriberId): string
    {
        return $this->sendSubscriptionRequest($subscriberId, '1');
    }

    /**
     * Unsubscribe a user from the service.
     *
     * @param string $subscriberId The unique identifier of the subscriber
     * @return string The subscription status after attempting to unsubscribe
     * @throws BdappsApiException If the unsubscription fails
     */
    public function unsubscribe(string $subscriberId): string
    {
        return $this->sendSubscriptionRequest($subscriberId, '0');
    }

    /**
     * Send a subscription request (subscribe or unsubscribe).
     *
     * @param string $subscriberId The unique identifier of the subscriber
     * @param string $action '1' for subscribe, '0' for unsubscribe
     * @return string The subscription status after the request
     * @throws BdappsApiException If the request fails
     */
    protected function sendSubscriptionRequest(string $subscriberId, string $action): string
    {
        $endpoint = '/subscription/send';

        $payload = [
            'applicationId' => $this->config['app_id'],
            'password' => $this->config['app_password'],
            'subscriberId' => $subscriberId,
            'version' => '1.0',
            'action' => $action
        ];

        try {
            $response = $this->client->post($endpoint, ['json' => $payload]);
            $result = json_decode($response->getBody(), true);
            return $result['subscriptionStatus'] ?? '';
        } catch (\Exception $e) {
            $action = $action === '1' ? 'subscribe' : 'unsubscribe';
            throw new BdappsApiException("Failed to {$action}: " . $e->getMessage(), $e->getCode());
        }
    }

    public function handleNotification(): array
    {
        $receiver = new SubscriptionReceiver();
        return $receiver->toArray();
    }
}
