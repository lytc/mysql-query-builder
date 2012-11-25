<?php

namespace Qb;

class UpdateTest extends \PHPUnit_Framework_TestCase
{
    public function testValue()
    {
        $update = new Update();
        $update->from('foo')
            ->value(['foo' => 1, 'bar' => 'bar']);

        $this->assertEquals("UPDATE `foo` SET `foo`=1, `bar`='bar'", $update->__toString());
    }

    public function testExecute()
    {
        $update = Query::update('foo', ['name' => 'qux']);

        Pdo::getInstance()->beginTransaction();
        $result = $update->execute();
        Pdo::getInstance()->rollBack();

        $this->assertGreaterThanOrEqual(1, $result);
    }
}