<?php

namespace Qb;

use Qb\Select;

class SelectTest extends \PHPUnit_Framework_TestCase
{
    public function testDistinctOption()
    {
        $select = new Select();
        $select->distinct();

        $this->assertEquals('SELECT DISTINCT * FROM ', $select->__toString());
    }

    public function testHighPriorityOption()
    {
        $select = new Select();
        $select->highPriority();

        $this->assertEquals('SELECT HIGH_PRIORITY * FROM ', $select->__toString());
    }

    public function testCacheOption()
    {
        $select = new Select();

        $select->cache();
        $this->assertEquals('SELECT SQL_CACHE * FROM ', $select->__toString());

        $select->noCache();
        $this->assertEquals('SELECT SQL_NO_CACHE * FROM ', $select->__toString());
    }

    public function testCalcFoundRowsOption()
    {
        $select = new Select();
        $select->calcFoundRows();
        $this->assertEquals('SELECT SQL_CALC_FOUND_ROWS * FROM ', $select->__toString());
    }

    public function testColumn()
    {
        $select = new Select();
        $select->column('foo');
        $this->assertEquals('SELECT `foo` FROM ', $select->__toString());

        $select = new Select();
        $select->column('foo, bar');
        $this->assertEquals('SELECT `foo`, `bar` FROM ', $select->__toString());

        $select = new Select();
        $select->column('foo f, bar  b');
        $this->assertEquals('SELECT `foo` `f`, `bar` `b` FROM ', $select->__toString());

        $select = new Select();
        $select->column(['foo f', 'bar  b']);
        $this->assertEquals('SELECT `foo` `f`, `bar` `b` FROM ', $select->__toString());

        $select = new Select();
        $select->column('foo f, bar  b');
        $this->assertEquals('SELECT `foo` `f`, `bar` `b` FROM ', $select->__toString());
    }

    public function testFrom()
    {
        $select = new Select();
        $select->from('foo');
        $this->assertEquals('SELECT * FROM `foo`', $select->__toString());

        $select = new Select();
        $select->from('foo f');
        $this->assertEquals('SELECT * FROM `foo` `f`', $select->__toString());

        $select = new Select();
        $select->from('foo f, bar  b');
        $this->assertEquals('SELECT * FROM `foo` `f`, `bar` `b`', $select->__toString());

        $select = new Select();
        $select->from(['foo', 'bar b']);
        $this->assertEquals('SELECT * FROM `foo`, `bar` `b`', $select->__toString());

        $select = new Select();
        $select->from('foo', 'bar');
        $this->assertEquals('SELECT * FROM `foo`, `bar`', $select->__toString());

        $select = new Select();
        $select->from(['foo', 'bar b'], 'baz z');
        $this->assertEquals('SELECT * FROM `foo`, `bar` `b`, `baz` `z`', $select->__toString());
    }

    public function testInnerJoin()
    {
        $select = new Select();
        $select->from('foo')->innerJoin('bar');
        $this->assertEquals('SELECT * FROM `foo` INNER JOIN `bar`', $select->__toString());

        $select = new Select();
        $select->from('foo')->innerJoin('bar', 'foo_id');
        $this->assertEquals('SELECT * FROM `foo` INNER JOIN `bar` USING `foo_id`', $select->__toString());

        $select = new Select();
        $select->from('foo')->innerJoin('bar', 'bar.foo_id = foo.id');
        $this->assertEquals('SELECT * FROM `foo` INNER JOIN `bar` ON bar.foo_id = foo.id', $select->__toString());

        $select = new Select();
        $select->from('foo f')->innerJoin('bar b', 'b.foo_id = f.id');
        $this->assertEquals('SELECT * FROM `foo` `f` INNER JOIN `bar` `b` ON b.foo_id = f.id', $select->__toString());
    }

