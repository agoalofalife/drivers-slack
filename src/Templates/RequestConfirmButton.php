<?php
declare(strict_types=1);
namespace FondBot\Drivers\Slack\Templates;

use FondBot\Templates\Keyboard\Button;
use FondBot\Contracts\Arrayable;

/**
 * Class RequestConfirmButton
 *
 * @package FondBot\Drivers\Slack\Templates
 */
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
     * @var string
     */
    private $text = 'You are sure?';

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            "confirm"=> [
                "title" => $this->label ?? 'title',
                "text" => $this->text,
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
       return 'RequestConfirmButton';
    }

    /**
     * Set text approval
     *
     * @param string $text
     * @return RequestConfirmButton
     */
    public function setApproval(string $text = 'Yes') : RequestConfirmButton
    {
        $this->textApproval = $text;
        return $this;
    }

    /**
     * Set text denial
     *
     * @param string $text
     * @return RequestConfirmButton
     */
    public function setDenial(string $text = 'No') : RequestConfirmButton
    {
        $this->textDenial = $text;
        return $this;
    }

    /**
     * Set text modal window
     *
     * @param string $text
     * @return RequestConfirmButton
     */
    public function setText(string $text = 'You are sure?') : RequestConfirmButton
    {
        $this->text = $text;
        return $this;
    }
}