<?php

declare(strict_types=1);

namespace Project\Domain\Model\Task;

use Project\Domain\Model\Common\Exception\NotFoundException;

class TaskNotFoundException extends NotFoundException
{
    public static function byId(TaskId $id): self
    {
        $message = sprintf('Task %s not found.', $id->get()->toString());

        return new self($message);
    }
}
