<?php

namespace Qb\Part;

trait Where
{
    /**
     * @param string|array $conditions
     * @param Arguments [$params]
     * @return Select
     */
    public function where($conditions)
    {
        if (!is_array($conditions)) {
            $params = func_get_args();
            array_shift($params);

            $conditions = [$conditions => $params];
        }

        $this->_parts['where'] = array_merge($this->_parts['where'], $conditions);
        return $this;
    }

    /**
     * @return string
     */
    protected function _buildWherePart()
    {
        $whereParts = [];
        foreach ($this->_parts['where'] as $condition => $params) {
            if (is_numeric($condition)) {
                $condition = $params;
                $params = null;
            }

            if (!preg_match('/^(or|and) /i', $condition)) {
                $condition = 'AND ' . $condition;
            }
            $whereParts[] = $params? self::bind($condition, $params) : $condition;
        }

        $whereParts = implode(' ', $whereParts);
        $whereParts = preg_replace('/^(or|and) /i', '', $whereParts);

        return 'WHERE ' . $whereParts;
    }
}