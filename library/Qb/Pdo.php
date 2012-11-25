<?php

namespace Qb;

/**
 *
 */
class Pdo extends \PDO
{
    /**
     * @var string
     */

    protected static $_dsn;
    /**
     * @var string
     */

    protected static $_username;
    /**
     * @var string
     */

    protected static $_password;
    /**
     * @var array
     */

    protected static $_driverOptions = [];

    /**
     * @var int
     */
    protected static $_fetchStyle = \PDO::FETCH_ASSOC;

    /**
     * @var array
     */
    protected static $_attributes = [
        \PDO::ATTR_ERRMODE   => \PDO::ERRMODE_EXCEPTION
    ];

    /**
     * @var bool
     */
    protected $_logFile = false;

    /**
     * @var Pdo
     */
    protected static $_instance;

    /**
     * @param string $dsn
     */
    public static function setDsn($dsn)
    {
        self::$_dsn = $dsn;
    }

    /**
     * @return string
     */
    public static function getDsn()
    {
        return self::$_dsn;
    }

    /**
     * @param string $username
     */
    public static function setUsername($username)
    {
        self::$_username = $username;
    }

    /**
     * @return string
     */
    public static function getUsername()
    {
        return self::$_username;
    }

    /**
     * @param string $password
     */
    public static function setPassword($password)
    {
        self::$_password = $password;
    }

    /**
     * @return string
     */
    public static function getPassword()
    {
        return self::$_password;
    }

    /**
     * @param array $driverOptions
     */
    public static function setDriverOptions($driverOptions)
    {
        self::$_driverOptions = $driverOptions;
    }

    /**
     * @return array
     */
    public static function getDriverOptions()
    {
        return self::$_driverOptions;
    }

    /**
     * @param array $attributes
     */
    public static function setAttributes(array $attributes)
    {
        self::$_attributes = $attributes;
    }

    /**
     * @return array
     */
    public static function getAttributes()
    {
        return self::$_attributes;
    }

    /**
     * @param int $fetchStyle
     */
    public static function setFetchMode($fetchStyle)
    {
        self::$_fetchStyle = $fetchStyle;
    }

    /**
     * @return int
     */
    public static function getFetchMode()
    {
        return self::$_fetchStyle;
    }

    /**
     * @param string [$dsn]
     * @param string [$username]
     * @param string [$password]
     * @param array [$driverOptions]
     */
    public function __construct($dsn = null, $username = null, $password = null, array $driverOptions = null)
    {
        if (!$dsn) {
            $dsn = self::$_dsn;
        }

        if (!$username) {
            $username = self::$_username;
        }

        if (!$password) {
            $password = self::$_password;
        }

        if (!$driverOptions) {
            $driverOptions = self::$_driverOptions;
        }

        parent::__construct($dsn, $username, $password, $driverOptions);

        foreach (self::$_attributes as $name => $value) {
            $this->setAttribute($name, $value);
        }
    }

    /**
     * @param string [$dsn]
     * @param string [$username]
     * @param string [$password]
     * @param array $driverOptions
     * @return Pdo
     */
    public static function getInstance($dsn = null, $username = null, $password = null, array $driverOptions = null)
    {
        if (!self::$_instance) {
            self::$_instance = new self($dsn, $username, $password, $driverOptions);
        }

        return self::$_instance;
    }

    /**
     * @param $file
     * @return Pdo
     */
    public function setLog($file)
    {
        $this->_logFile = $file;
        return $this;
    }

    /**
     * @param $message
     * @return Pdo
     */
    public function log($message)
    {
        if (!$this->_logFile) {
            return $this;
        }

        file_put_contents($this->_logFile, $message, FILE_APPEND);
        return $this;
    }
}