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
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'attachment' => [
            'type' => 'template',
            'actions' => [
               'options' => [
                   $this->options
               ]
           ],
      ]];
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
}