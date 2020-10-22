<?php

declare(strict_types=1);

namespace Project\Domain\Model\Task;

class Description
{
    const MAX_CHARACTERS_ALLOWED = 65535;

    private string $value;

    private function __construct(string $description)
    {
        $this->value = $description;
    }

    public static function create(string $description): self
    {
        if (strlen($description) > self::MAX_CHARACTERS_ALLOWED) {
            throw MaxCharactersAllowedException::fromDescription($description);
        }

        return new self($description);
    }

    public function get(): string
    {
        return $this->value;
    }
}
