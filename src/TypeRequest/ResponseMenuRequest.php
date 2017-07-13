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

/**
 * Class ResponseMenuRequest
 *
 * @package FondBot\Drivers\Slack\TypeRequest
 */
class ResponseMenuRequest implements TypeRequest, ReceivedMessage
{

    /**
     * @var HttpRequest
     */
    private $request;

    /**
     * ResponseButtonRequest constructor.
     *
     * @param HttpRequest $request
     */
    public function __construct(HttpRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Get user id from request
     *
     * @return string
     * @internal param HttpRequest $request
     */
    public function getUserId(): string
    {
        return $this->getParameters()['user']['id'];
    }

    /**
     * Get channel id from request
     *
     * @return string
     * @internal param HttpRequest $request
     */
    public function getChatId(): string
    {
        return (string) $this->getParameters()['channel']['id'];
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
        if ( !$this->getParameters()['token'] == $driver->getParameter('verify_token') )
        {
            throw new InvalidRequest('Invalid verify token');
        }
    }

    /**
     * Get parameters
     *
     * @return array
     */
    private function getParameters(): array
    {
        return json_decode($this->request->getParameter('payload'), true);
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->getParameters()['actions'][0]['selected_options'][0]['value'];
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
        return isset($this->getParameters()['actions']);
    }

    /**
     * Get payload.
     *
     * @return string|null
     */
    public function getData(): ?string
    {
        return json_encode($this->getParameters()['actions']);
    }
}