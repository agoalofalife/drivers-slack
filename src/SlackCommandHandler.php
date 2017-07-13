<?php

declare(strict_types=1);

namespace FondBot\Drivers\Slack;

use FondBot\Drivers\CommandHandler;
use FondBot\Drivers\Commands\SendMessage;
use FondBot\Drivers\Commands\SendRequest;
use FondBot\Drivers\Commands\SendAttachment;

/**
 * Class SlackCommandHandler
 * @package FondBot\Drivers\Slack
 */
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

        if ($command->getTemplate() !== null)
        {
//            unset($payload['text']);

//            $payload['attachments'] = json_encode([ array_merge([
//                "text" =>  $command->getText(),
//                "callback_id" => $command->getTemplate()->getName(),
//                ],
//                $this->driver->getTemplateCompiler()->compile($command->getTemplate()))]);
            $payload = array_merge($payload, $this->driver->getTemplateCompiler()->compile($command->getTemplate()));
        }

        $payload   = array_merge($payload, [
            'token'   => $this->driver->getParameter('token')
        ]);
//        file_put_contents(path().'file.txt', json_encode($payload));
        $r = $this->driver->getHttp()->post($this->driver->getBaseUrl() . $this->driver->mapDriver('postMessage'), [
            'query' => $payload,
        ]);
        file_put_contents(path().'file.txt', $r->getBody()->getContents());

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
