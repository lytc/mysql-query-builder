<?php

namespace Qb;

class DeleteTest extends \PHPUnit_Framework_TestCase
{
    public function testTablePart()
    {
        $delete = new Delete();
        $delete->table('f')->from('foo f, bar b');
        $this->assertEquals('DELETE `f` FROM `foo` `f`, `bar` `b`', $delete->__toString());
    }

    public function testFromPart()
    {
        $delete = new Delete();
        $delete->from('foo');
        $this->assertEquals('DELETE FROM `foo`', $delete->__toString());

        $delete = new Delete();
        $delete->from('foo f');
        $this->assertEquals('DELETE FROM `foo` `f`', $delete->__toString());

        $delete = new Delete();
        $delete->from('foo f, bar b');
        $this->assertEquals('DELETE FROM `foo` `f`, `bar` `b`', $delete->__toString());
    }

    public function testWherePart()
    {
        $delete = new Delete();
        $delete->from('foo')->where('bar > 1');
        $this->assertEquals('DELETE FROM `foo` WHERE bar > 1', $delete->__toString());
    }

    public function testOrderByPart()
    {
        $delete = new Delete();
        $delete->from('foo')->orderBy('bar, baz DESC');
        $this->assertEquals('DELETE FROM `foo` ORDER BY `bar`, `baz` DESC', $delete->__toString());
    }

    public function testOptions()
    {
        $delete = new Delete();
        $delete->from('foo')
            ->lowPriority()
            ->quick()
            ->ignore();

        $this->assertEquals('DELETE LOW_PRIORITY QUICK IGNORE FROM `foo`', $delete->__toString());
    }
}