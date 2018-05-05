<?php

namespace Coreproc\GlobeLabsSms\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        return new static($response);
    }
}
