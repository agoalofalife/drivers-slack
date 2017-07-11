<?php
declare(strict_types=1);

namespace FondBot\Drivers\Slack\Contracts;

use FondBot\Http\Request as HttpRequest;

interface TypeRequest
{
    /**
     * @param HttpRequest $request
     * @return string
     */
    public function getUserId(HttpRequest $request) : string;

    /**
     * @param HttpRequest $request
     * @return string
     */
    public function getChatId(HttpRequest $request) : string;
}