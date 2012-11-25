<?php

namespace Qb\Part;

trait OrderBy
{
    /**
     * @param string|array|Arguments $conditions
     * @return Select
     */
    public function orderBy($conditions)
    {
        $conditions = func_get_args();
        foreach ($conditions as $condition) {
            if (!is_array($condition)) {
                $condition = preg_split('/,\s*/', $condition);
            }
            $this->_parts['orderBy'] = array_merge($this->_parts['orderBy'], $condition);
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function _buildOrderByPart()
    {
        foreach ($this->_parts['orderBy'] as &$column) {
            $column = preg_split('/\s+/', $column);
            $column[0] = self::quoteIdentifier($column[0]);
            $column = implode(' ', $column);
        }

        return 'ORDER BY ' . implode(', ', $this->_parts['orderBy']);
    }
}