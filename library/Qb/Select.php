<?php

namespace Qb;

use Qb\Part\From,
    Qb\Part\Join,
    Qb\Part\Where,
    Qb\Part\OrderBy,
    Qb\Part\Limit,
    Qb\Pdo;

/**
 *
 */
class Select extends AbstractQuery
{
    use From, Join, Where, OrderBy, Limit;

    protected $_defaultParts = [
        'distinct'          => false,
        'highPriority'      => false,
        'sizeResult'        => null,
        'cache'             => false,
        'calcFoundRows'     => false,
        'column'            => [],
        'from'              => [],
        'join'              => [],
        'where'             => [],
        'groupBy'           => [],
        'having'            => [],
        'orderBy'           => [],
        'limit'             => null,
        'offset'            => null,
        'into'              => null,
        'lockingReads'      => null
    ];

    /**
     * @param bool [$flag=true]
     * @return Select
     */
    public function distinct($flag = true)
    {
        $this->_parts['distinct']= $flag;
        return $this;
    }

    /**
     * @param bool [$flag=true]
     * @return bool
     */
    public function highPriority($flag = true)
    {
        $this->_parts['highPriority'] = $flag;
        return true;
    }

    /**
     * @param string $type
     * @return Select
     */
    protected function _sizeResult($type)
    {
        $this->_parts['sizeResult'] = $type;
        return $this;
    }

    /**
     * @return Select
     */
    protected function smallResult()
    {
        return $this->_sizeResult('SQL_SMALL_RESULT');
    }

    /**
     * @return Select
     */
    public function bigResult()
    {
        return $this->_sizeResult('SQL_BIG_RESULT');
    }

    /**
     *
     */
    public function bufferResult()
    {
        $this->_sizeResult = 'SQL_BUFFER_RESULT';
    }

    /**
     * @param string $type
     * @return Select
     */
    protected function _cache($type)
    {
        $this->_parts['cache'] = $type;
        return $this;
    }

    /**
     * @return Select
     */
    public function cache()
    {
        return $this->_cache('SQL_CACHE');
    }

    /**
     * @return Select
     */
    public function noCache()
    {
        return $this->_cache('SQL_NO_CACHE');
    }

    /**
     * @param bool [$flag=true]
     * @return Select
     */
    public function calcFoundRows($flag = true)
    {
        $this->_parts['calcFoundRows'] = $flag;
        return $this;
    }

    /**
     * @param string|array $columns
     * @return Select
     */
    public function column($columns)
    {
        if (!is_array($columns)) {
            $columns = [$columns];
        }

        foreach ($columns as $table => $column) {
            if (!is_array($column)) {
                $column = preg_split('/,\s*/', $column);
            }

            if (!is_numeric($table)) {
                foreach ($column as &$col) {
                    $col = "$table.$col";
                }
            }

            $this->_parts['column'] = array_merge($this->_parts['column'], $column);
        }

        return $this;
    }

    /**
     * @param string|array|Arguments $conditions
     * @return Select
     */
    public function groupBy($conditions)
    {
        $conditions = func_get_args();
        foreach ($conditions as $condition) {
            if (!is_array($condition)) {
                $condition = preg_split('/,\s*/', $condition);
            }
            $this->_parts['groupBy'] = array_merge($this->_parts['groupBy'], $condition);
        }

        return $this;
    }

    /**
     * @param string|array $conditions
     * @param Arguments [$params]
     * @return Select
     */
    public function having($conditions)
    {
        if (!is_array($conditions)) {
            $params = func_get_args();
            array_shift($params);

            $conditions = [$conditions => $params];
        }

        $this->_parts['having'] = array_merge($this->_parts['having'], $conditions);
        return $this;
    }

    /**
     * @param string $into
     * @return Select
     */
    public function into($into)
    {
        $this->_parts['into'] = $into;
        return $this;
    }

    /**
     * @param string $fileName
     * @param string [$charsetName]
     * @param string [$options]
     * @return Select
     */
    public function intoOutFile($fileName, $charsetName = null, $options = null)
    {
        $fileName = self::quote($fileName);
        $into = ["OUTFILE $fileName"];

        if ($charsetName) {
            $into[] = "CHARACTER SET $charsetName";
        }

        if ($options) {
            $into[] = $options;
        }

        return $this->into(implode(' ', $into));
    }

    /**
     * @param string $fileName
     * @return Select
     */
    public function intoDumpFile($fileName)
    {
        $fileName = self::quote($fileName);
        return $this->into("DUMPFILE $fileName");
    }

    /**
     * @return Select
     */
    public function forUpdate()
    {
        $this->_parts['lockingReads'] = 'FOR UPDATE';
        return $this;
    }

