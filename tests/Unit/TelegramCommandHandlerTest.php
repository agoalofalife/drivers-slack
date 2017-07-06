<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use GuzzleHttp\Client;
use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Driver;
use FondBot\Contracts\Template;
use FondBot\Templates\Attachment;
use FondBot\Drivers\TemplateCompiler;
use FondBot\Drivers\Commands\SendMessage;
use FondBot\Drivers\Commands\SendAttachment;
use FondBot\Drivers\Telegram\SlackCommandHandler;

class TelegramCommandHandlerTest extends TestCase
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
        $driver->shouldReceive('getHttp')->andReturn($guzzle)->once();
        $driver->shouldReceive('getBaseUrl')->andReturn('http://telegram.api')->once();

        $payload = [
            'chat_id' => 'foo',
            'text' => $text,
            'reply_markup' => 'template payload',
        ];

        $guzzle->shouldReceive('post')->with('http://telegram.api/sendMessage', ['json' => $payload])->once();

        (new SlackCommandHandler($driver, $guzzle))->handle($command);
    }

    /**
     * @dataProvider attachmentTypes
     *
     * @param string $genericType
     */
    public function test_handleSendAttachment(string $genericType): void
    {
        switch ($genericType) {
            case Attachment::TYPE_IMAGE:
                $type = 'photo';
                $endpoint = 'sendPhoto';
                break;
            case Attachment::TYPE_AUDIO:
                $type = 'audio';
                $endpoint = 'sendAudio';
                break;
            case Attachment::TYPE_VIDEO:
                $type = 'video';
                $endpoint = 'sendVideo';
                break;
            default:
                $type = 'document';
                $endpoint = 'sendDocument';
                break;
        }

        $guzzle = $this->mock(Client::class);
        $chat = $this->mock(Chat::class);
        $user = $this->mock(User::class);
        $attachment = $this->mock(Attachment::class);
        $driver = $this->mock(Driver::class);
        $command = new SendAttachment($chat, $user, $attachment);

        $attachment->shouldReceive('getType')->andReturn($genericType)->once();
        $chat->shouldReceive('getId')->andReturn('foo')->once();
        $attachment->shouldReceive('getPath')->andReturn('https://fondbot.com/images/logo.png')->once();
        $driver->shouldReceive('getHttp')->andReturn($guzzle)->once();
        $driver->shouldReceive('getBaseUrl')->andReturn('http://telegram.api')->once();

        /* @noinspection PhpParamsInspection */
        $guzzle->shouldReceive('post')
            ->withArgs(function ($arg1, $arg2) use ($endpoint, $type) {
                if ($arg1 !== 'http://telegram.api/'.$endpoint) {
                    return false;
                }

                $multipart = $arg2['multipart'];
                if ($multipart[0]['name'] !== 'chat_id' || $multipart[0]['contents'] !== 'foo') {
                    return false;
                }

                if ($multipart[1]['name'] !== $type || !is_resource($multipart[1]['contents'])) {
                    return false;
                }

                return true;
            })
            ->once();

        (new SlackCommandHandler($driver, $guzzle))->handle($command);
    }

    public function attachmentTypes(): array
    {
        return collect(Attachment::possibleTypes())
            ->map(function ($type) {
                return [$type];
            })
            ->toArray();
    }
}
