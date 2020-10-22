<?php

declare(strict_types=1);

namespace Project\Test\Unit\Domain\Model\Task;

use PHPUnit\Framework\TestCase;
use Project\Domain\Model\Task\MaxCharactersAllowedException;
use Project\Domain\Model\Task\Summary;

class SummaryTest extends TestCase
{
    public function testGivenValidStringCreateSummaryCorrectly()
    {
        $value = 'Great summary';
        $summary = Summary::create($value);

        $this->assertSame($value, $summary->get());
    }

    public function testThrowExceptionWhenSummaryContainMaximumCharacterAllowed()
    {
        $this->expectException(MaxCharactersAllowedException::class);

        $value = str_repeat("-", Summary::MAX_CHARACTERS_ALLOWED + 1);
        Summary::create($value);
    }
}