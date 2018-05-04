<?php

namespace Coreproc\GlobeLabsSms;

use Coreproc\GlobeLabsSms\Exceptions\CouldNotSendNotification;
use Coreproc\GlobeLabsSms\Events\MessageWasSent;
use Coreproc\GlobeLabsSms\Events\SendingMessage;
use Illuminate\Notifications\Notification;

class GlobeLabsSmsChannel
{
    public function __construct()
    {
        // Initialisation code here
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \Coreproc\GlobeLabsSms\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        //$response = [a call to the api of your notification send]

//        if ($response->error) { // replace this by the code need to check for errors
//            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
//        }
    }
}
