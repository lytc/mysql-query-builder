<?php

namespace Qb;

class InsertTest extends \PHPUnit_Framework_TestCase
{
    public function testInsertValues()
    {
        $insert = new Insert();
        $insert->into('foo')->value(['foo' => 'foo', 'bar' => 'bar']);
        $this->assertEquals("INSERT `foo` (`foo`, `bar`) VALUES ('foo', 'bar')", $insert->__toString());

        $insert = new Insert();
        $insert->into('foo')->value([['foo' => 'foo', 'bar' => 'bar'], ['foo' => 'foo2', 'bar' => 'bar2']]);
        $this->assertEquals("INSERT `foo` (`foo`, `bar`) VALUES ('foo', 'bar'), ('foo2', 'bar2')", $insert->__toString());

        $insert = new Insert();
        $insert->into('foo')->column('foo, bar')->value(['foo', 'bar' => 'bar']);
        $this->assertEquals("INSERT `foo` (`foo`, `bar`) VALUES ('foo', 'bar')", $insert->__toString());

        $insert = new Insert();
        $insert->into('foo')->column(['foo', 'bar'])->value(['foo', 'bar' => 'bar']);
        $this->assertEquals("INSERT `foo` (`foo`, `bar`) VALUES ('foo', 'bar')", $insert->__toString());
    }

    public function testInsertSelect()
    {
        $insert = new Insert();
        $insert->into('foo')->value("SELECT * FROM bar");
        $this->assertEquals('INSERT `foo` SELECT * FROM bar', $insert->__toString());

        $select = new Select();
        $select->from('bar');
        $insert = new Insert();
        $insert->into('foo')->value($select);
        $this->assertEquals('INSERT `foo` SELECT * FROM `bar`', $insert->__toString());

        $select = new Select();
        $select->from('bar')->column('foo, bar, baz');
        $insert = new Insert();
        $insert->into('foo')->column('foo, bar, baz')->value($select);
        $this->assertEquals('INSERT `foo` (`foo`, `bar`, `baz`) SELECT `foo`, `bar`, `baz` FROM `bar`', $insert->__toString());
    }

    public function testOnDuplicateKeyUpdate()
    {
        $insert = new Insert();
        $insert->into('foo')
                ->value(['foo' => 'foo', 'bar' => 'bar'])
                ->onDuplicateKeyUpdate(['foo' => 1, 'bar' => 'baz']);

        $expected = "INSERT `foo` (`foo`, `bar`) VALUES ('foo', 'bar') ON DUPLICATE KEY UPDATE `foo`=1, `bar`='baz'";
        $this->assertEquals($expected, $insert->__toString());
    }

    public function testOptions()
    {
        $insert = new Insert();
        $insert->into('foo')
            ->value(['foo' => 'foo', 'bar' => 'bar'])
            ->lowPriority();
        $this->assertEquals("INSERT LOW_PRIORITY `foo` (`foo`, `bar`) VALUES ('foo', 'bar')", $insert->__toString());

        $insert = new Insert();
        $insert->into('foo')
            ->value(['foo' => 'foo', 'bar' => 'bar'])
            ->highPriority();
        $this->assertEquals("INSERT HIGH_PRIORITY `foo` (`foo`, `bar`) VALUES ('foo', 'bar')", $insert->__toString());

        $insert = new Insert();
        $insert->into('foo')
            ->value(['foo' => 'foo', 'bar' => 'bar'])
            ->delayed();
        $this->assertEquals("INSERT DELAYED `foo` (`foo`, `bar`) VALUES ('foo', 'bar')", $insert->__toString());

        $insert = new Insert();
        $insert->into('foo')
            ->value(['foo' => 'foo', 'bar' => 'bar'])
            ->ignore();
        $this->assertEquals("INSERT IGNORE `foo` (`foo`, `bar`) VALUES ('foo', 'bar')", $insert->__toString());
    }

    public function testTableAlias()
    {
        $insert = new Insert();
        $insert->into('foo f')->value(['foo' => 'foo', 'bar' => 'bar']);
        $this->assertEquals("INSERT `foo` `f` (`foo`, `bar`) VALUES ('foo', 'bar')", $insert->__toString());
    }
}