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
     * @var array
     */
    private $options = [];

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $placeholder;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $callbackId;

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            "response_type" => "in_channel",
            'attachments' => json_encode([
                [
                "text" => $this->text ?? '',
                'type' => 'template',
                "callback_id" => $this->callbackId ?? bin2hex(random_bytes(5)),
                'actions' => [
                    [
                    "name"=> $this->name ?? 'default',
                    "text"=> $this->placeholder ?? 'Choose..',
                    "type"=> "select",
                    'options' => $this->options
                    ]
                ]
                ]
            ])
        ];
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

    /**
     * Add new option in select
     *
     * @param array $option
     * @return RequestSelect
     */
    public function addOption(array $option) : RequestSelect
    {
        $this->options[] = $option;
        return $this;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return RequestSelect
     */
    public function setText(string $text) : RequestSelect
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Set placeholder menu
     *
     * @param string $placeholder
     * @return RequestSelect
     */
    public function setPlaceholder(string $placeholder) : RequestSelect
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return RequestSelect
     */
    public function setName(string $name) : RequestSelect
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set unique callback id
     *
     * @param string $id
     * @return RequestSelect
     */
    public function setCallbackId(string $id) : RequestSelect
    {
        $this->callbackId = $id;
        return $this;
    }
}