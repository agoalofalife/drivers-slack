<?php
declare(strict_types=1);
namespace FondBot\Drivers\Slack\TypeRequest;

use FondBot\Drivers\Exceptions\InvalidRequest;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Drivers\Slack\Contracts\TypeRequest;
use FondBot\Drivers\Slack\SlackDriver;
use FondBot\Http\Request as HttpRequest;
use FondBot\Templates\Attachment;
use FondBot\Templates\Location;

class CommandRequest implements TypeRequest, ReceivedMessage
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
       return $this->request->getParameter('user_id');
    }

    /**
     * @return string
     * @internal param HttpRequest $request
     */
    public function getChatId(): string
    {
      return $this->request->getParameters()['channel_id'];
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

    /**
     * @return null|string
     */
    public function getText() : ?string
    {
        return $this->request->getParameter('command');
    }

    /**
     * Get location.
     *
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
       return null;
    }

    /**
     * Determine if message has attachment.
     *
     * @return bool
     */
    public function hasAttachment(): bool
    {
        return false;
    }

    /**
     * Get attachment.
     *
     * @return Attachment|null
     */
    public function getAttachment(): ?Attachment
    {
       return null;
    }

    /**
     * Determine if message has payload.
     *
     * @return bool
     */
    public function hasData(): bool
    {
        return strlen($this->request->getParameter('text')) > 0;
    }

    /**
     * Get payload.
     *
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->request->getParameter('text');
    }
}