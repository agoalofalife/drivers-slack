<?php
declare(strict_types=1);

namespace FondBot\Drivers\Slack\TypeRequest;


use FondBot\Drivers\Slack\Contracts\TypeRequest;
use FondBot\Http\Request as HttpRequest;

class ResponseButtonRequest implements TypeRequest
{

    /**
     * @param HttpRequest $request
     * @return string
     */
    public function getUserId(HttpRequest $request): string
    {
        return $request->getParameter('user.id');
    }

    /**
     * @param HttpRequest $request
     * @return string
     */
    public function getChatId(HttpRequest $request): string
    {
        return  (string) $request->getParameter('channel.id');
    }
}