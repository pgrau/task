<?php

declare(strict_types=1);

namespace Project\Test\Unit\Domain\Model\Task;

use PHPUnit\Framework\TestCase;
use Project\Domain\Model\Task\Priority;
use Project\Domain\Model\Task\PriorityNotAllowedException;

class PriorityTest extends TestCase
{
    public function testGivenAllowedPriorityCreatePriorityCorrectly()
    {
        $value = Priority::HIGH;
        $priority = Priority::create($value);

        $this->assertSame($value, $priority->get());
    }

    public function testThrowExceptionWhenPriorityIsNotAllowed()
    {
        $this->expectException(PriorityNotAllowedException::class);

        $value = 'asereje';
        Priority::create($value);
    }
}