    public function testStraightJoin()
    {
        $select = new Select();
        $select->from('foo')->straightJoin('bar');
        $this->assertEquals('SELECT * FROM `foo` STRAIGHT_JOIN `bar`', $select->__toString());

        $select = new Select();
        $select->from('foo f')->straightJoin('bar b');
        $this->assertEquals('SELECT * FROM `foo` `f` STRAIGHT_JOIN `bar` `b`', $select->__toString());
    }

    public function testLeftJoin()
    {
        $select = new Select();
        $select->from('foo')->leftJoin('bar', 'foo_id');
        $this->assertEquals('SELECT * FROM `foo` LEFT JOIN `bar` USING `foo_id`', $select->__toString());

        $select = new Select();
        $select->from('foo')->leftJoin('bar', 'bar.foo_id = foo.id');
        $this->assertEquals('SELECT * FROM `foo` LEFT JOIN `bar` ON bar.foo_id = foo.id', $select->__toString());

        $select = new Select();
        $select->from('foo f')->leftJoin('bar b', 'b.foo_id = f.id');
        $this->assertEquals('SELECT * FROM `foo` `f` LEFT JOIN `bar` `b` ON b.foo_id = f.id', $select->__toString());
    }

    public function testRightJoin()
    {
        $select = new Select();
        $select->from('foo')->rightJoin('bar', 'foo_id');
        $this->assertEquals('SELECT * FROM `foo` RIGHT JOIN `bar` USING `foo_id`', $select->__toString());

        $select = new Select();
        $select->from('foo')->rightJoin('bar', 'bar.foo_id = foo.id');
        $this->assertEquals('SELECT * FROM `foo` RIGHT JOIN `bar` ON bar.foo_id = foo.id', $select->__toString());

        $select = new Select();
        $select->from('foo f')->rightJoin('bar b', 'b.foo_id = f.id');
        $this->assertEquals('SELECT * FROM `foo` `f` RIGHT JOIN `bar` `b` ON b.foo_id = f.id', $select->__toString());
    }

    public function testLeftOuterJoin()
    {
        $select = new Select();
        $select->from('foo')->leftOuterJoin('bar', 'foo_id');
        $this->assertEquals('SELECT * FROM `foo` LEFT OUTER JOIN `bar` USING `foo_id`', $select->__toString());

        $select = new Select();
        $select->from('foo')->leftOuterJoin('bar', 'bar.foo_id = foo.id');
        $this->assertEquals('SELECT * FROM `foo` LEFT OUTER JOIN `bar` ON bar.foo_id = foo.id', $select->__toString());

        $select = new Select();
        $select->from('foo f')->leftOuterJoin('bar b', 'b.foo_id = f.id');
        $this->assertEquals('SELECT * FROM `foo` `f` LEFT OUTER JOIN `bar` `b` ON b.foo_id = f.id', $select->__toString());
    }

    public function testRightOuterJoin()
    {
        $select = new Select();
        $select->from('foo')->rightOuterJoin('bar', 'foo_id');
        $this->assertEquals('SELECT * FROM `foo` RIGHT OUTER JOIN `bar` USING `foo_id`', $select->__toString());

        $select = new Select();
        $select->from('foo')->rightOuterJoin('bar', 'bar.foo_id = foo.id');
        $this->assertEquals('SELECT * FROM `foo` RIGHT OUTER JOIN `bar` ON bar.foo_id = foo.id', $select->__toString());

        $select = new Select();
        $select->from('foo f')->rightOuterJoin('bar b', 'b.foo_id = f.id');
        $this->assertEquals('SELECT * FROM `foo` `f` RIGHT OUTER JOIN `bar` `b` ON b.foo_id = f.id', $select->__toString());
    }

    public function testNaturalJoin()
    {
        $select = new Select();
        $select->from('foo')->naturalJoin('bar');
        $this->assertEquals('SELECT * FROM `foo` NATURAL JOIN `bar`', $select->__toString());

        $select = new Select();
        $select->from('foo f')->naturalJoin('bar b');
        $this->assertEquals('SELECT * FROM `foo` `f` NATURAL JOIN `bar` `b`', $select->__toString());
    }

