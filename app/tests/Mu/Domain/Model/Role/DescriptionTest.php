<?php

namespace Mu\Domain\Model\Role;

use PHPUnit\Framework\TestCase;

class DescriptionTest extends TestCase
{
    public function testValidDescription()
    {
        $textDescription = 'minde';
        $description = new Description($textDescription);
        $this->assertInstanceOf(Description::class, $description);
        $this->assertEquals($textDescription, $description->value());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidLength()
    {
        new Description('inva');
    }
}
