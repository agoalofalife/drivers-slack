<?php
declare(strict_types=1);
namespace FondBot\Drivers\Slack\Templates;

use FondBot\Contracts\Template;
use FondBot\Contracts\Arrayable;


/**
 * Class RequestSelect
 *
 * @package FondBot\Drivers\Slack\Templates
 */
class RequestSelect implements Template, Arrayable
{
    /**
     * @var array
     */
    private $options = [];

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            "response_type" => "in_channel",
            'attachments' => json_encode([
                [
                "text"=> "Choose a game to play",
                'type' => 'template',
                "callback_id"=> "game_selection",
                'actions' => [
                    [
                    "name"=>"games_list",
                    "text"=> "Pick a game...",
                    "type"=> "select",
                        'options' => [
                            $this->options
                        ]
                    ]
                ]
                ]
            ])
        ];

            return [
                    "response_type"=> "in_channel",
                    "attachments" => json_encode([
                    [
            "text"=> "Choose a game to play",
            "fallback"=> "If you could read this message, you'd be choosing something fun to do right now.",
            "color"=> "#3AA3E3",
            "attachment_type"=> "default",
            "callback_id"=> "game_selection",
            "actions"=> [
                [
                    "name"=>"games_list",
                    "text"=> "Pick a game...",
                    "type"=> "select",
                    "options"=> [
                        [
                            "text"=> "Recommend",
                            "value"=> "recommend"
                        ],
                        [
                            "text"=> "Bridge",
                            "value"=> "bridge"
                        ],
                        [
                            "text"=> "Checkers",
                            "value"=> "checkers"
                        ],
                        [
                            "text"=> "Chess",
                            "value"=> "chess"
                        ],
                        [
                            "text"=> "Poker",
                            "value"=> "poker"
                        ],
                        [
                            "text"=>"Falken's Maze",
                            "value"=> "maze"
                        ],
                        [
                            "text"=> "Global Thermonuclear War",
                            "value"=> "war"
                        ]
                    ]
                ]
            ]
            ]
                    ])
                ];
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'RequestSelect';
    }

    /**
     * Add new option in select
     *
     * @param array $option
     * @return RequestSelect
     */
    public function addOption(array $option) : RequestSelect
    {
        $this->options[] = $option;
        file_put_contents(path().'file.txt', json_encode( $this->options));
        return $this;
    }
}