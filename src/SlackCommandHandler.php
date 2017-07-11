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
//        $payload = [
//            'channel' => $command->getChat()->getId(),
//            'text' => 'sx',
//            'token' => $this->driver->getParameter('token'),
//            'attachments' =>
//                json_encode([
//               [
//                   "text" => "And here’s an attachment!",
////                   "title" =>  "Slack API Documentation",
//                    "image_url" => 'http://img0.marimedia.ru/static/834978c33e059ecc622f95ee29a18f87/thumbs/media/articles/404/ab114d1441dd7aa82a8c322e4290a5d0.jpg/660x660.jpg',
////                   "author_link" =>  "http://flickr.com/bobby/",
//               ]
//            ]),
//        ];
        $payload = [
            'channel' => $command->getChat()->getId(),
//            'text' => 'sx',
            'token'   => $this->driver->getParameter('token'),
        ];

        $payload =   array_merge($payload,  $command->getAttachment()->getMetadata());
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
