<?php

namespace Mist\Core;

class Database extends \PDO
{
    function __construct()
    {
        $config = include_once CONFIG . 'database.php';
        $string = sprintf(
            $config['dns'], $config['host'], $config['port'], $config['database']
        );
        parent::__construct($string, $config['username'], $config['password']);
        parent::setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Execute query
     *
     * @param string $query Query to be execute
     * @param array  $data  Data for query
     *
     * @return void
     */
    public function execute($query, $data = [])
    {
        $stmt = $this->prepare($query);
        return $stmt->execute($data);
    }
}
