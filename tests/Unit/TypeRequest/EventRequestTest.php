<?php
declare(strict_types=1);

namespace Tests\Unit\TypeRequest;
use FondBot\Drivers\Exceptions\InvalidRequest;
use FondBot\Drivers\Slack\SlackDriver;
use FondBot\Drivers\Slack\TypeRequest\EventRequest;
use Tests\TestCase;
use FondBot\Http\Request as HttpRequest;

/**
 * Class EventRequestTest
 * @package Tests\Unit\TypeRequest
 */
class EventRequestTest extends TestCase
{
    /**
     * @var EventRequest
     */
    protected $eventRequest;

    /**
     * @var HttpRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request        = $this->mock(HttpRequest::class);
        $this->eventRequest = new EventRequest($this->request);
    }

    public function test_getUserId()
    {
        $this->request->shouldReceive('getParameter')->once()->with('event.user')->andReturn($this->faker()->word);
        $this->eventRequest->getUserId();
    }

    public function test_getChatId()
    {
        $string = ['event' => [
            'channel' => $this->faker()->word
        ]];
        $this->request->shouldReceive('getParameters')->once()->andReturn($string);
        $this->eventRequest->getChatId();
    }

    public function test_verifyRequest()
    {
        $token   = $this->faker()->word;
        $driver  = $this->mock(SlackDriver::class);

        $this->request->shouldReceive('getParameter')->once()->with('token')->andReturn($token);
        $driver->shouldReceive('getParameter')->once()->with('verify_token')->andReturn($token);
        $this->eventRequest->verifyRequest($driver);
    }

    public function test_verifyRequest_exception()
    {
        $driver  = $this->mock(SlackDriver::class);

        $this->request->shouldReceive('getParameter')->once()->with('token');
        $driver->shouldReceive('getParameter')->once()->with('verify_token')->andReturn('some');
        $this->expectException(InvalidRequest::class);
        $this->expectExceptionMessage('Invalid verify token');
        $this->eventRequest->verifyRequest($driver);
    }

    public function test_getText()
    {
        $this->request->shouldReceive('getParameter')->with('event.text')->once()->andReturn($this->faker()->word);
        $this->eventRequest->getText();
    }
}