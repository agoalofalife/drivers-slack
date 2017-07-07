<?php

declare(strict_types=1);

namespace FondBot\Drivers\Slack;

use GuzzleHttp\Client;
use FondBot\Templates\Location;
use FondBot\Templates\Attachment;
use FondBot\Drivers\ReceivedMessage;


class SlackReceivedMessage implements ReceivedMessage
{
    private $guzzle;
    private $payload;

    public function __construct(Client $guzzle, array $payload)
    {
        $this->guzzle  = $guzzle;
        $this->payload = $payload;
    }

    /**
     * Get text.
     *
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->payload['text'] ?? null;
    }

    /**
     * Get location.
     *
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        return null;
    }

    /**
     * Get attachment.
     *
     * @return Attachment|null
     */
    public function getAttachment(): ?Attachment
    {
        return null;
    }

    /**
     * Determine if message has attachment.
     *
     * @return bool
     */
    public function hasAttachment(): bool
    {
        // TODO: Implement hasAttachment() method.
    }

    /**
     * Determine if message has payload.
     *
     * @return bool
     */
    public function hasData(): bool
    {
        // TODO: Implement hasData() method.
    }

    /**
     * Get payload.
     *
     * @return string|null
     */
    public function getData(): ?string
    {
        // TODO: Implement getData() method.
    }
}