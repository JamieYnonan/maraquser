<?php

namespace Mu\Domain\Model\Permission;

use PHPUnit\Framework\TestCase;

class DescriptionTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidLength()
    {
        new Description('abcd');
    }

    public function testValidLength()
    {
        $description = new Description('abcde');
        $this->assertInstanceOf(Description::class, $description);
    }
}
