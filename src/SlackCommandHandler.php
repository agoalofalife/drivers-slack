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
            unset($payload['text']);
            $payload['attachments'] = json_encode([array_merge([
                "text" =>  $command->getText(),
                "callback_id" => $command->getTemplate()->getName(),
                ], $this->driver->getTemplateCompiler()->compile($command->getTemplate()))]);
//                $r = json_encode([[
//            "text"=> "Choose a game to play",
//            "fallback"=> "You are unable to choose a game",
//            "callback_id"=> "wopr_game",
//            "color"=> "#3AA3E3",
//            "attachment_type"=> "default",
//            "actions"=> [
//                [
//                    "name"=> "game",
//                    "text"=> "Chess",
//                    "type"=> "button",
//                    "value"=> "recommend"
//                ],
//                [
//                    "name"=> "game",
//                    "text"=> "Falken's Maze",
//                    "type"=> "button",
//                    "value"=> "maze"
//                ],
//                [
//                    "name"=> "game",
//                    "text"=> "Thermonuclear War",
//                    "style"=> "danger",
//                    "type"=> "button",
//                    "value"=> "war",
//                    "confirm"=> [
//                    "title"=> "Are you sure?",
//                        "text"=> "Wouldn't you prefer a good game of chess?",
//                        "ok_text"=> "Yes",
//                        "dismiss_text"=> "No",
//                    ]
//                ]
//            ]
//    ]]);
        }
//        file_put_contents(path().'file.txt', $payload['attachments'] );
        $payload   = array_merge($payload, [
            'token'   => $this->driver->getParameter('token')
        ]);

        $this->driver->getHttp()->post($this->driver->getBaseUrl() . $this->driver->mapDriver('postMessage'), [
            'query' => $payload,
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
