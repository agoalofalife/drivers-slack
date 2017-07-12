<?php
declare(strict_types=1);

namespace FondBot\Drivers\Slack\Contracts;


interface TransformRequest
{
    public function transform();
}