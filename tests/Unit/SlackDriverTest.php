<?php

declare(strict_types=1);

namespace Tests\Unit;

use FondBot\Drivers\Chat;
use FondBot\Drivers\Exceptions\InvalidRequest;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Drivers\Slack\SlackCommandHandler;
use FondBot\Drivers\Slack\SlackDriver;
use FondBot\Drivers\Slack\SlackTemplateCompiler;
use Tests\TestCase;
use GuzzleHttp\Client;
use FondBot\Helpers\Str;
use FondBot\Drivers\User;
use FondBot\Http\Request;

/**
 * Class SlackDriverTest
 *
 * @package Tests\Unit
 */
class SlackDriverTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->guzzle = $this->mock(Client::class);

        $this->driver = new SlackDriver($this->guzzle);
        $this->driver->fill($this->parameters = ['verify_token' => $this->getToken()], new Request($this->factoryTypeRequest(), []));
    }

    public function test_getBaseUrl() : void
    {
        $this->assertEquals('https://slack.com/api/', $this->driver->getBaseUrl());
    }

    public function test_verifyRequest() : void
    {
        $types = $this->factoryTypeRequest(true);

        foreach ($types as $type)
        {
            $this->driver->fill($this->parameters = ['verify_token' => $this->getToken()], new Request($type, []));
            $this->driver->verifyRequest();
        }
    }

    public function test_verifyRequest_exception() : void
    {
        $this->driver->fill($this->parameters = ['verify_token' => $this->getToken()], new Request([], []));
        $this->expectException(InvalidRequest::class);
        $this->expectExceptionMessage('Invalid type request');
        $this->driver->verifyRequest();
    }

    public function test_getUser() : void
    {
        $this->guzzle->shouldReceive('get')
                     ->with($this->driver->getBaseUrl() . $this->driver
                     ->mapDriver('infoAboutUser'), \Mockery::type('array'))
                     ->once()->andReturnSelf();

        $this->guzzle->shouldReceive('getBody')->once()->andReturn($this->factoryUserInfo(['ok' => true]));

        $this->driver->verifyRequest();
        $this->assertInstanceOf(User::class, $this->driver->getUser());
    }

    public function test_getUser_Exception() : void
    {
        $error = $this->faker()->text();
        $this->guzzle->shouldReceive('get')
            ->with($this->driver->getBaseUrl() . $this->driver
                    ->mapDriver('infoAboutUser'), \Mockery::type('array'))
            ->once()->andReturnSelf();

        $this->guzzle->shouldReceive('getBody')->once()->andReturn($this->factoryUserInfo(['ok' => false, 'error' => $error]));
        $this->driver->verifyRequest();
        $this->expectException(\Exception::class);
        $this->driver->getUser();
    }

    public function test_getMessage() : void
    {
        $this->driver->verifyRequest();
        $this->assertInstanceOf(ReceivedMessage::class,  $this->driver->getMessage());
    }

    public function test_mapDriver() : void
    {
        $revise = ['infoAboutUser' => 'users.info', 'postMessage' => 'chat.postMessage'];
        $name   = $this->faker()->randomElement(['infoAboutUser', 'postMessage']);

        $this->assertEquals($revise[$name], $this->driver->mapDriver($name));
    }

    public function test_mapDriver_Exception() : void
    {
        $this->expectException(\Exception::class);
        $this->driver->mapDriver($this->faker()->name);
    }

    public function test_getTemplateCompiler() : void
    {
        $this->assertInstanceOf(SlackTemplateCompiler::class, $this->driver->getTemplateCompiler());
    }

    public function test_getCommandHandler() : void
    {
        $this->assertInstanceOf(SlackCommandHandler::class, $this->driver->getCommandHandler());
    }

    public function test_getChat() : void
    {
        $this->driver->verifyRequest();
        $this->assertInstanceOf(Chat::class, $this->driver->getChat());
    }

    public function test_isVerificationRequest() : void
    {
        $this->driver->fill($this->parameters = ['token' => Str::random()], new Request(['type' => 'url_verification'], []));
        $this->assertTrue($this->driver->isVerificationRequest());
    }

    public function test_verifyWebhook() : void
    {
        $token = $this->faker()->uuid;
        $challenge = $this->faker()->word;

        $this->driver->fill($this->parameters = ['verify_token' => $token], new Request(['token' => $token, 'challenge' => $challenge], []));

        $this->assertEquals($challenge, $this->driver->verifyWebhook());
    }

    public function test_verifyWebhook_exception() : void
    {
        $token = $this->faker()->uuid;
        $challenge = $this->faker()->word;

        $this->driver->fill($this->parameters = ['verify_token' => ''], new Request(['token' => $token, 'challenge' => $challenge], []));
        $this->expectException(InvalidRequest::class);
        $this->assertEquals($challenge, $this->driver->verifyWebhook());
    }
}
