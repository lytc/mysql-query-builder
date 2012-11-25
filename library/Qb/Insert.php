<?php

namespace Qb;

/**
 *
 */
class Insert extends AbstractQuery
{
    /**
     * @var array
     */
    protected $_defaultParts = [
        'priority'              => null,
        'ignore'                => false,
        'into'                  => null,
        'column'               => null,
        'value'                => null,
        'onDuplicateKeyUpdate'  => [],
    ];

    /**
     * @param string $priority
     * @return Insert
     */
    protected function _priority($priority)
    {
        $this->_parts['priority'] = $priority;
        return $this;
    }

    /**
     * @return Insert
     */
    public function lowPriority()
    {
        return $this->_priority('LOW_PRIORITY');
    }

    /**
     * @return Insert
     */
    public function delayed()
    {
        return $this->_priority('DELAYED');
    }

    /**
     * @return Insert
     */
    public function highPriority()
    {
        return $this->_priority('HIGH_PRIORITY');
    }

    /**
     * @param bool [$flag=true]
     * @return Insert
     */
    public function ignore($flag = true)
    {
        $this->_parts['ignore'] = $flag;
        return $this;
    }

    /**
     * @param string $table
     * @return Insert
     */
    public function into($table)
    {
        $this->_parts['into'] = $table;
        return $this;
    }

    /**
     * @param string|array $columns
     * @return Insert
     */
    public function column($columns)
    {
        $this->_parts['column'] = $columns;
        return $this;
    }

    /**
     * @param string|array|Select $values
     * @return Insert
     */
    public function value($values)
    {
        $this->_parts['value'] = $values;
        return $this;
    }

    /**
     * @param array $data
     * @return Insert
     */
    public function onDuplicateKeyUpdate(array $data)
    {
        $this->_parts['onDuplicateKeyUpdate'] = $data;
        return $this;
    }

    /**
     * @return string
     */
    protected function _build()
    {
        $parts = ['INSERT'];

        if ($this->_parts['priority']) {
            $parts[] = $this->_parts['priority'];
        }

        if ($this->_parts['ignore']) {
            $parts[] = 'IGNORE';
        }

        # into
        $into = preg_split('/\s+/', $this->_parts['into']);
        $intoPart = self::quoteIdentifier($into[0]);
        if (2 == count($into)) {
            $intoPart .= ' ' . self::quoteIdentifier($into[1]);
        }
        $parts[] = $intoPart;

        if (is_array($values = $this->_parts['value'])) {
            if (!is_array(current($values))) {
                $values = [$values];
            }
        }

        # columns
        $columns = $this->_parts['column'];
        if (!$columns && is_array($values)) {
            $columns = array_keys(current($values));
        }

        if (is_string($columns)) {
            $columns = preg_split('/,\s*/', $columns);
        }

        if ($columns) {
            foreach ($columns as &$column) {
                $column = self::quoteIdentifier($column);
            }
            $parts[] = '(' . implode(', ', $columns) . ')';
        }

        # values
        if (!is_array($values)) {
            $parts[] = $values . '';
        } else {
            $valueParts = [];
            foreach ($values as $value) {
                $value = self::quote($value);
                $valueParts[] = '(' . implode(', ', $value) . ')';
            }
            $parts[] = 'VALUES ' . implode(', ', $valueParts);
        }

        # on duplicate key update
        if ($data = $this->_parts['onDuplicateKeyUpdate']) {
            $updateParts = [];
            foreach ($data as $column => $value) {
                $updateParts[] = self::quoteIdentifier($column) . '=' . self::quote($value);
            }
            $parts[] = 'ON DUPLICATE KEY UPDATE ' . implode(', ', $updateParts);
        }

        return implode(' ', $parts);
    }

    /**
     * @param array [$params]
     * @param array [$driverOptions]
     * @return string
     */
    public function execute(array $params = [], array $driverOptions = [])
    {
        parent::execute($params, $driverOptions);
        return Pdo::getInstance()->lastInsertId();
    }
}