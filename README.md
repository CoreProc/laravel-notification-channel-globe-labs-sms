# Laravel Globe Labs SMS Notification Channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/coreproc/laravel-notification-channel-globe-labs-sms.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/laravel-notification-channel-globe-labs-sms)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![StyleCI](https://styleci.io/repos/8b2O04/shield)](https://styleci.io/repos/8b2O04)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/1de49b17-79c9-4e8b-816b-585d846128fe.svg?style=flat-square)](https://insight.sensiolabs.com/projects/1de49b17-79c9-4e8b-816b-585d846128fe)
[![Quality Score](https://img.shields.io/scrutinizer/g/coreproc/laravel-notification-channel-globe-labs-sms.svg?style=flat-square)](https://scrutinizer-ci.com/g/coreproc/laravel-notification-channel-globe-labs-sms)
[![Total Downloads](https://img.shields.io/packagist/dt/coreproc/laravel-notification-channel-globe-labs-sms.svg?style=flat-square)](https://packagist.org/packages/coreproc/laravel-notification-channel-globe-labs-sms)

This package makes it easy to send notifications using [Globe Labs SMS](http://www.globelabs.com.ph/#!/developer/api/sms) with Laravel 5.3 and above.

## Contents

- [Installation](#installation)
	- [Setting up the GlobeLabsSms service](#setting-up-the-Globe-Labs-Sms-service)
- [Usage](#usage)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

Install this package with Composer:

```
composer require coreproc/laravel-notification-channel-globe-labs-sms
```
    
Register the ServiceProvider in your config/app.php (Skip this step if you are using Laravel 5.5 and above):

```
Coreproc\GlobeLabsSms\GlobeLabsSmsServiceProvider::class,
```

### Setting up the Globe Labs Sms service

Start by creating a project here: [http://www.globelabs.com.ph/#!/developer/api/sms](http://www.globelabs.com.ph/#!/developer/api/sms)

Please note that this package does not handle the opt-in steps required for a user to subscribe to your Globe Labs SMS application.

This package assumes that you have the opt-in steps handled either via [SMS](http://www.globelabs.com.ph/docs/#getting-started-opt-in-via-sms) or through a [web form](http://www.globelabs.com.ph/docs/#getting-started-opt-in-via-webform) and that you already have access to the subscriber's access token.

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
            'access_token' => 'access-token-obtained-from-sms-opt-in-this-could-be-stored-in-your-database',
            'address' => '09171234567', // can be any format as long as it is a valid mobile number
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
