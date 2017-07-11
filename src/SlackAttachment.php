<?php

declare(strict_types=1);

namespace FondBot\Drivers\Slack;

use FondBot\Templates\Attachment;

class SlackAttachment extends Attachment
{
    private const name = 'attachments';
    private $parameters;

    /**
     * @param array $parameters
     * @return Attachment
     */
    public function setMetadata(array $parameters) : Attachment
    {
        $this->parameters[self::name] = json_encode([$parameters]);
        return $this;
    }

    /**
     * @return array
     */
    public function getMetadata() : array
    {
        return $this->parameters;
    }
}
