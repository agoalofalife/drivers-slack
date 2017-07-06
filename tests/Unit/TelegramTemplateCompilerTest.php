<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use FondBot\Templates\Keyboard;
use FondBot\Templates\Keyboard\UrlButton;
use FondBot\Templates\Keyboard\ReplyButton;
use FondBot\Templates\Keyboard\PayloadButton;
use FondBot\Drivers\Telegram\SlackTemplateCompiler;
use FondBot\Drivers\Telegram\Templates\RequestContactButton;
use FondBot\Drivers\Telegram\Templates\RequestLocationButton;

class TelegramTemplateCompilerTest extends TestCase
{
    public function test_reply_keyboard(): void
    {
        $keyboard = (new Keyboard)
            ->addButton(
                (new ReplyButton)->setLabel('Reply')
            )
            ->addButton(
                (new RequestContactButton)->setLabel('Contact')
            )
            ->addButton(
                (new RequestLocationButton)->setLabel('Location')
            );

        $result = (new SlackTemplateCompiler)->compile($keyboard);

        $expected = [
            'keyboard' => [
                ['Reply'],
                [['text' => 'Contact', 'request_contact' => true]],
                [['text' => 'Location', 'request_location' => true]],
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ];

        $this->assertSame($expected, $result);
    }

    public function test_inline_keyboard(): void
    {
        $keyboard = (new Keyboard)
            ->addButton(
                (new PayloadButton)->setLabel('Payload')->setPayload('payload')
            )
            ->addButton(
                (new UrlButton)->setLabel('Url')->setUrl('http://foo')->setParameters(['foo' => 'bar'])
            );

        $result = (new SlackTemplateCompiler)->compile($keyboard);

        $expected = [
            'inline_keyboard' => [
                [['text' => 'Payload', 'callback_data' => 'payload']],
                [['text' => 'Url', 'url' => 'http://foo']],
            ],
        ];

        $this->assertSame($expected, $result);
    }
}
