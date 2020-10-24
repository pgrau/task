<?php

declare(strict_types=1);

namespace Project\Test\Unit\Domain\Model\Task;

use PHPUnit\Framework\TestCase;
use Project\Domain\Model\Task\Description;
use Project\Domain\Model\Task\MaxCharactersAllowedException;

class DescriptionTest extends TestCase
{
    public function testGivenValidStringCreateDescriptionCorrectly()
    {
        $value = 'Great description';
        $description = Description::create($value);

        $this->assertSame($value, $description->get());
    }

    public function testThrowExceptionWhenDescriptionContainMaximumCharacterAllowed()
    {
        $this->expectException(MaxCharactersAllowedException::class);

        $value = str_repeat("-", Description::MAX_CHARACTERS_ALLOWED + 1);
        Description::create($value);
    }
}
