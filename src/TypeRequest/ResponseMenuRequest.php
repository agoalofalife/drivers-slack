<?php
declare(strict_types=1);

namespace FondBot\Drivers\Slack\TypeRequest;


use FondBot\Drivers\Exceptions\InvalidRequest;
use FondBot\Drivers\Slack\Contracts\TypeRequest;
use FondBot\Drivers\Slack\SlackDriver;
use FondBot\Http\Request as HttpRequest;

/**
 * Class ResponseMenuRequest
 *
 * @package FondBot\Drivers\Slack\TypeRequest
 */
class ResponseMenuRequest implements TypeRequest
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
    public function getParameters(): array
    {
        return json_decode($this->request->getParameter('payload'), true);
    }
}