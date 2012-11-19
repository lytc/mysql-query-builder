<?php

namespace Qb;

use Qb\Part\From,
    Qb\Part\Join,
    Qb\Part\Where,
    Qb\Part\OrderBy,
    Qb\Part\Limit;

class Delete extends AbstractQuery
{
    use From, Join, Where, OrderBy, Limit;

    protected $_defaultParts = [
        'lowPriority'       => false,
        'quick'             => false,
        'ignore'            => false,
        'table'            => [],
        'from'              => [],
        'join'              => [],
        'where'             => [],
        'orderBy'           => [],
        'limit'             => null
    ];

    /**
     * @param bool [$flag=true]
     * @return Delete
     */
    public function lowPriority($flag = true)
    {
        $this->_parts['lowPriority'] = $flag;
        return $this;
    }

    /**
     * @param bool [$flag=true]
     * @return Delete
     */
    public function quick($flag = true)
    {
        $this->_parts['quick'] = $flag;
        return $this;
    }

    /**
     * @param bool [$flag=true]
     * @return Delete
     */
    public function ignore($flag = true)
    {
        $this->_parts['ignore'] = $flag;
        return $this;
    }

    /**
     * @param String|Array|Arguments $tables
     * @return Delete
     */
    public function table($tables)
    {
        $tables = func_get_args();
        foreach ($tables as $table) {
            if (!is_array($table)) {
                $table = preg_split('/,\s*/', $table);
            }
            $this->_parts['table'] = array_merge($this->_parts['table'], $table);
        }
        return $this;
    }

    /**
     * @return String
     */
    protected function _build()
    {
        $parts = ['DELETE'];

        # table part
        if ($tables = $this->_parts['table']) {
            foreach ($tables as &$table) {
                $table = self::quoteIdentifier($table);
            }
            $parts[] = implode(', ', $tables);
        }

        # low priority?
        if ($this->_parts['lowPriority']) {
            $parts[] = 'LOW_PRIORITY';
        }

        # quick?
        if ($this->_parts['quick']) {
            $parts[] = 'QUICK';
        }

        # ignore?
        if ($this->_parts['ignore']) {
            $parts[] = 'IGNORE';
        }

        # from part
        $parts[] = $this->_buildFromPart();

        # join part
        if ($this->_parts['join']) {
            $parts[] = $this->_buildJoinPart();
        }

        # where part
        if ($this->_parts['where']) {
            $parts[] = $this->_buildWherePart();
        }

        # order by part
        if ($this->_parts['orderBy']) {
            $parts[] = $this->_buildOrderByPart();
        }

        # limit part
        if ($this->_parts['limit']) {
            $parts[] = $this->_buildLimitPart();
        }

        return implode(' ', $parts);
    }
}