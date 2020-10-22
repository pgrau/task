<?php

declare(strict_types=1);

namespace Project\Domain\Model\Common\ValueObject;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class UuidIdentifier
{
    private UuidInterface $value;

    private function __construct(UuidInterface $uuid)
    {
        $this->value = $uuid;
    }

    public static function create(): self
    {
        return new static(Uuid::uuid4());
    }

    public static function fromString(string $uuid): self
    {
        return new static(Uuid::fromString($uuid));
    }

    public function get(): UuidInterface
    {
        return $this->value;
    }
}