<?php
declare(strict_types=1);

namespace Tests\Unit\TypeRequest;
use FondBot\Drivers\Exceptions\InvalidRequest;
use FondBot\Drivers\Slack\SlackDriver;
use FondBot\Drivers\Slack\TypeRequest\EventRequest;
use FondBot\Templates\Attachment;
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

    public function setUp() : void
    {
        $this->request        = $this->mock(HttpRequest::class);
        $this->eventRequest = new EventRequest($this->request);
    }

    public function test_getUserId() : void
    {
        $this->request->shouldReceive('getParameter')->once()->with('event.user')->andReturn($this->faker()->word);
        $this->eventRequest->getUserId();
    }

    public function test_getChatId() : void
    {
        $string = ['event' => [
            'channel' => $this->faker()->word
        ]];
        $this->request->shouldReceive('getParameters')->once()->andReturn($string);
        $this->eventRequest->getChatId();
    }

    public function test_verifyRequest() : void
    {
        $token   = $this->faker()->word;
        $driver  = $this->mock(SlackDriver::class);

        $this->request->shouldReceive('getParameter')->once()->with('token')->andReturn($token);
        $driver->shouldReceive('getParameter')->once()->with('verify_token')->andReturn($token);
        $this->eventRequest->verifyRequest($driver);
    }

    public function test_verifyRequest_exception() : void
    {
        $driver  = $this->mock(SlackDriver::class);

        $this->request->shouldReceive('getParameter')->once()->with('token');
        $driver->shouldReceive('getParameter')->once()->with('verify_token')->andReturn('some');
        $this->expectException(InvalidRequest::class);
        $this->expectExceptionMessage('Invalid verify token');
        $this->eventRequest->verifyRequest($driver);
    }

    public function test_getText_exist_attachment() : void
    {
        $this->request->shouldReceive('getParameter')->with('event.file.title')->once()->andReturn($this->faker()->word);
        $this->request->shouldReceive('hasParameters')->with('event.file')->once()->andReturn(true);
        $this->eventRequest->getText();
    }

    public function test_getText_is_not_exist_attachment() : void
    {
        $this->request->shouldReceive('hasParameters')->with('event.file')->once()->andReturn(false);
        $this->request->shouldReceive('getParameter')->with('event.text')->once()->andReturn($this->faker()->word);
        $this->eventRequest->getText();
    }

    public function test_getLocation() : void
    {
        $this->assertNull($this->eventRequest->getLocation());
    }

    public function test_hasAttachment() : void
    {
        $this->request->shouldReceive('hasParameters')->with('event.file')->once()->andReturn($this->faker()->boolean());
        $this->eventRequest->hasAttachment();
    }

    public function test_getAttachment() : void
    {
        $this->request->shouldReceive('getParameter')->with('event.file')->once()->andReturn([]);
        $this->assertInstanceOf(Attachment::class,  $this->eventRequest->getAttachment());
    }

    public function test_hasData() : void
    {
        $this->request->shouldReceive('hasParameters')->with('event.text')->once()->andReturn($this->faker()->boolean());
        $this->eventRequest->hasData();
    }

    public function test_getData() : void
    {
        $this->request->shouldReceive('getParameter')->with('event.text')->once()->andReturn($this->faker()->word);
        $this->eventRequest->getData();
    }
}