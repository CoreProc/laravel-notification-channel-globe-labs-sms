<?php

namespace Coreproc\GlobeLabsSms;

use Exception;
use Coreproc\MsisdnPh\Msisdn;
use Coreproc\GlobeLabsSms\Exceptions\CouldNotSendNotification;

class GlobeLabsSmsMessage
{
    protected $message;

    protected $clientCorrelator;

    protected $accessToken;

    protected $address;

    protected $globeLabsSmsApiSendUrl = 'https://devapi.globelabs.com.ph/smsmessaging/v1/outbound/{senderAddress}/requests?access_token={accessToken}';

    public static function create($notifiable)
    {
        $instance = new static();

        $info = $notifiable->routeNotificationFor('globeLabsSms');

        $instance->setAddress($info['address']);
        $instance->setAccessToken($info['access_token']);

        return $instance;
    }

    public function getApiSendUrl()
    {
        // The URL should have the {senderAddress} and {accessToken} values embedded in the string so we can replace
        // this with the correct values.
        $url = config('broadcasting.connections.globe_labs_sms.api_send_url', $this->globeLabsSmsApiSendUrl);

        $url = str_replace('{senderAddress}', $this->getSenderAddress(), $url);
        $url = str_replace('{accessToken}', $this->getAccessToken(), $url);

        return $url;
    }

    public function getSenderAddress()
    {
        return substr(config('broadcasting.connections.globe_labs_sms.short_code'), -4);
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getAddress()
    {
        try {
            $msisdn = new Msisdn($this->address);
        } catch (Exception $e) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($e->getMessage());
        }

        return 'tel:'.$msisdn->get(true);
    }

    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    public function getClientCorrelator()
    {
        return $this->clientCorrelator;
    }

    public function setClientCorrelator($clientCorrelator)
    {
        $this->clientCorrelator = $clientCorrelator;

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function toJson()
    {
        $request['outboundSMSMessageRequest']['address'] = $this->getAddress();
        $request['outboundSMSMessageRequest']['senderAddress'] = $this->getSenderAddress();

        if (! empty($this->getClientCorrelator())) {
            $request['outboundSMSMessageRequest']['clientCorrelator'] = $this->getClientCorrelator();
        }

        $request['outboundSMSMessageRequest']['outboundSMSTextMessage']['message'] = $this->getMessage();

        return json_encode($request);
    }
}
