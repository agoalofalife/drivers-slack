<?php
declare(strict_types=1);
namespace FondBot\Drivers\Slack\Templates;

use FondBot\Templates\Keyboard\Button;
use FondBot\Contracts\Arrayable;

/**
 * Class RequestButton
 *
 * @package FondBot\Drivers\Slack\Templates
 */
class RequestButton extends Button implements Arrayable
{

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
                    "name"=> $this->label,
                    "text"=> $this->label,
                    "type"=> "button",
                    "value"=> $this->label
              ];
    }
    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return get_class($this);
    }
}