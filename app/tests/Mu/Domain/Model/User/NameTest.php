<?php

namespace Mu\Domain\Model\User;

use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function testValidMinLength()
    {
        $nameText = 'Aaa';
        $name = new Name($nameText);
        $this->assertInstanceOf(Name::class, $name);
        $this->assertEquals($nameText, $name->value());
    }

    public function testValidMaxLength()
    {
        $nameText = str_repeat('a', 50);
        $name = new Name($nameText);
        $this->assertInstanceOf(Name::class, $name);
        $this->assertEquals($nameText, $name->value());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidMinLength()
    {
        new Name('aa');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidMaxLength()
    {
        new Name(str_repeat('a', 51));
    }
}
