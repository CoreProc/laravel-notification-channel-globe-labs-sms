# Laravel Globe Labs SMS Notification Channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/coreproc/laravel-notification-channel-globe-labs-sms.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/laravel-notification-channel-globe-labs-sms)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/coreproc/laravel-notification-channel-globe-labs-sms/master.svg?style=flat-square)](https://travis-ci.org/coreproc/laravel-notification-channel-globe-labs-sms)
[![StyleCI](https://styleci.io/repos/8b2O04/shield)](https://styleci.io/repos/8b2O04)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/1de49b17-79c9-4e8b-816b-585d846128fe.svg?style=flat-square)](https://insight.sensiolabs.com/projects/1de49b17-79c9-4e8b-816b-585d846128fe)
[![Quality Score](https://img.shields.io/scrutinizer/g/coreproc/laravel-notification-channel-globe-labs-sms.svg?style=flat-square)](https://scrutinizer-ci.com/g/coreproc/laravel-notification-channel-globe-labs-sms)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/coreproc/laravel-notification-channel-globe-labs-sms/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/coreproc/laravel-notification-channel-globe-labs-sms/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/coreproc/laravel-notification-channel-globe-labs-sms.svg?style=flat-square)](https://packagist.org/packages/coreproc/laravel-notification-channel-globe-labs-sms)

This package makes it easy to send notifications using [Globe Labs SMS](http://www.globelabs.com.ph/#!/developer/api/sms) with Laravel 5.3 and above.

## Contents

- [Installation](#installation)
	- [Setting up the GlobeLabsSms service](#setting-up-the-GlobeLabsSms-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

Install this package with Composer:

```
composer require coreproc/laravel-notification-channel-globe-labs-sms
```
    
Register the ServiceProvider in your config/app.php (Skip this step if you are using Laravel 5.5):

```
Coreproc\GlobeLabsSms\GlobeLabsSmsServiceProvider::class,
```

### Setting up the GlobeLabsSms service

You need to register for a server key from Firebase for your application. Start by creating a project here: 
[http://www.globelabs.com.ph/#!/developer/api/sms](http://www.globelabs.com.ph/#!/developer/api/sms)

Once you've registered and set up your app, add the short code to your configuration in `config/broadcasting.php`

```php
'connections' => [
    ....
    'globe_labs_sms' => [
        'short_code' => env('GLOBE_LABS_SMS_SHORT_CODE'),
    ],
    ...
]
```

## Usage

Set a `routeNotificationForGlobeLabsSms()` method in your model/class with the `Notifiable` trait. For example:

```php
class User extends Model
{
    use Notifiable;

    public function routeNotificationForGlobeLabsSms()
    {
        return [
            'access_token' => 'access-token-obtained-from-sms-opt-in',
            'address' => '09171234567',
        ];
    }
}
```

You can now send SMS via Globe Labs by creating an `GlobeLabsSmsMessage` in a `Notification` class:

```php
class AccountActivated extends Notification
{
    public function via($notifiable)
    {
        return [GlobeLabsSmsChannel::class];
    }

    public function toGlobeLabsSms($notifiable) 
    {
        return GlobeLabsSmsMessage::create($notifiable)
            ->setMessage('This is a test message');
    }
}
```

Call the SMS notification by calling the `notify()` method in the model/class. For example:

```php
$user = User::find(1);

$user->notify(new AccountActivated);
```

## Security

If you discover any security related issues, please email chris.bautista@coreproc.ph instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Chris Bautista](https://github.com/chrisbjr)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
