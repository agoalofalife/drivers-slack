<?php
declare(strict_types=1);

namespace FondBot\Drivers\Slack\TypeRequest;


use FondBot\Drivers\Exceptions\InvalidRequest;
use FondBot\Drivers\Slack\Contracts\TypeRequest;
use FondBot\Drivers\Slack\SlackDriver;
use FondBot\Http\Request as HttpRequest;

class EventRequest implements TypeRequest
{
    private $request;

    public function __construct(HttpRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     * @internal param HttpRequest $request
     */
    public function getUserId(): string
    {
        return $this->request->getParameter('event.user');
    }

    /**
     * @return string
     * @internal param HttpRequest $request
     */
    public function getChatId(): string
    {
        return  (string) $this->request->getParameters()['event']['channel'];
    }

    /**
     * Verify incoming request data.
     *
     * @param SlackDriver $driver
     * @return void
     * @throws InvalidRequest
     * @internal param HttpRequest $request
     */
    public function verifyRequest(SlackDriver $driver): void
    {
        if ( !$this->request->getParameter('token') == $driver->getParameter('verify_token') )
        {
            throw new InvalidRequest('Invalid verify token');
        }
    }
}