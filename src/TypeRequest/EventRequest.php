<?php
declare(strict_types=1);

namespace FondBot\Drivers\Slack\TypeRequest;


use FondBot\Drivers\Slack\Contracts\TypeRequest;
use FondBot\Http\Request as HttpRequest;

class EventRequest implements TypeRequest
{
    /**
     * @param HttpRequest $request
     * @return string
     */
    public function getUserId(HttpRequest $request): string
    {
        return $request->getParameter('event.user');
    }

    /**
     * @param HttpRequest $request
     * @return string
     */
    public function getChatId(HttpRequest $request): string
    {
        return  (string) $request->getParameters()['event']['channel'];
    }
}