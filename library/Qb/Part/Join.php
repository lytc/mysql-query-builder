<?php

namespace Qb\Part;

trait Join
{
    /**
     * @param String $type
     * @param String $table
     * @param String [$conditions]
     * @return Select
     */
    protected function _join($type, $table, $conditions = null)
    {
        $this->_parts['join'][] = ['type' => $type, 'table' => $table, 'conditions' => $conditions];
        return $this;
    }

    /**
     * @param String $table
     * @param String [$conditions]
     * @return Select
     */
    public function innerJoin($table, $conditions = null)
    {
        return $this->_join('INNER JOIN', $table, $conditions);
    }

    /**
     * @param String $table
     * @param String [$conditions]
     * @return Select
     */
    public function straightJoin($table, $conditions = null)
    {
        return $this->_join('STRAIGHT_JOIN', $table, $conditions);
    }

    /**
     * @param String $table
     * @param String $conditions
     * @return Select
     */
    public function leftJoin($table, $conditions)
    {
        return $this->_join('LEFT JOIN', $table, $conditions);
    }

    /**
     * @param String $table
     * @param String $conditions
     * @return Select
     */
    public function rightJoin($table, $conditions)
    {
        return $this->_join('RIGHT JOIN', $table, $conditions);
    }

    /**
     * @param String $table
     * @param String $conditions
     * @return Select
     */
    public function leftOuterJoin($table, $conditions)
    {
        return $this->_join('LEFT OUTER JOIN', $table, $conditions);
    }

    /**
     * @param String $table
     * @param String $conditions
     * @return Select
     */
    public function rightOuterJoin($table, $conditions)
    {
        return $this->_join('RIGHT OUTER JOIN', $table, $conditions);
    }

    /**
     * @param String $table
     * @return Select
     */
    public function naturalJoin($table)
    {
        return $this->_join('NATURAL JOIN', $table);
    }

    /**
     * @param String $table
     * @return Select
     */
    public function naturalLeftJoin($table)
    {
        return $this->_join('NATURAL LEFT JOIN', $table);
    }

    /**
     * @param String $table
     * @return Select
     */
    public function naturalRightJoin($table)
    {
        return $this->_join('NATURAL RIGHT JOIN', $table);
    }

    /**
     * @param String $table
     * @return Select
     */
    public function naturalLeftOuterJoin($table)
    {
        return $this->_join('NATURAL LEFT OUTER JOIN', $table);
    }

    /**
     * @param String $table
     * @return Select
     */
    public function naturalRightOuterJoin($table)
    {
        return $this->_join('NATURAL RIGHT OUTER JOIN', $table);
    }

    /**
     * @return String
     */
    protected function _buildJoinPart()
    {
        $joinParts = [];
        foreach ($this->_parts['join'] as $join) {
            $part = [$join['type']];
            $table = preg_split('/\s+/', $join['table']);
            $part[] = self::quoteIdentifier($table[0]);
            if (2 == count($table)) {
                $part[] = self::quoteIdentifier($table[1]);
            }

            $conditions = $join['conditions'];
            if ($conditions) {
                if (false !== strpos($conditions, ',') || preg_match('/^([\w\d+]+)$/', $conditions)) {
                    $joinConditionType = 'USING';
                    $conditions = preg_split('/,\s+/', $conditions);
                    foreach ($conditions as &$condition) {
                        $condition = self::quoteIdentifier($condition);
                    }
                    $conditions = $joinConditionType . ' ' . implode(', ', $conditions);
                } else {
                    $conditions = 'ON ' . $conditions;
                }
                $part[] = $conditions;
            }
            $joinParts[] = implode(' ', $part);
        }
        return implode(' ', $joinParts);
    }
}