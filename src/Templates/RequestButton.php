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
    private $style = 'default';

    /**
     * @var RequestConfirmButton
     */
    private $buttonConfirm = null;

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $prepare =   array_merge([
            "callback_id" => bin2hex(random_bytes(5)),
            "name"=> $this->label ?? 'acs',
            "text"=> $this->label ?? 'asc',
            "type"=> "button",
            "value"=> $this->activator ?? $this->label ?? 'asx',
            "style"=> $this->style,
        ], is_null($this->buttonConfirm) ? [] : $this->buttonConfirm->toArray());
        return ['attachments' => json_encode([$prepare]) ];
    }
    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'RequestButton';
    }

    /**
     * Set activator
     *
     * @param string|null $activator
     * @return RequestButton
     */
    public function setActivator(string $activator = null) : RequestButton
    {
        $this->activator = $activator;
        return $this;
    }

    /**
     * @param string|null $style
     * @return RequestButton
     */
    public function setStyle(string $style = null) : RequestButton
    {
        $this->style = $style;
        return $this;
    }

    /**
     * Set confirm Button
     *
     * @param RequestConfirmButton $button
     * @return RequestButton
     */
    public function setConfirm(RequestConfirmButton $button) : RequestButton
    {
        $this->buttonConfirm = $button;
        return $this;
    }
}