<?php
declare(strict_types=1);

namespace Tests\Unit;
use FondBot\Drivers\Slack\Contracts\TypeRequest;
use FondBot\Drivers\Slack\SlackReceivedMessage;
use Tests\TestCase;

/**
 * Class SlackReceivedMessageTest
 * @package Tests\Unit
 */
class SlackReceivedMessageTest extends TestCase
{
    protected $typeRequest;
    /**
     * @var SlackReceivedMessage
     */
    protected $receive;

    public function setUp() : void
    {
        $this->typeRequest = $this->mock(TypeRequest::class);
        $this->receive     = new SlackReceivedMessage($this->guzzle(), $this->typeRequest);
    }

    public function test_getText() : void
    {
        $this->typeRequest->shouldReceive('getText')->once()->andReturn(\Mockery::type('string'));
        $this->receive->getText();
    }

    public function test_getLocation() : void
    {
        $this->typeRequest->shouldReceive('getLocation')->once()->andReturn(null);
        $this->receive->getLocation();
    }

    public function test_getAttachment() : void
    {
        $this->typeRequest->shouldReceive('getAttachment')->once()->andReturn(null);
        $this->receive->getAttachment();
    }

    public function test_hasAttachment() : void
    {
        $this->typeRequest->shouldReceive('hasAttachment')->once()->andReturn($this->faker()->boolean);
        $this->receive->hasAttachment();
    }

    public function test_hasData() : void
    {
        $this->typeRequest->shouldReceive('hasData')->once()->andReturn($this->faker()->boolean);
        $this->receive->hasData();
    }

    public function test_getData() : void
    {
        $this->typeRequest->shouldReceive('getData')->once()->andReturn($this->faker()->word);
        $this->receive->getData();
    }

}