    public function testNaturalLeftJoin()
    {
        $select = new Select();
        $select->from('foo')->naturalLeftJoin('bar');
        $this->assertEquals('SELECT * FROM `foo` NATURAL LEFT JOIN `bar`', $select->__toString());

        $select = new Select();
        $select->from('foo f')->naturalLeftJoin('bar b');
        $this->assertEquals('SELECT * FROM `foo` `f` NATURAL LEFT JOIN `bar` `b`', $select->__toString());
    }

    public function testNaturalRightJoin()
    {
        $select = new Select();
        $select->from('foo')->naturalRightJoin('bar');
        $this->assertEquals('SELECT * FROM `foo` NATURAL RIGHT JOIN `bar`', $select->__toString());

        $select = new Select();
        $select->from('foo f')->naturalRightJoin('bar b');
        $this->assertEquals('SELECT * FROM `foo` `f` NATURAL RIGHT JOIN `bar` `b`', $select->__toString());
    }

    public function testNaturalLeftOuterJoin()
    {
        $select = new Select();
        $select->from('foo')->naturalLeftOuterJoin('bar');
        $this->assertEquals('SELECT * FROM `foo` NATURAL LEFT OUTER JOIN `bar`', $select->__toString());

        $select = new Select();
        $select->from('foo f')->naturalLeftOuterJoin('bar b');
        $this->assertEquals('SELECT * FROM `foo` `f` NATURAL LEFT OUTER JOIN `bar` `b`', $select->__toString());
    }

    public function testNaturalRightOuterJoin()
    {
        $select = new Select();
        $select->from('foo')->naturalRightOuterJoin('bar');
        $this->assertEquals('SELECT * FROM `foo` NATURAL RIGHT OUTER JOIN `bar`', $select->__toString());

        $select = new Select();
        $select->from('foo f')->naturalRightOuterJoin('bar b');
        $this->assertEquals('SELECT * FROM `foo` `f` NATURAL RIGHT OUTER JOIN `bar` `b`', $select->__toString());
    }

    public function testWhere()
    {
        $select = new Select();
        $select->from('foo')->where('id = 1');
        $this->assertEquals('SELECT * FROM `foo` WHERE id = 1', $select->__toString());

        $select = new Select();
        $select->from('foo')
            ->where('bar = 1')
            ->where('AND baz = 2')
            ->where('OR qux = 3');
        $this->assertEquals('SELECT * FROM `foo` WHERE bar = 1 AND baz = 2 OR qux = 3', $select->__toString());
    }

    public function testWhereWithBind()
    {
        $select = new Select();
        $select->from('foo')->where('bar = ?', 1);
        $this->assertEquals('SELECT * FROM `foo` WHERE bar = 1', $select->__toString());

        $select = new Select();
        $select->from('foo')->where('bar = ? AND baz = ?', 1);
        $this->assertEquals('SELECT * FROM `foo` WHERE bar = 1 AND baz = 1', $select->__toString());

        $select = new Select();
        $select->from('foo')->where('bar = ? OR bar = ?', 1, 2);
        $this->assertEquals('SELECT * FROM `foo` WHERE bar = 1 OR bar = 2', $select->__toString());

        $select = new Select();
        $select->from('foo')->where('bar IN(?)', [1,2]);
        $this->assertEquals('SELECT * FROM `foo` WHERE bar IN(1, 2)', $select->__toString());

        $select = new Select();
        $select->from('foo')->where('bar IN(?)', [1,'foo']);
        $this->assertEquals("SELECT * FROM `foo` WHERE bar IN(1, 'foo')", $select->__toString());

        $select = new Select();
        $select->from('foo')->where([
            'bar > ?' => 1,
            'baz IN(?)' => [1,'foo']
        ]);
        $this->assertEquals("SELECT * FROM `foo` WHERE bar > 1 AND baz IN(1, 'foo')", $select->__toString());
    }

