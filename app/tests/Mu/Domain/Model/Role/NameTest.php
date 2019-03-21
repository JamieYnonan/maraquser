<?php

namespace Mu\Domain\Model\Role;

use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function testValidNameMinLength()
    {
        $nameText = 'min';
        $name = new Name($nameText);
        $this->assertInstanceOf(Name::class, $name);
        $this->assertEquals($nameText, $name->value());
    }

    public function testValidNameMaxLength()
    {
        $nameText = str_repeat('a', 50);
        $name = new Name($nameText);
        $this->assertInstanceOf(Name::class, $name);
        $this->assertEquals($nameText, $name->value());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidNameMinLength()
    {
        new Name('aa');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidNameMaxLength()
    {
        new Name(str_repeat('a', 51));
    }
}
