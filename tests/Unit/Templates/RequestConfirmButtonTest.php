<?php
declare(strict_types=1);

namespace Tests\Unit\Templates;

use FondBot\Drivers\Slack\Templates\RequestConfirmButton;
use Tests\TestCase;

class RequestConfirmButtonTest extends TestCase
{
    /**
     * @var RequestConfirmButton
     */
    protected $requestConfirmButton;

    public function setUp()
    {
        $this->requestConfirmButton = new RequestConfirmButton();
    }

    public function test_toArray()
    {
        $source = $this->requestConfirmButton->toArray();
        $this->assertArrayHasKey('confirm', $source);
        $this->assertArrayHasKeys(['title', 'text', 'ok_text', 'dismiss_text'], $source['confirm']);
    }

    public function test_getName()
    {
        $this->assertEquals('RequestConfirmButton', $this->requestConfirmButton->getName());
    }

    public function test_setApproval()
    {
       $this->assertInstanceOf(RequestConfirmButton::class,  $this->requestConfirmButton->setApproval($this->faker()->word));
    }

    public function test_setDenial()
    {
        $this->assertInstanceOf(RequestConfirmButton::class,  $this->requestConfirmButton->setDenial($this->faker()->word));
    }

    public function test_setText()
    {
        $this->assertInstanceOf(RequestConfirmButton::class,  $this->requestConfirmButton->setText($this->faker()->word));
    }

}