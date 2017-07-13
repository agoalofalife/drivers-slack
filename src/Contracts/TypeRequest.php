<?php
declare(strict_types=1);

namespace FondBot\Drivers\Slack\Contracts;

use FondBot\Drivers\Slack\SlackDriver;

interface TypeRequest
{
    /**
     * @return string
     * @internal param HttpRequest $request
     */
    public function getUserId() : string;

    /**
     * @return string
     * @internal param HttpRequest $request
     */
    public function getChatId() : string;

    /**
     * Verify incoming request data.
     *
     * @param SlackDriver $driver
     * @return void
     * @internal param HttpRequest $request
     */
    public function verifyRequest(SlackDriver $driver): void;

    /**
     * @return string
     */
    public function getText() : ?string ;
}