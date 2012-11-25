<?php

namespace Qb;

class PdoTest extends \PHPUnit_Framework_TestCase
{
    public function testGetInstance()
    {
        $pdo = Pdo::getInstance();
        $this->assertInstanceOf('Qb\\Pdo', $pdo);
        $this->assertSame($pdo, Pdo::getInstance());
    }
}