    public function testGroupBy()
    {
        $select = new Select();
        $select->from('foo')->groupBy('bar');
        $this->assertEquals('SELECT * FROM `foo` GROUP BY `bar`', $select->__toString());

        $select = new Select();
        $select->from('foo')->groupBy('bar, baz');
        $this->assertEquals('SELECT * FROM `foo` GROUP BY `bar`, `baz`', $select->__toString());

        $select = new Select();
        $select->from('foo')->groupBy(['bar', 'baz']);
        $this->assertEquals('SELECT * FROM `foo` GROUP BY `bar`, `baz`', $select->__toString());

        $select = new Select();
        $select->from('foo')->groupBy('bar', 'baz');
        $this->assertEquals('SELECT * FROM `foo` GROUP BY `bar`, `baz`', $select->__toString());

        $select = new Select();
        $select->from('foo')->groupBy(['bar', 'baz'], 'qux');
        $this->assertEquals('SELECT * FROM `foo` GROUP BY `bar`, `baz`, `qux`', $select->__toString());
    }

    public function testHaving()
    {
        $select = new Select();
        $select->from('foo')->having('id = 1');
        $this->assertEquals('SELECT * FROM `foo` HAVING id = 1', $select->__toString());

        $select = new Select();
        $select->from('foo')
            ->having('bar = 1')
            ->having('AND baz = 2')
            ->having('OR qux = 3');
        $this->assertEquals('SELECT * FROM `foo` HAVING bar = 1 AND baz = 2 OR qux = 3', $select->__toString());
    }

    public function testHavingWithBind()
    {
        $select = new Select();
        $select->from('foo')->having('bar = ?', 1);
        $this->assertEquals('SELECT * FROM `foo` HAVING bar = 1', $select->__toString());

        $select = new Select();
        $select->from('foo')->having('bar = ? AND baz = ?', 1);
        $this->assertEquals('SELECT * FROM `foo` HAVING bar = 1 AND baz = 1', $select->__toString());

        $select = new Select();
        $select->from('foo')->having('bar = ? OR bar = ?', 1, 2);
        $this->assertEquals('SELECT * FROM `foo` HAVING bar = 1 OR bar = 2', $select->__toString());

        $select = new Select();
        $select->from('foo')->having('bar IN(?)', [1,2]);
        $this->assertEquals('SELECT * FROM `foo` HAVING bar IN(1, 2)', $select->__toString());

        $select = new Select();
        $select->from('foo')->having('bar IN(?)', [1,'foo']);
        $this->assertEquals("SELECT * FROM `foo` HAVING bar IN(1, 'foo')", $select->__toString());

        $select = new Select();
        $select->from('foo')->having([
            'bar > ?' => 1,
            'baz IN(?)' => [1,'foo']
        ]);
        $this->assertEquals("SELECT * FROM `foo` HAVING bar > 1 AND baz IN(1, 'foo')", $select->__toString());
    }

    public function testOrderBy()
    {
        $select = new Select();
        $select->from('foo')->orderBy('bar');
        $this->assertEquals('SELECT * FROM `foo` ORDER BY `bar`', $select->__toString());

        $select = new Select();
        $select->from('foo')->orderBy('bar, baz');
        $this->assertEquals('SELECT * FROM `foo` ORDER BY `bar`, `baz`', $select->__toString());

        $select = new Select();
        $select->from('foo')->orderBy(['bar', 'baz']);
        $this->assertEquals('SELECT * FROM `foo` ORDER BY `bar`, `baz`', $select->__toString());

        $select = new Select();
        $select->from('foo')->orderBy('bar', 'baz');
        $this->assertEquals('SELECT * FROM `foo` ORDER BY `bar`, `baz`', $select->__toString());

        $select = new Select();
        $select->from('foo')->orderBy(['bar', 'baz'], 'qux');
        $this->assertEquals('SELECT * FROM `foo` ORDER BY `bar`, `baz`, `qux`', $select->__toString());

        $select = new Select();
        $select->from('foo')->orderBy('bar, baz DESC');
        $this->assertEquals('SELECT * FROM `foo` ORDER BY `bar`, `baz` DESC', $select->__toString());
    }

