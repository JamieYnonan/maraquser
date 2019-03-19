<?php

namespace Mu\Domain\Model\Permission;

use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidMinLength()
    {
        new Name('ab');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidMaxLength()
    {
        $invalidLength = 'qwertyuiopasdfghjklzxcvbnmqwertyuiopasdfghjklzxcvbn';
        new Name($invalidLength);
    }

    public function testValidMinLength()
    {
        $name = new Name('abc');
        $this->assertInstanceOf(Name::class, $name);
    }

    public function testValidMaxLength()
    {
        $validLength = 'qwertyuiopasdfghjklzxcvbnqwertyuiopasdfghjklzxcvbn';
        $name = new Name($validLength);

        $this->assertInstanceOf(Name::class, $name);
    }

    public function testToLowerText()
    {
        $name = new Name('UPPER');
        $this->assertEquals('upper', $name->value());
    }

    public function testReplaceSpaces()
    {
        $name = new Name('my name is ');
        $this->assertEquals('my-name-is', $name->value());
    }
}