    /**
     * @return Select
     */
    public function lockInShareMode()
    {
        $this->_parts['lockingReads'] = 'LOCK IN SHARE MODE';
        return $this;
    }

    /**
     * @return string
     */
    protected function _build()
    {
        $parts = ['SELECT'];

        # is distinct?
        if ($this->_parts['distinct']) {
            $parts[] = 'DISTINCT';
        }

        # is high priority?
        if ($this->_parts['highPriority']) {
            $parts[] = 'HIGH_PRIORITY';
        }

        # size result?
        if ($this->_parts['sizeResult']) {
            $parts[] = $this->_parts['sizeResult'];
        }

        # cache
        if ($this->_parts['cache']) {
            $parts[] = $this->_parts['cache'];
        }

        # calc found rows?
        if ($this->_parts['calcFoundRows']) {
            $parts[] = 'SQL_CALC_FOUND_ROWS';
        }

        # column parts
        if (!$this->_parts['column']) {
            $parts[] = '*';
        } else {
            $columnParts = [];

            foreach ($this->_parts['column'] as $column) {
                $column = preg_split('/\s+/', $column);
                $part = self::quoteIdentifier($column[0]);
                if (2 == count($column)) {
                    $part .= ' ' . self::quoteIdentifier($column[1]);
                }

                $columnParts[] = $part;
            }

            $parts[] = implode(', ', $columnParts);
        }

        # from parts
        $parts[] = $this->_buildFromPart();

        # join parts
        if ($this->_parts['join']) {
            $parts[] = $this->_buildJoinPart();
        }

        # where parts
        if ($this->_parts['where']) {
            $parts[] = $this->_buildWherePart();
        }

        # group by parts
        if ($groupBy = $this->_parts['groupBy']) {
            foreach ($groupBy as &$column) {
                $column = self::quoteIdentifier($column);
            }

            $parts[] = 'GROUP BY ' . implode(', ', $groupBy);
        }

        # having parts
        if ($this->_parts['having']) {
            $havingParts = [];
            foreach ($this->_parts['having'] as $condition => $params) {
                if (is_numeric($condition)) {
                    $condition = $params;
                    $params = null;
                }

                if (!preg_match('/^(or|and) /i', $condition)) {
                    $condition = 'AND ' . $condition;
                }
                $havingParts[] = $params? self::bind($condition, $params) : $condition;
            }

            $havingParts = implode(' ', $havingParts);
            $havingParts = preg_replace('/^(or|and) /i', '', $havingParts);

            $parts[] = 'HAVING ' . $havingParts;
        }

        # order by parts
        if ($this->_parts['orderBy']) {
            $parts[] = $this->_buildOrderByPart();
        }

        # limit?
        if ($this->_parts['limit']) {
            $parts[] = $this->_buildLimitPart();
        }

        # into part
        if ($this->_parts['into']) {
            $parts[] = 'INTO ' . $this->_parts['into'];
        }

        # looking reads?
        if ($this->_parts['lockingReads']) {
            $parts[] = $this->_parts['lockingReads'];
        }

        return implode(' ', $parts);
    }

    /**
     * @param int [$fetchStyle]
     * @param array [$params]
     * @return array
     */
    public function fetch($fetchStyle = null, array $params = [])
    {
        if (is_array($fetchStyle)) {
            $params = $fetchStyle;
            $fetchStyle = null;
        }

        if (null === $fetchStyle) {
            $fetchStyle = Pdo::getFetchMode();
        }

        return $this->execute($params)->fetch($fetchStyle);
    }

    /**
     * @param int [$fetchStyle]
     * @param array [$params]
     * @return array
     */
    public function fetchAll($fetchStyle = null, array $params = [])
    {
        if (is_array($fetchStyle)) {
            $params = $fetchStyle;
            $fetchStyle = null;
        }

        if (null === $fetchStyle) {
            $fetchStyle = Pdo::getFetchMode();
        }

        return $this->execute($params)->fetchAll($fetchStyle);
    }

    /**
     * @param int|array [$col=0]
     * @param array [$params]
     * @return array|bool
     */
    public function fetchCol($col = 0, array $params = [])
    {
        if (is_array($col)) {
            $params = $col;
            $col = 0;
        }

        $rows = $this->fetchAll(Pdo::FETCH_BOTH, $params);

        if (!$rows) {
            return false;
        }

        $result = [];

        foreach ($rows as $row) {
            $result[] = $row[$col];
        }

        return $result;
    }

    /**
     * @param int|array [$col=0]
     * @param array [$params]
     * @return null
     */
    public function fetchCell($col = 0, array $params = [])
    {
        if (is_array($col)) {
            $params = $col;
            $col = 0;
        }

        $row = $this->fetch(Pdo::FETCH_BOTH, $params);

        if (!$row) {
            return null;
        }

        return $row[$col];
    }
}