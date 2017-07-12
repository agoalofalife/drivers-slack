<?php
declare(strict_types=1);

namespace Tests\Unit\TypeRequest;

use FondBot\Drivers\Exceptions\InvalidRequest;
use FondBot\Drivers\Slack\SlackDriver;
use FondBot\Drivers\Slack\TypeRequest\ResponseButtonRequest;
use Tests\TestCase;
use FondBot\Http\Request as HttpRequest;

/**
 * Class ResponseButtonRequestTest
 * @package Tests\Unit\TypeRequest
 */
class ResponseButtonRequestTest extends TestCase
{
    /**
     * @var ResponseButtonRequest
     */
    protected $responseButtonRequest;

    /**
     * @var HttpRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request        = $this->mock(HttpRequest::class);
        $this->responseButtonRequest = new ResponseButtonRequest($this->request);
    }

    public function test_getUserId()
    {
        $json = '{"user" : {"id" : "1DA"}}';
        $this->request->shouldReceive('getParameter')->once()->with('payload')->andReturn($json);
        $this->responseButtonRequest->getUserId();
    }

    public function test_getChatId()
    {
        $json = '{"channel" : {"id" : "1DA"}}';
        $this->request->shouldReceive('getParameter')->once()->with('payload')->andReturn($json);
        $this->responseButtonRequest->getChatId();
    }


    public function test_verifyRequest()
    {
        $json = '{"token" : "sx"}';
        $token   = $this->faker()->word;
        $driver  = $this->mock(SlackDriver::class);
        $this->request->shouldReceive('getParameter')->once()->with('payload')->andReturn($json);
        $driver->shouldReceive('getParameter')->once()->with('verify_token')->andReturn($token);
        $this->responseButtonRequest->verifyRequest($driver);
    }

    public function test_verifyRequest_exception()
    {
        $json    = '{"token" : "sx"}';
        $driver  = $this->mock(SlackDriver::class);
        $this->request->shouldReceive('getParameter')->once()->with('payload')->andReturn($json);
        $driver->shouldReceive('getParameter')->once()->with('verify_token');
        $this->expectException(InvalidRequest::class);
        $this->responseButtonRequest->verifyRequest($driver);
    }

    public function test_getText()
    {
        $driver  = $this->mock(SlackDriver::class);
        $json    = '{"actions" : [{ "value" : "as"}]}';
        $this->request->shouldReceive('getParameter')->once()->with('payload')->andReturn($json);
        $this->responseButtonRequest->getText($driver);
    }
}