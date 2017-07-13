<?php
declare(strict_types=1);

namespace Tests\Unit;

use FondBot\Drivers\Commands\SendAttachment;
use FondBot\Drivers\Commands\SendRequest;
use FondBot\Drivers\Slack\SlackCommandHandler;
use FondBot\Drivers\Slack\SlackTemplateCompiler;
use FondBot\Templates\Attachment;
use Tests\TestCase;
use FondBot\Drivers\Driver;
use GuzzleHttp\Client;
use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Contracts\Template;
use FondBot\Drivers\Commands\SendMessage;

/**
 * Class SlackCommandHandlerTest
 * @package Tests\Unit
 */
class SlackCommandHandlerTest extends TestCase
{
    protected $text;
    protected $baseUrl;
    protected $guzzle;
    protected $chat;
    protected $user;
    protected $driver;

    public function setUp() : void
    {
        $this->text    = $this->faker()->text;
        $this->baseUrl = 'https://slack.com/api/';
        $this->guzzle  = $this->mock(Client::class);
        $this->chat    = $this->mock(Chat::class);
        $this->user    = $this->mock(User::class);
        $this->driver  = $this->mock(Driver::class);
    }

    public function test_handleSendMessage_with_Tempalate(): void
    {
        $template = $this->mock(Template::class);
        $templateCompiler = $this->mock(SlackTemplateCompiler::class);
        $command  = new SendMessage($this->chat, $this->user, $this->text, $template);

        $nameMethod = $this->faker()->word;
        $this->chat->shouldReceive('getId')->andReturn('foo')->once();
        $this->driver->shouldReceive('getParameter')->with('token')->andReturn($this->faker()->randomAscii)->once();
        $this->driver->shouldReceive('getHttp')->once()->andReturn($this->guzzle);
        $this->driver->shouldReceive('mapDriver')->once()->with('postMessage')->andReturn($this->text);
        $this->driver->shouldReceive('getBaseUrl')->andReturn($this->baseUrl )->once();
        $this->driver->shouldReceive('getTemplateCompiler')->andReturn($templateCompiler)->once();
        $templateCompiler->shouldReceive('compile')->once()->andReturn([]);
        $template->shouldReceive('getName')->once()->andReturn($nameMethod);

        $this->guzzle->shouldReceive('post')->once()->with($this->baseUrl  . $this->text, \Mockery::type('array'));
        (new SlackCommandHandler($this->driver))->handle($command);
    }

    public function test_handleSendAttachment() : void
    {
        $attachment = $this->mock(Attachment::class);
        $command    = new SendAttachment($this->chat, $this->user, $attachment);

        $attachment->shouldReceive('getMetadata')->once();
        $this->chat->shouldReceive('getId')->andReturn('foo')->once();
        $this->driver->shouldReceive('getHttp')->once()->andReturn($this->guzzle);
        $this->driver->shouldReceive('getParameter')->with('token')->andReturn($this->faker()->randomAscii)->once();
        $this->driver->shouldReceive('getBaseUrl')->andReturn($this->baseUrl )->once();
        $this->driver->shouldReceive('mapDriver')->once()->with('postMessage')->andReturn($this->text);

        $this->guzzle->shouldReceive('post')->once()->with($this->baseUrl  . $this->text, \Mockery::type('array'))->andReturnSelf();
        $this->guzzle->shouldReceive('getBody')->once()->andReturnSelf();
        $this->guzzle->shouldReceive('getContents')->once();

        (new SlackCommandHandler($this->driver))->handle($command);
    }

    public function test_handleSendRequest() : void
    {
        $command    = new SendRequest($this->chat, $this->user, 'some');
        (new SlackCommandHandler($this->driver))->handle($command);
    }
}