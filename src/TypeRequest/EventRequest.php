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

class EventRequest implements TypeRequest, ReceivedMessage
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
        if (!$this->request->getParameter('token') == $driver->getParameter('verify_token')) {
            throw new InvalidRequest('Invalid verify token');
        }
    }

    /**
     * @return null|string
     */
    public function getText() : ?string
    {
        if ($this->hasAttachment()) {
            return $this->request->getParameter('event.file.title');
        }
        return $this->request->getParameter('event.text');
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
        return $this->request->hasParameters('event.file');
    }

    /**
     * Get attachment.
     *
     * @return Attachment|null
     */
    public function getAttachment(): ?Attachment
    {
        return (new Attachment())->setMetadata($this->request->getParameter('event.file'));
    }

    /**
     * Determine if message has payload.
     *
     * @return bool
     */
    public function hasData(): bool
    {
        return $this->request->hasParameters('event.text');
    }

    /**
     * Get payload.
     *
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->request->getParameter('event.text');
    }
}