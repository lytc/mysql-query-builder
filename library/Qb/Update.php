<?php

namespace Qb;

use Qb\Part\From,
    Qb\Part\Join,
    Qb\Part\Where,
    Qb\Part\OrderBy,
    Qb\Part\Limit;

class Update extends AbstractQuery
{
    use From, Join, Where, OrderBy, Limit;

    protected $_defaultParts = [
        'lowPriority'       => false,
        'ignore'            => false,
        'from'              => [],
        'join'              => [],
        'value'             => [],
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
    public function ignore($flag = true)
    {
        $this->_parts['ignore'] = $flag;
        return $this;
    }

    /**
     * @param array $values
     * @return Update
     */
    public function value(array $values)
    {
        $this->_parts['value'] = $values;
        return $this;
    }

    protected function _build()
    {
        $parts = ['UPDATE'];

        # low priority?
        if ($this->_parts['lowPriority']) {
            $parts[] = 'LOW_PRIORITY';
        }

        # ignore?
        if ($this->_parts['ignore']) {
            $parts[] = 'IGNORE';
        }

        # from part
        $parts[] = $this->_buildFromPart(false);

        # join part
        if ($this->_parts['join']) {
            $parts[] = $this->_buildJoinPart();
        }

        # value part
        $values = $this->_parts['value'];
        foreach ($values as $column => &$value) {
            $value = self::quoteIdentifier($column) . '=' . self::quote($value);
        }
        $parts[] = 'SET ' . implode(', ', $values);

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