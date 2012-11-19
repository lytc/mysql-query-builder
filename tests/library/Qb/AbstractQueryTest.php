<?php

namespace Qb;

class AbstractQueryTest extends \PHPUnit_Framework_TestCase
{
    public function testQuoteIdentifier()
    {
        $this->assertEquals('`foo`', AbstractQuery::quoteIdentifier('foo'));
        $this->assertEquals('`foo`.`bar`', AbstractQuery::quoteIdentifier('foo.bar'));
        $this->assertEquals('`foo`.`bar`.`baz`', AbstractQuery::quoteIdentifier('foo.bar.baz'));
    }

    public function testEscape()
    {
        $this->assertEquals('f\\"oo', AbstractQuery::escape('f"oo'));
        $this->assertEquals('f\\\'oo', AbstractQuery::escape('f\'oo'));
        $this->assertEquals('f\\\oo', AbstractQuery::escape('f\oo'));
    }

    public function testQuote()
    {
        $this->assertEquals(1, AbstractQuery::quote(1));
        $this->assertEquals("'foo'", AbstractQuery::quote('foo'));
        $this->assertEquals(NULL, AbstractQuery::quote(NULL));
        $this->assertEquals(true, AbstractQuery::quote(true));
        $this->assertEquals(false, AbstractQuery::quote(false));
        $this->assertEquals(1.2, AbstractQuery::quote(1.2));
        $this->assertEquals([1, 2], AbstractQuery::quote([1, 2]));
        $this->assertEquals([1, '2'], AbstractQuery::quote([1, '2']));
    }

    public function testBind()
    {
        $this->assertEquals("WHERE id = 1", AbstractQuery::bind("WHERE id = ?", 1));
        $this->assertEquals("WHERE id = 1", AbstractQuery::bind("WHERE id = ?", '1'));
        $this->assertEquals("WHERE id = 'foo'", AbstractQuery::bind("WHERE id = ?", 'foo'));
        $this->assertEquals("WHERE id = 1", AbstractQuery::bind("WHERE id = ?", true));
        $this->assertEquals("WHERE id = 0", AbstractQuery::bind("WHERE id = ?", false));
        $this->assertEquals("WHERE id IN(1, 2)", AbstractQuery::bind("WHERE id IN(?)", [[1,2]]));
        $this->assertEquals("WHERE id IN(1, 'foo')", AbstractQuery::bind("WHERE id IN(?)", [[1,'foo']]));
        $this->assertEquals("WHERE id IN(1, '\\\"foo')", AbstractQuery::bind("WHERE id IN(?)", [1,'"foo']));

        $this->assertEquals('WHERE id = (1+1)', AbstractQuery::bind('WHERE id = ?', new Expr('(1+1)')));

        $subSelect = new Select();
        $subSelect->from('bar')->column('id')->where('id > ?', 10);
        $this->assertEquals('WHERE id IN(SELECT `id` FROM `bar` WHERE id > 10)', AbstractQuery::bind('WHERE id IN(?)', $subSelect));
    }
}