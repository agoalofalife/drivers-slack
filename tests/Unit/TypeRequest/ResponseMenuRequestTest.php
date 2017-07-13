<?php
declare(strict_types=1);

namespace Tests\Unit\TypeRequest;

use FondBot\Drivers\Exceptions\InvalidRequest;
use FondBot\Drivers\Slack\SlackDriver;
use FondBot\Drivers\Slack\TypeRequest\ResponseMenuRequest;
use Tests\TestCase;
use FondBot\Http\Request as HttpRequest;

/**
 * Class ResponseMenuRequest
 * @package Tests\Unit\TypeRequest
 */
class ResponseMenuRequestTest extends TestCase
{
    /**
     * @var ResponseMenuRequest
     */
    protected $responseMenuRequest;

    /**
     * @var HttpRequest
     */
    protected $request;

    public function setUp() : void
    {
        $this->request        = $this->mock(HttpRequest::class);
        $this->responseMenuRequest = new ResponseMenuRequest($this->request);
    }

    public function test_getUserId() : void
    {
        $json = '{"user" : {"id" : "1DA"}}';
        $this->request->shouldReceive('getParameter')->once()->with('payload')->andReturn($json);
        $this->responseMenuRequest->getUserId();
    }

    public function test_getChatId() : void
    {
        $json = '{"channel" : {"id" : "1DA"}}';
        $this->request->shouldReceive('getParameter')->once()->with('payload')->andReturn($json);
        $this->responseMenuRequest->getChatId();
    }

    public function test_verifyRequest() : void
    {
        $json = '{"token" : "sx"}';
        $token   = $this->faker()->word;
        $driver  = $this->mock(SlackDriver::class);
        $this->request->shouldReceive('getParameter')->once()->with('payload')->andReturn($json);
        $driver->shouldReceive('getParameter')->once()->with('verify_token')->andReturn($token);
        $this->responseMenuRequest->verifyRequest($driver);
    }

    public function test_verifyRequest_exception() : void
    {
        $json    = '{"token" : "sx"}';
        $driver  = $this->mock(SlackDriver::class);
        $this->request->shouldReceive('getParameter')->once()->with('payload')->andReturn($json);
        $driver->shouldReceive('getParameter')->once()->with('verify_token');
        $this->expectException(InvalidRequest::class);
        $this->responseMenuRequest->verifyRequest($driver);
    }

    public function test_getText() : void
    {
        $driver  = $this->mock(SlackDriver::class);
        $json    = '{"actions" : [{ "selected_options" : [{"value" : "test" }]}]}';
        $this->request->shouldReceive('getParameter')->once()->with('payload')->andReturn($json);
        $this->responseMenuRequest->getText($driver);
    }

    public function test_getLocation() : void
    {
        $this->assertNull($this->responseMenuRequest->getLocation());
    }

    public function test_hasAttachment() : void
    {
        $this->assertFalse($this->responseMenuRequest->hasAttachment());
    }

    public function test_getAttachment() : void
    {
        $this->assertNull($this->responseMenuRequest->getAttachment());
    }

    public function test_hasData() : void
    {
        $json    = '{"token" : "sx"}';
        $this->request->shouldReceive('getParameter')->once()->with('payload')->andReturn($json);
        $this->assertFalse($this->responseMenuRequest->hasData());
    }

    public function test_getData()
    {
        $json    = '{"actions" : "sx"}';
        $this->request->shouldReceive('getParameter')->once()->with('payload')->andReturn($json);
        $this->responseMenuRequest->getData();
    }
}