<?php

namespace Kazinokib\BdappsApi\Services;

use Kazinokib\BdappsApi\Exceptions\BdappsApiException;

class SubscriptionReceiver
{
    private $frequency;
    private $status;
    private $applicationId;
    private $subscriberId;
    private $timestamp;

    public function __construct()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BdappsApiException("Invalid subscription notification received");
        }

        $this->validateAndSetProperties($input);
    }

    private function validateAndSetProperties($input)
    {
        $requiredFields = ['frequency', 'status', 'subscriberId', 'applicationId', 'timeStamp'];

        foreach ($requiredFields as $field) {
            if (!isset($input[$field])) {
                throw new BdappsApiException("Missing required field: $field");
            }
        }

        $this->frequency = $input['frequency'];
        $this->status = $input['status'];
        $this->subscriberId = $input['subscriberId'];
        $this->applicationId = $input['applicationId'];
        $this->timestamp = $input['timeStamp'];
    }

    public function getFrequency()
    {
        return $this->frequency;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getSubscriberId()
    {
        return $this->subscriberId;
    }

    public function getApplicationId()
    {
        return $this->applicationId;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function toArray()
    {
        return [
            'frequency' => $this->frequency,
            'status' => $this->status,
            'subscriberId' => $this->subscriberId,
            'applicationId' => $this->applicationId,
            'timestamp' => $this->timestamp,
        ];
    }
}
