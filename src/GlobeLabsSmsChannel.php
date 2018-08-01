<?php

namespace Coreproc\GlobeLabsSms;

use Coreproc\GlobeLabsSms\Events\GlobeLabsSmsSent;
use Coreproc\GlobeLabsSms\Exceptions\CouldNotSendNotification;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Notifications\Notification;
use Lang;

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
            $response = $this->client->request('POST', $message->getApiSendUrl(), [
                'body' => $message->toJson(),
            ]);

            event(new GlobeLabsSmsSent($response));
        } catch (ConnectException $exception) {
            throw $this->generateExceptionMessage($exception, 'connect_exception');
        } catch (ClientException $exception) {
            throw $this->generateExceptionMessage($exception, 'client_exception');
        } catch (ServerException $exception) {
            throw $this->generateExceptionMessage($exception, 'server_exception');
        } catch (GuzzleException $exception) {
            throw $this->generateExceptionMessage($exception, 'guzzle_exception');
        }
    }

    private function generateExceptionMessage($exception, $langString)
    {
        $message = $exception->getMessage();

        if (! empty($exception->getResponse())) {
            $response = json_decode($exception->getResponse()->getBody()->getContents());
            $message = $response->error ?? $exception->getMessage();
        }

        if (Lang::has('globe_labs_sms::errors.' . $langString)) {
            $message = __('globe_labs_sms::errors.' . $langString);
        }

        return new CouldNotSendNotification($message);
    }
}
