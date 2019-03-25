<?php

namespace Mu\Domain\Model\User;

use PHPUnit\Framework\TestCase;

class LastNameTest extends TestCase
{
    public function testValidMinLength()
    {
        $lastNameText = 'Aaa';
        $lastName = new LastName($lastNameText);
        $this->assertInstanceOf(LastName::class, $lastName);
        $this->assertEquals($lastNameText, $lastName->value());
    }

    public function testValidMaxLength()
    {
        $lastNameText = str_repeat('a', 50);
        $lastName = new LastName($lastNameText);
        $this->assertInstanceOf(LastName::class, $lastName);
        $this->assertEquals($lastNameText, $lastName->value());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidMinLength()
    {
        new LastName('aa');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidMaxLength()
    {
        new LastName(str_repeat('a', 51));
    }
}
