<?php

declare(strict_types=1);

namespace Project\Domain\Model\Task;

use Project\Domain\Model\Common\Exception\ConflictException;

class PriorityNotAllowedException extends ConflictException
{
    public static function fromString(string $value): self
    {
        $message = sprintf('Priority must be one of (%s). Got(%s)',
            implode(',', Priority::VALUES_ALLOWED),
            $value
        );

        return new self($message);
    }
}
