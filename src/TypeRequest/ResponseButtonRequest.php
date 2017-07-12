<?php
declare(strict_types=1);

namespace FondBot\Drivers\Slack\TypeRequest;

use FondBot\Drivers\Exceptions\InvalidRequest;
use FondBot\Drivers\Slack\Contracts\TypeRequest;
use FondBot\Drivers\Slack\SlackDriver;
use FondBot\Http\Request as HttpRequest;

/**
 * Class ResponseButtonRequest
 *
 * @package FondBot\Drivers\Slack\TypeRequest
 */
class ResponseButtonRequest implements TypeRequest
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
     * @return string
     * @internal param HttpRequest $request
     */
    public function getUserId(): string
    {
        return $this->getParameters()['user']['id'];
    }

    /**
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
     * @return string
     */
    public function getText(): string
    {
        return $this->getParameters()['actions'][0]['value'];
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
}