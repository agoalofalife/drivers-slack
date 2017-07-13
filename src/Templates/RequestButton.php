<?php
declare(strict_types=1);
namespace FondBot\Drivers\Slack\Templates;

use FondBot\Templates\Keyboard\Button;
use FondBot\Contracts\Arrayable;

/**
 * Class RequestButton
 *
 * @package FondBot\Drivers\Slack\Templates
 */
class RequestButton extends Button implements Arrayable
{

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return
            [ "actions"=> [
                [
                    "name"=> "game",
                    "text"=> "Chess",
                    "type"=> "button",
                    "value"=> "recommend"
                ],
                [
                    "name"=> "game",
                    "text"=> "Falken's Maze",
                    "type"=> "button",
                    "value"=> "maze"
                ],
                [
                    "name"=> "game",
                    "text"=> "Thermonuclear War",
                    "style"=> "danger",
                    "type"=> "button",
                    "value"=> "war",
                    "confirm"=> [
                    "title"=> "Are you sure?",
                        "text"=> "Wouldn't you prefer a good game of chess?",
                        "ok_text"=> "Yes",
                        "dismiss_text"=> "No",
                    ]
                ]
            ] ];
    }
    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'RequestButton';
    }
}