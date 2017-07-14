<?php
declare(strict_types=1);

namespace Tests\Unit\Templates;

use FondBot\Drivers\Slack\Templates\RequestButton;
use FondBot\Drivers\Slack\Templates\RequestConfirmButton;
use Tests\TestCase;

class RequestButtonTest extends TestCase
{
    /**
     * @var RequestButton
     */
    protected $requestButton;

    public function setUp()
    {
        $this->requestButton = new RequestButton();
    }

    public function test_toArray()
    {
        $this->assertArrayHasKeys(['text', 'style', 'type', 'name', 'value'],   $this->requestButton->toArray());
    }

    public function test_getName()
    {
        $this->assertEquals('RequestButton', $this->requestButton->getName());
    }

    public function test_setActivator()
    {
        $this->assertInstanceOf(RequestButton::class,  $this->requestButton->setActivator($this->faker()->word));
    }

    public function test_setStyle()
    {
        $this->assertInstanceOf(RequestButton::class,  $this->requestButton->setStyle($this->faker()->word));
    }

    public function test_setConfirm()
    {
        $confirm = $this->mock(RequestConfirmButton::class);
        $this->assertInstanceOf(RequestButton::class, $this->requestButton->setConfirm($confirm));
    }

    public function test_setDescription()
    {
        $this->assertInstanceOf(RequestButton::class, $this->requestButton->setDescription($this->faker()->word));
    }
}