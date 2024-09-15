<?php

namespace Kazinokib\BdappsApi;

use Kazinokib\BdappsApi\Services\SmsService;
use Kazinokib\BdappsApi\Services\UssdService;
use Kazinokib\BdappsApi\Services\CaasService;
use Kazinokib\BdappsApi\Services\OtpService;
use Kazinokib\BdappsApi\Services\SubscriptionService;

/**
 * Class BdappsApi
 *
 * This class serves as the main entry point for the BDApps API integration.
 * It provides access to various services for SMS, USSD, CaaS, and OTP operations.
 *
 * @package Kazinokib\BdappsApi
 */
class BdappsApi
{
    /**
     * @var SmsService The SMS service instance
     */
    protected $smsService;

    /**
     * @var UssdService The USSD service instance
     */
    protected $ussdService;

    /**
     * @var CaasService The CaaS (Charging as a Service) instance
     */
    protected $caasService;

    /**
     * @var OtpService The OTP (One-Time Password) service instance
     */
    protected $otpService;

    /**
     * @var SubscriptionService The Subscription service instance
     */
    protected $subscriptionService;

    /**
     * BdappsApi constructor.
     *
     * @param SmsService $smsService The SMS service instance
     * @param UssdService $ussdService The USSD service instance
     * @param CaasService $caasService The CaaS service instance
     * @param OtpService $otpService The OTP service instance
     * @param SubscriptionService $subscriptionService The Subscription service instance
     */
    public function __construct(
        SmsService $smsService,
        UssdService $ussdService,
        CaasService $caasService,
        OtpService $otpService,
        SubscriptionService $subscriptionService
    ) {
        $this->smsService = $smsService;
        $this->ussdService = $ussdService;
        $this->caasService = $caasService;
        $this->otpService = $otpService;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Get the SMS service instance.
     *
     * @return SmsService The SMS service instance
     */
    public function sms(): SmsService
    {
        return $this->smsService;
    }

    /**
     * Get the USSD service instance.
     *
     * @return UssdService The USSD service instance
     */
    public function ussd(): UssdService
    {
        return $this->ussdService;
    }

    /**
     * Get the CaaS (Charging as a Service) instance.
     *
     * @return CaasService The CaaS service instance
     */
    public function caas(): CaasService
    {
        return $this->caasService;
    }

    /**
     * Get the OTP (One-Time Password) service instance.
     *
     * @return OtpService The OTP service instance
     */
    public function otp(): OtpService
    {
        return $this->otpService;
    }

    /**
     * Get the Subscription service instance.
     *
     * @return SubscriptionService The Subscription service instance
     */
    public function subscription(): SubscriptionService
    {
        return $this->subscriptionService;
    }

    public function handleSubscriptionNotification(): array
    {
        return $this->subscriptionService->handleNotification();
    }
}
