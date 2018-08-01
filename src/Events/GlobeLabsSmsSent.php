<?php

namespace Coreproc\GlobeLabsSms\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Psr\Http\Message\ResponseInterface;

class GlobeLabsSmsSent
{
    use Dispatchable;

    protected $response;

    /**
     * Create a new event instance.
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
