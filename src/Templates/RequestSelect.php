<?php
declare(strict_types=1);
namespace FondBot\Drivers\Slack\Templates;

use FondBot\Contracts\Template;
use FondBot\Contracts\Arrayable;


/**
 * Class RequestSelect
 *
 * @package FondBot\Drivers\Slack\Templates
 */
class RequestSelect implements Template, Arrayable
{

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_merge([
            "name"=> $this->label,
            "text"=> $this->label,
            "type"=> "button",
            "value"=> $this->activator ?? $this->label,
            "style"=> $this->style,
        ]);
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'RequestSelect';
    }
}