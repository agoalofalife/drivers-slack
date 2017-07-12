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

    public function setUp()
    {
        $this->typeRequest = $this->mock(TypeRequest::class);
        $this->receive     = new SlackReceivedMessage($this->guzzle(), $this->typeRequest);
    }

    public function test_getText()
    {
        $this->typeRequest->shouldReceive('getText')->once()->andReturn(\Mockery::type('string'));
        $this->receive->getText();
    }

    public function test_getLocation()
    {
        $this->assertNull($this->receive->getLocation());
    }

    public function test_getAttachment()
    {
        $this->assertNull($this->receive->getAttachment());
    }
}