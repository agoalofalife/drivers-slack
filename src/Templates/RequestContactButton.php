<?php

declare(strict_types=1);

namespace FondBot\Drivers\Telegram\Templates;

use FondBot\Contracts\Arrayable;
use FondBot\Templates\Keyboard\Button;

class RequestContactButton extends Button implements Arrayable
{
    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'RequestContactButton';
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            [
                'text' => $this->label,
                'request_contact' => true,
            ],
        ];
    }
}
