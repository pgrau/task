<?php

declare(strict_types=1);

namespace Project\Domain\Model\Task;

class Priority
{
    const HIGHEST = 'HIGHEST';
    const HIGH = 'HIGH';
    const LOW = 'LOW';
    const LOWEST = 'LOWEST';

    const VALUES_ALLOWED = [self::HIGHEST, self::HIGH, self::LOW, self::LOWEST];

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function create(string $value): self
    {
        $value = strtoupper($value);
        if (! in_array($value, self::VALUES_ALLOWED)) {
            throw PriorityNotAllowedException::fromString($value);
        }

        return new self($value);
    }

    public function get(): string
    {
        return $this->value;
    }
}