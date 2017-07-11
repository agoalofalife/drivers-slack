<?php
declare(strict_types=1);

namespace FondBot\Drivers\Slack\TypeRequest;


use FondBot\Drivers\Exceptions\InvalidRequest;
use FondBot\Drivers\Slack\Contracts\TypeRequest;
use FondBot\Drivers\Slack\SlackDriver;
use FondBot\Http\Request as HttpRequest;

class ResponseButtonRequest implements TypeRequest
{

    /**
     * @param HttpRequest $request
     * @return string
     */
    public function getUserId(HttpRequest $request): string
    {
        $response = json_decode($request->getParameter('payload'));
        return $response->user->id;
    }

    /**
     * @param HttpRequest $request
     * @return string
     */
    public function getChatId(HttpRequest $request): string
    {
        $response = json_decode($request->getParameter('payload'));
        return  (string) $response->channel->id;
    }

    /**
     * Verify incoming request data.
     *
     * @param HttpRequest $request
     * @param SlackDriver $driver
     * @return void
     * @throws InvalidRequest
     */
    public function verifyRequest(HttpRequest $request, SlackDriver $driver): void
    {
        $response = json_decode($request->getParameter('payload'));

        if ( !$response->token == $driver->getParameter('verify_token') )
        {
            throw new InvalidRequest('Invalid verify token');
        }
    }
}