<?php

namespace App\Exceptions;

use LogicException;

class UndefinedStatusException extends LogicException
{
    public static function fromStatus(string $status): self
    {
        return new self(sprintf('Undefined status called: "%s"', $status));
    }
}