    public function testLimit()
    {
        $select = new Select();
        $select->from('foo')->limit(10);
        $this->assertEquals('SELECT * FROM `foo` LIMIT 10', $select->__toString());

        $select = new Select();
        $select->from('foo')->limit(10)->offset(11);
        $this->assertEquals('SELECT * FROM `foo` LIMIT 10 OFFSET 11', $select->__toString());

        $select = new Select();
        $select->from('foo')->limit(10, 11);
        $this->assertEquals('SELECT * FROM `foo` LIMIT 10 OFFSET 11', $select->__toString());
    }

    public function testInto()
    {
        $select = new Select();
        $select->from('foo')->into('bar');
        $this->assertEquals('SELECT * FROM `foo` INTO bar', $select->__toString());
    }

    public function testIntoOutFile()
    {
        $select = new Select();
        $select->from('foo')->intoOutFile('path/to/filename.csv');
        $this->assertEquals("SELECT * FROM `foo` INTO OUTFILE 'path/to/filename.csv'", $select->__toString());

        $select = new Select();
        $select->from('foo')->intoOutFile('path/to/filename.csv', 'utf8');
        $this->assertEquals("SELECT * FROM `foo` INTO OUTFILE 'path/to/filename.csv' CHARACTER SET utf8", $select->__toString());
    }

    public function testIntoDumpFile()
    {
        $select = new Select();
        $select->from('foo')->intoDumpFile('path/to/filename.csv');
        $this->assertEquals("SELECT * FROM `foo` INTO DUMPFILE 'path/to/filename.csv'", $select->__toString());
    }

    public function testForUpdateOption()
    {
        $select = new Select();
        $select->from('foo')->forUpdate();

        $this->assertEquals('SELECT * FROM `foo` FOR UPDATE', $select->__toString());
    }

    public function testLockInShareMode()
    {
        $select = new Select();
        $select->from('foo')->lockInShareMode();

        $this->assertEquals('SELECT * FROM `foo` LOCK IN SHARE MODE', $select->__toString());
    }

    public function testReset()
    {
        $select = new Select();
        $select->from('foo')->reset('from')->from('bar');
        $this->assertEquals('SELECT * FROM `bar`', $select->__toString());

        $select = new Select();
        $select->from('foo')->where('bar > 1')->reset()->from('bar');
        $this->assertEquals('SELECT * FROM `bar`', $select->__toString());
    }

