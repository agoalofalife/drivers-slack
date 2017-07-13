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
     * @var string
     */
    private $activator;

    /**
     * @var string
     */
    private $style;
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
                    "value"=> $this->activator ?? $this->label,
                    "style"=> "danger",
                    "confirm"=> [
                   "title"=> "Are you sure?",
                        "text"=> "Wouldn't you prefer a good game of chess?",
                        "ok_text"=> "Yes",
                        "dismiss_text"=>"No"
                    ]
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

    /**
     * Set activator
     *
     * @param string|null $activator
     * @return Button
     */
    public function setActivator(string $activator = null) : Button
    {
        $this->activator = $activator;
        return $this;
    }

    /**
     * @param string|null $style
     * @return Button
     */
    public function setStyle(string $style = null) : Button
    {
        $this->style = $style;
        return $this;
    }
}