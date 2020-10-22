<?php

declare(strict_types=1);

namespace Project\Domain\Model\Task;

class Summary
{
    const MAX_CHARACTERS_ALLOWED = 255;

    private string $value;

    private function __construct(string $summary)
    {
        $this->value = $summary;
    }

    public static function create(string $summary): self
    {
        if (strlen($summary) > self::MAX_CHARACTERS_ALLOWED) {
            throw MaxCharactersAllowedException::fromSummary($summary);
        }

        return new self($summary);
    }

    public function get(): string
    {
        return $this->value;
    }
}
