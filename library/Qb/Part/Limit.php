<?php

namespace Qb\Part;

trait Limit
{
    /**
     * @param int $limit
     * @param int [$offset]
     * @return Select
     */
    public function limit($limit, $offset = null)
    {
        $this->_parts['limit'] = $limit;

        if (null !== $offset) {
            $this->offset($offset);
        }

        return $this;
    }

    /**
     * @param int $offset
     * @return Select
     */
    public function offset($offset)
    {
        $this->_parts['offset'] = $offset;
        return $this;
    }

    /**
     * @return string
     */
    protected function _buildLimitPart()
    {
        $limitPart = 'LIMIT ' . (int) $this->_parts['limit'];
        if ($this->_parts['offset']) {
            $limitPart .= ' OFFSET ' . (int) $this->_parts['offset'];
        }

        return $limitPart;
    }
}