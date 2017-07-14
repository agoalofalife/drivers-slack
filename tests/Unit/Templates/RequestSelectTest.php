<?php

declare(strict_types=1);

namespace Tests\Unit\Templates;

use FondBot\Drivers\Slack\Templates\RequestSelect;
use Tests\TestCase;

class RequestSelectTest extends TestCase
{
    /**
     * @var RequestSelect
     */
    protected $requestSelect;

    public function setUp() : void
    {
        $this->requestSelect = new RequestSelect();
    }

    public function test_getName() : void
    {
        $this->assertEquals('RequestSelect', $this->requestSelect->getName());
    }

    public function test_addOption() : void
    {
        $this->assertInstanceOf(RequestSelect::class, $this->requestSelect->addOption([]));
    }

    public function test_setText() : void
    {
        $this->assertInstanceOf(RequestSelect::class, $this->requestSelect->setText($this->faker()->word));
    }

    public function test_setPlaceholder() : void
    {
        $this->assertInstanceOf(RequestSelect::class, $this->requestSelect->setPlaceholder($this->faker()->word));
    }

    public function test_setName() : void
    {
        $this->assertInstanceOf(RequestSelect::class, $this->requestSelect->setName($this->faker()->word));
    }
}