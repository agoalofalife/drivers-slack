<?php

declare(strict_types=1);

namespace Tests\Unit\TypeRequest;

use FondBot\Drivers\Exceptions\InvalidRequest;
use FondBot\Drivers\Slack\SlackDriver;
use FondBot\Drivers\Slack\TypeRequest\CommandRequest;
use Tests\TestCase;
use FondBot\Http\Request as HttpRequest;

/**
 * Class CommandRequestTest
 * @package Tests\Unit\TypeRequest
 */
class CommandRequestTest extends TestCase
{
    /**
     * @var CommandRequest
     */
    protected $commandRequest;

    /**
     * @var HttpRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request        = $this->mock(HttpRequest::class);
        $this->commandRequest = new CommandRequest($this->request);
    }

    public function test_getUserId()
    {
        $this->request->shouldReceive('getParameter')->once()->with('user_id')->andReturn($this->faker()->word);
        $this->commandRequest->getUserId();
    }

    public function test_getChatId()
    {
        $string = ['channel_id' => $this->faker()->word];
        $this->request->shouldReceive('getParameters')->once()->andReturn($string);
        $this->commandRequest->getChatId();
    }

    public function test_verifyRequest()
    {
        $token   = $this->faker()->word;
        $driver  = $this->mock(SlackDriver::class);

        $this->request->shouldReceive('getParameter')->once()->with('token')->andReturn($token);
        $driver->shouldReceive('getParameter')->once()->with('verify_token')->andReturn($token);
        $this->commandRequest->verifyRequest($driver);
    }

    public function test_verifyRequest_exception()
    {
        $driver  = $this->mock(SlackDriver::class);

        $this->request->shouldReceive('getParameter')->once()->with('token');
        $driver->shouldReceive('getParameter')->once()->with('verify_token')->andReturn('some');
        $this->expectException(InvalidRequest::class);
        $this->expectExceptionMessage('Invalid verify token');
        $this->commandRequest->verifyRequest($driver);
    }

    public function test_getText()
    {
        $this->request->shouldReceive('getParameter')->with('command')->once()->andReturn($this->faker()->word);
        $this->commandRequest->getText();
    }
}