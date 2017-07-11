<?php
declare(strict_types=1);

namespace FondBot\Drivers\Slack;

use FondBot\Http\Request as HttpRequest;

trait FactoryTypeRequest
{
    /**
     * @param HttpRequest $request
     * @return string
     */
    private function getUserId(HttpRequest $request) : string
    {
        return $request->getParameter('event.user') ?? $request->getParameter('user_id');
    }

    /**
     * @param HttpRequest $request
     * @return string
     */
    private function getChatId(HttpRequest $request) : string
    {
       $parameters = $request->getParameters();
       return  (string) isset( $parameters['event']) ? $parameters['event']['channel'] : $parameters['channel_id'];
    }
}