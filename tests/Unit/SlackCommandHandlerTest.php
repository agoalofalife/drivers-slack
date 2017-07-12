<?php
declare(strict_types=1);

namespace Tests\Unit;

use FondBot\Drivers\Slack\SlackCommandHandler;
use Tests\TestCase;
use FondBot\Drivers\Driver;
use GuzzleHttp\Client;
use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Contracts\Template;
use FondBot\Drivers\TemplateCompiler;
use FondBot\Drivers\Commands\SendMessage;

class SlackCommandHandlerTest extends TestCase
{

    public function test_handleSendMessage(): void
    {
        $guzzle = $this->mock(Client::class);
        $chat = $this->mock(Chat::class);
        $user = $this->mock(User::class);
        $text = $this->faker()->text;
        $template = $this->mock(Template::class);
        $templateCompiler = $this->mock(TemplateCompiler::class);
        $driver = $this->mock(Driver::class);

        $command = new SendMessage($chat, $user, $text, $template);

        $chat->shouldReceive('getId')->andReturn('foo')->once();
        $driver->shouldReceive('getTemplateCompiler')->andReturn($templateCompiler)->once();
        $templateCompiler->shouldReceive('compile')->with($template)->andReturn('template payload')->once();
        $driver->shouldReceive('getBaseUrl')->andReturn('https://slack.com/api/')->once();

        $payload = [
            'chat_id' => 'foo',
            'text' => $text,
            'reply_markup' => 'template payload',
        ];

        $driver->shouldReceive('post')->with('https://slack.com/api/', ['json' => $payload])->once();

        (new SlackCommandHandler($driver))->handle($command);
    }

}