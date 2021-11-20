<?php

namespace Vitra\Core;

class Database extends \PDO
{
    private static $_host = '127.0.0.1';
    private static $_port = '3307';
    private static $_dbname = 'database123';
    private static $_username = 'root';
    private static $_password = 'toor';

    function __construct()
    {
        $string = sprintf(
            'mysql:host=%s;port=%s;dbname=%s',
            self::$_host, self::$_port, self::$_dbname
        );
        parent::__construct($string, self::$_username, self::$_password);
        parent::setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Execute query
     *
     * @param string $query Query to be execute
     * @param array  $data Data for query
     *
     * @return void
     */
    public function execute($query, $data = [])
    {
        $stmt = $this->prepare($query);
        return $stmt->execute($data);
    }
}
