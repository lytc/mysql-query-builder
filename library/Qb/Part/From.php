<?php

namespace Qb\Part;

trait From
{
    /**
     * @param String|Array|Arguments $tables
     * @return Select
     */
    public function from($tables)
    {
        $tables = func_get_args();
        foreach ($tables as $table) {
            if (!is_array($table)) {
                $table = preg_split('/,\s*/', $table);
            }
            $this->_parts['from'] = array_merge($this->_parts['from'], $table);
        }

        return $this;
    }

    /**
     * @return String
     */
    protected function _buildFromPart($fromPrefix = true)
    {
        $fromParts = [];

        foreach ($this->_parts['from'] as $table) {
            $table = preg_split('/\s+/', $table);
            $part = self::quoteIdentifier($table[0]);
            if (2 == count($table)) {
                $part .= ' ' . self::quoteIdentifier($table[1]);
            }
            $fromParts[] = $part;
        }

        return ($fromPrefix? "FROM " : '') . implode(', ', $fromParts);
    }
}