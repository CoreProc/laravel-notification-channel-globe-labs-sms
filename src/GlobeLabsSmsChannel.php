<?php

namespace Coreproc\GlobeLabsSms;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Notifications\Notification;
use Coreproc\GlobeLabsSms\Exceptions\CouldNotSendNotification;

class GlobeLabsSmsChannel
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (empty($notifiable->routeNotificationFor('globeLabsSms'))) {
            throw new CouldNotSendNotification('Missing method in your notifiable: routeNotificationForGlobeLabs().');
        }

        $contactInfo = $notifiable->routeNotificationFor('globeLabsSms');

        // The contact info should include an address (mobile number). We are making access_token optional. The API
        // response will catch that anyway.
        if (empty($contactInfo['address'])) {
            throw new CouldNotSendNotification('Missing address variable from your routeNotificationForGlobeLabs().');
        }

        $message = $notification->toGlobeLabsSms($notifiable);

        try {
            $this->client->request('POST', $message->getApiSendUrl(), [
                'body' => $message->toJson(),
            ]);
        } catch (GuzzleException $exception) {
            throw new CouldNotSendNotification($exception->getMessage());
        }
    }
}
