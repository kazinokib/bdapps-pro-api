# BDApps API Laravel Package

This Laravel package provides an easy-to-use interface for integrating with the BDApps API, allowing you to send SMS, handle USSD sessions, manage OTP, and perform CAAS operations in your Laravel applications.

## Table of Contents

1. [Installation](#installation)
2. [Configuration](#configuration)
3. [Usage](#usage)
   - [SMS Service](#sms-service)
   - [USSD Service](#ussd-service)
   - [OTP Service](#otp-service)
   - [CAAS Service](#caas-service)
4. [Error Handling](#error-handling)
5. [Contributing](#contributing)
6. [License](#license)

## Installation

You can install the package via composer:

```bash
composer require kazinokib/bdappsapi
```

## Configuration

After installation, publish the configuration file:

```bash
php artisan vendor:publish --provider="Kazinokib\BdappsApi\BdappsApiServiceProvider" --tag="config"
```

This will create a `config/bdappsapi.php` file in your app's configuration directory. You should configure your BDApps API credentials in your `.env` file:

```
BDAPPS_APP_ID=your_app_id
BDAPPS_APP_PASSWORD=your_app_password
BDAPPS_BASE_URL=https://developer.bdapps.com
```

## Usage

### SMS Service

To send an SMS:

```php
use Kazinokib\BdappsApi\BdappsApi;

public function sendSms(BdappsApi $bdappsApi)
{
    $result = $bdappsApi->smsService->send('Your message', ['tel:8801812345678']);
    // Handle the result
}
```

### USSD Service

To send a USSD message:

```php
use Kazinokib\BdappsApi\BdappsApi;

public function sendUssd(BdappsApi $bdappsApi)
{
    $result = $bdappsApi->ussdService->send('Your message', 'session_id', 'tel:8801812345678');
    // Handle the result
}
```

### OTP Service

To request an OTP:

```php
use Kazinokib\BdappsApi\BdappsApi;

public function requestOtp(BdappsApi $bdappsApi)
{
    $result = $bdappsApi->otpService->requestOtp('tel:8801812345678');
    // Handle the result
}
```

To verify an OTP:

```php
use Kazinokib\BdappsApi\BdappsApi;

public function verifyOtp(BdappsApi $bdappsApi)
{
    $result = $bdappsApi->otpService->verifyOtp('reference_no', 'otp_code');
    // Handle the result
}
```

### CAAS Service

To query balance:

```php
use Kazinokib\BdappsApi\BdappsApi;

public function queryBalance(BdappsApi $bdappsApi)
{
    $result = $bdappsApi->caasService->queryBalance('tel:8801812345678');
    // Handle the result
}
```

To perform a direct debit:

```php
use Kazinokib\BdappsApi\BdappsApi;

public function directDebit(BdappsApi $bdappsApi)
{
    $result = $bdappsApi->caasService->directDebit('external_tx_id', 'tel:8801812345678', 10.00);
    // Handle the result
}
```

## Error Handling

All services throw a `BdappsApiException` on error. You should catch this exception and handle it appropriately:

```php
use Kazinokib\BdappsApi\Exceptions\BdappsApiException;

try {
    $result = $bdappsApi->smsService->send('Your message', ['tel:8801812345678']);
} catch (BdappsApiException $e) {
    // Handle the exception
    $errorCode = $e->getErrorCode();
    $errorDetail = $e->getErrorDetail();
}
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