    public function testComplexQuery()
    {
        $expected = "SELECT DISTINCT
                `tCourse`.`title`,
                `tCourse`.`coursenumber`,
                `tCourse`.`courseid`,
                `tCourse`.`startdts`,
                `tCourse`.`enddts`
            FROM
                `tCourse`
            LEFT OUTER JOIN `tCourseUser` ON tCourseUser.courseid = tCourse.courseid AND tCourseUser.userid = 1
            LEFT OUTER JOIN `tCourseRole` ON tCourseRole.courseid = tCourse.courseid
            LEFT OUTER JOIN `tUserRole` ON tUserRole.roleid = tCourseRole.roleid AND tUserRole.userid = 1
            WHERE
                tCourse.systemid = 1
            AND (tCourse.startdts < now() OR tCourseUser.facultyf > 0 OR tCourseRole.facultyf > 0)
            AND (tCourseUser.userid IS NOT NULL OR tUserRole.userid IS NOT NULL)
            ORDER BY
                `tCourse`.`title`";

        $expected = preg_replace('/\s+/', ' ', $expected);
        $select = new Select();
        $select
            ->distinct()
            ->from('tCourse')
            ->column(['tCourse' => 'title, coursenumber, courseid, startdts, enddts'])
            ->leftOuterJoin('tCourseUser', 'tCourseUser.courseid = tCourse.courseid AND tCourseUser.userid = 1')
            ->leftOuterJoin('tCourseRole', 'tCourseRole.courseid = tCourse.courseid')
            ->leftOuterJoin('tUserRole', 'tUserRole.roleid = tCourseRole.roleid AND tUserRole.userid = 1')
            ->where('tCourse.systemid = ?', 1)
            ->where('(tCourse.startdts < now() OR tCourseUser.facultyf > ? OR tCourseRole.facultyf > ?)', 0)
            ->where('(tCourseUser.userid IS NOT NULL OR tUserRole.userid IS NOT NULL)')
            ->orderBy('tCourse.title');

        $this->assertEquals($expected, $select->__toString());

        $expected = "SELECT DISTINCT
                `c`.`title`,
                `c`.`coursenumber`,
                `c`.`courseid`,
                `c`.`startdts`,
                `c`.`enddts`
            FROM
                `tCourse` `c`
            LEFT OUTER JOIN `tCourseUser` `cu` ON cu.courseid = c.courseid AND cu.userid = 1
            LEFT OUTER JOIN `tCourseRole` `cr` ON cr.courseid = c.courseid
            LEFT OUTER JOIN `tUserRole` `ur` ON ur.roleid = cr.roleid AND ur.userid = 1
            WHERE
                c.systemid = 1
            AND (c.startdts < now() OR cu.facultyf > 0 OR cr.facultyf > 0)
            AND (cu.userid IS NOT NULL OR ur.userid IS NOT NULL)
            ORDER BY
                `c`.`title`";

        $expected = preg_replace('/\s+/', ' ', $expected);
        $select = new Select();
        $select
            ->distinct()
            ->from('tCourse c')
            ->column(['c' => 'title, coursenumber, courseid, startdts, enddts'])
            ->leftOuterJoin('tCourseUser cu', 'cu.courseid = c.courseid AND cu.userid = 1')
            ->leftOuterJoin('tCourseRole cr', 'cr.courseid = c.courseid')
            ->leftOuterJoin('tUserRole ur', 'ur.roleid = cr.roleid AND ur.userid = 1')
            ->where('c.systemid = ?', 1)
            ->where('(c.startdts < now() OR cu.facultyf > ? OR cr.facultyf > ?)', 0)
            ->where('(cu.userid IS NOT NULL OR ur.userid IS NOT NULL)')
            ->orderBy('c.title');

        $this->assertEquals($expected, $select->__toString());
    }

    public function testFetch()
    {
        $result = Query::select('foo')->fetch();
        $this->assertArrayHasKey('id', $result);

        $result = Query::select('foo')->fetch(Pdo::FETCH_OBJ);
        $this->assertObjectHasAttribute('id', $result);


        $result = Query::select('foo')->fetch(Pdo::FETCH_OBJ);
        $this->assertObjectHasAttribute('id', $result);

        $result = Query::select('foo')->where('id = :id')->fetch(['id' => 1]);
        $this->assertArrayHasKey('id', $result);
    }

    public function testFetchAll()
    {
        $result = Query::select('foo')->fetchAll();
        $this->assertInternalType('array', $result);
    }

    public function testFetchCol()
    {
        $result = Query::select('foo')->fetchCol(1);
        $this->assertInternalType('array', $result);
        $this->assertContains('foo', $result);

        $result = Query::select('foo')->fetchCol('name');
        $this->assertContains('foo', $result);
    }

    public function testFetchCell()
    {
        $result = Query::select('foo')->fetchCell(1);
        $this->assertEquals('foo', $result);
    }
}