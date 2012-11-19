<?php

namespace Qb;

/**
 *
 */
abstract class AbstractQuery
{
    /**
     * @return string
     */
    abstract protected function _build();

    /**
     * @var string
     */
    protected static $_escapeCallback = 'addslashes';

    /**
     * @param $name
     * @return string
     */
    public static function quoteIdentifier($name)
    {
        $parts = explode('.', $name);
        foreach ($parts as &$part) {
            if ($part[0] == '`') {
                continue;
            }

            if (!preg_match('/^[\w\d_]+$/', $part)) {
                continue;
            }

            $part = "`$part`";
        }

        return implode('.', $parts);
    }

    /**
     * @param $callback
     */
    public static function setEscapeCallback($callback)
    {
        self::$_escapeCallback = $callback;
    }

    /**
     * @param string $str
     * @return string
     */
    public static function escape($str)
    {
        return call_user_func(self::$_escapeCallback, $str);
    }

    /**
     * @param string $str
     * @param bool [$doubleQuote=false]
     * @return string
     */
    public static function quote($str, $doubleQuote = false)
    {
        if ($str instanceof Expr || $str instanceof self ) {
            return $str->__toString();
        }

        if (is_object($str)) {
            $str .= '';
        }

        if (true === $str) {
            return 1;
        }

        if (false === $str) {
            return 0;
        }

        if (is_array($str)) {
            foreach ($str as &$item) {
                $item = self::quote($item, $doubleQuote);
            }
            return $str;
        }

        switch (gettype($str)) {
            case 'integer':
            case 'double':
            case 'double':
            case 'NULL':
                return $str;

            default:
                if (is_numeric($str)) {
                    return $str;
                }
                $char = $doubleQuote? '"' : "'";
                return $char . self::escape($str) . $char;

        }
    }

    public static function bind($str, $params = [])
    {
        if (!is_array($params)) {
            $params = [$params];
        }

        foreach ($params as &$param) {
            if (is_array($param)) {
                $param = implode(', ', self::quote($param));
            } else {
                $param = self::quote($param);
            }
        }

        if (($questionMarkCount = substr_count($str, '?')) > ($paramCount = count($params))) {
            $last = end($params);
            $params = array_merge($params, array_fill(0, $questionMarkCount - $paramCount, $last));
        }

        $str = str_replace('?', '%s', $str);

        if ($questionMarkCount == 1 && !is_array(current($params))) {
            $params = implode(', ', $params);
        }

        $str = vsprintf($str, $params);
        return $str;
    }

    /**
     * @var array
     */
    protected $_parts           = [];

    /**
     *
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * @param String|Array $parts
     * @return Select
     * @throws Exception
     */
    public function reset($parts = null)
    {
        if (null === $parts) {
            $this->_parts = $this->_defaultParts;
            return $this;
        }

        $args   = func_get_args();
        $parts  = [];
        foreach ($args as $arg) {
            if (!is_array($arg)) {
                $arg = preg_split('/,\s*/', $arg);
            }
            $parts = array_merge($parts, $arg);
        }

        foreach ($parts as $part) {
            if (!array_key_exists($part, $this->_defaultParts)) {
                throw new Exception(sprintf('Part %s', $part));
            }
            $this->_parts[$part] = $this->_defaultParts[$part];
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->_build();
    }
}