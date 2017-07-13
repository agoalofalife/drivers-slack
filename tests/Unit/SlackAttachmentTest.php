<?php

declare(strict_types=1);

namespace Tests\Unit;
use FondBot\Drivers\Slack\SlackAttachment;
use FondBot\Templates\Attachment;
use Tests\TestCase;

/**
 * Class SlackAttachmentTest
 * @package Tests\Unit
 */
class SlackAttachmentTest extends TestCase
{
    /**
     * @var Attachment
     */
    protected $attachment;
    protected $innerAttach;

    public function setUp() : void
    {
        $this->attachment = new SlackAttachment();
        $this->innerAttach = $this->faker()->words;
        $test = ['attachments' => $this->innerAttach];

        $this->attachment->setMetadata($test);
    }

    public function test_setMetadata() : void
    {
        $this->assertEquals(['attachments' => json_encode([$this->innerAttach])], $this->attachment->getMetadata());
    }

    public function test_getMetadata() : void
    {
        $this->assertInternalType('array', $this->attachment->getMetadata());
    }
}