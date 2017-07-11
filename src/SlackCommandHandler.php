<?php

declare(strict_types=1);

namespace FondBot\Drivers\Slack;

use FondBot\Templates\Attachment;
use FondBot\Drivers\CommandHandler;
use FondBot\Drivers\Commands\SendMessage;
use FondBot\Drivers\Commands\SendRequest;
use FondBot\Drivers\Commands\SendAttachment;

class SlackCommandHandler extends CommandHandler
{
    /** @var SlackDriver */
    protected $driver;

    /**
     * Handle send message command.
     *
     * @param SendMessage $command
     */
    protected function handleSendMessage(SendMessage $command): void
    {
        $payload = [
            'channel' => $command->getChat()->getId(),
            'text' => $command->getText(),
        ];

        if ($command->getTemplate() !== null) {
            $payload['attachments'] = $this->driver->getTemplateCompiler()->compile($command->getTemplate());
        }
        $payload   = array_merge($payload, [
            'token'   => $this->driver->getParameter('token')
        ]);

        $this->driver->getHttp()->post($this->driver->getBaseUrl() . $this->driver->mapDriver('postMessage'), [
            'query' => $payload
        ]);
    }

    /**
     * Handle send attachment command.
     *
     * @param SendAttachment $command
     */
    protected function handleSendAttachment(SendAttachment $command): void
    {
        $payload = [
            'channel' => $command->getChat()->getId(),
            'token'   => $this->driver->getParameter('token'),
        ];

        $payload = array_merge($payload,  $command->getAttachment()->getMetadata());
        $this->driver->getHttp()->post($this->driver->getBaseUrl() . $this->driver->mapDriver('postMessage'), [
            'query' => $payload
        ])->getBody()->getContents();
    }

    /**
     * Handle send request command.
     *
     * @param SendRequest $command
     */
    protected function handleSendRequest(SendRequest $command): void
    {
    }
}
