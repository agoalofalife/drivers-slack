<?php

declare(strict_types=1);

namespace FondBot\Drivers\Slack;

use FondBot\Drivers\Slack\Contracts\Attachment as ContractAttachment;
use FondBot\Templates\Attachment;

class SlackAttachment extends Attachment implements ContractAttachment
{
    private const name = 'attachments';
    private $parameters = [self::name];

    public function setMetadata(array $parameters) : Attachment
    {
        $this->parameters[self::name] = json_encode($parameters);
        return $this;
    }

    public function getMetadata() : array
    {
        return $this->parameters;
    }
}
