<?php
declare(strict_types=1);
namespace FondBot\Drivers\Slack\Templates;

use FondBot\Templates\Keyboard\Button;
use FondBot\Contracts\Arrayable;


class RequestConfirmButton extends Button implements Arrayable
{
    /**
     * @var string
     */
    private $textDenial = 'Yes';

    /**
     * @var string
     */
    private $textApproval = 'No';

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            "confirm"=> [
                "title" => $this->label,
                "text" => "Wouldn't you prefer a good game of chess?",
                "ok_text" => $this->textApproval,
                "dismiss_text" => $this->textDenial
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
     * Set text approval
     *
     * @param string $text
     * @return Button
     */
    public function setApproval(string $text = 'Yes') : Button
    {
        $this->textApproval = $text;
        return $this;
    }

    /**
     * Set text denial
     *
     * @param string $text
     * @return Button
     */
    public function setDenial(string $text = 'No') : Button
    {
        $this->textDenial = $text;
        return $this;
    }
}