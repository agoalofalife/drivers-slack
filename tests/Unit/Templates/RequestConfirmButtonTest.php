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
}