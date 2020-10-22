<?php

declare(strict_types=1);

namespace Project\Domain\Model\Task;

use Project\Domain\Model\Common\Exception\ConflictException;

class MaxCharactersAllowedException extends ConflictException
{
    public static function fromSummary(string $summary): self
    {
        $message = sprintf('Summary has more than maximum characters allowed (%d). Got(%d)',
            Summary::MAX_CHARACTERS_ALLOWED,
            strlen($summary)
        );

        return new self($message);
    }

    public static function fromDescription(string $description): self
    {
        $message = sprintf('Description has more than maximum characters allowed (%d). Got(%d)',
            Description::MAX_CHARACTERS_ALLOWED,
            strlen($description)
        );

        return new self($message);
    }
}