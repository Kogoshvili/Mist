<?php

namespace Mist\Core;

class Database extends \PDO
{
    protected static $config = [];

    function __construct()
    {
        if (empty(self::$config)) {
            self::$config = include_once CONFIG . 'database.php';
        }

        $string = sprintf(
            self::$config['dns'], self::$config['driver'], self::$config['host'], self::$config['port'], self::$config['database']
        );

        parent::__construct($string, self::$config['username'], self::$config['password']);
        parent::setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
