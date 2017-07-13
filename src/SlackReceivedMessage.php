<?php

declare(strict_types=1);

namespace FondBot\Drivers\Slack;

use FondBot\Drivers\Slack\Contracts\TypeRequest;
use GuzzleHttp\Client;
use FondBot\Templates\Location;
use FondBot\Templates\Attachment;
use FondBot\Drivers\ReceivedMessage;


/**
 * Class SlackReceivedMessage
 *
 * @package FondBot\Drivers\Slack
 */
class SlackReceivedMessage implements ReceivedMessage
{
    private $guzzle;

    /**
     * @var TypeRequest
     */
    private $payload;

    /**
     * SlackReceivedMessage constructor.
     *
     * @param Client      $guzzle
     * @param TypeRequest $payload
     */
    public function __construct(Client $guzzle, TypeRequest $payload)
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
        return $this->payload->getText();
    }

    /**
     * Get location.
     *
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        return $this->payload->getLocation();
    }

    /**
     * Get attachment.
     *
     * @return Attachment|null
     */
    public function getAttachment(): ?Attachment
    {
        return $this->payload->getAttachment();
    }

    /**
     * Determine if message has attachment.
     *
     * @return bool
     */
    public function hasAttachment(): bool
    {
        return $this->payload->hasAttachment();
    }

    /**
     * Determine if message has payload.
     *
     * @return bool
     */
    public function hasData(): bool
    {
        return $this->payload->hasData();
    }

    /**
     * Get payload.
     *
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->payload->getData();
    }
}