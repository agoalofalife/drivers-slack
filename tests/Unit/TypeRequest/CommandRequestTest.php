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

    public function setUp() : void
    {
        $this->request        = $this->mock(HttpRequest::class);
        $this->commandRequest = new CommandRequest($this->request);
    }

    public function test_getUserId() : void
    {
        $this->request->shouldReceive('getParameter')->once()->with('user_id')->andReturn($this->faker()->word);
        $this->commandRequest->getUserId();
    }

    public function test_getChatId() : void
    {
        $string = ['channel_id' => $this->faker()->word];
        $this->request->shouldReceive('getParameters')->once()->andReturn($string);
        $this->commandRequest->getChatId();
    }

    public function test_verifyRequest() : void
    {
        $token   = $this->faker()->word;
        $driver  = $this->mock(SlackDriver::class);

        $this->request->shouldReceive('getParameter')->once()->with('token')->andReturn($token);
        $driver->shouldReceive('getParameter')->once()->with('verify_token')->andReturn($token);
        $this->commandRequest->verifyRequest($driver);
    }

    public function test_verifyRequest_exception() : void
    {
        $driver  = $this->mock(SlackDriver::class);

        $this->request->shouldReceive('getParameter')->once()->with('token');
        $driver->shouldReceive('getParameter')->once()->with('verify_token')->andReturn('some');
        $this->expectException(InvalidRequest::class);
        $this->expectExceptionMessage('Invalid verify token');
        $this->commandRequest->verifyRequest($driver);
    }

    public function test_getText() : void
    {
        $this->request->shouldReceive('getParameter')->with('command')->once()->andReturn($this->faker()->word);
        $this->commandRequest->getText();
    }

    public function test_getLocation() : void
    {
        $this->assertNull($this->commandRequest->getLocation());
    }

    public function test_hasAttachment() : void
    {
        $this->assertFalse($this->commandRequest->hasAttachment());
    }

    public function test_getAttachment() : void
    {
        $this->assertNull($this->commandRequest->getAttachment());
    }

    public function test_hasData() : void
    {
        $this->request->shouldReceive('getParameter')->with('text')->once()->andReturn($this->faker()->word);
        $this->assertTrue($this->commandRequest->hasData());
    }

    public function test_getData()
    {
        $string = $this->faker()->word;
        $this->request->shouldReceive('getParameter')->with('text')->once()->andReturn($string);
        $this->assertEquals($this->commandRequest->getData(), $string);
    }
}