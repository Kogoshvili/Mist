<?php

namespace Mist\Models;

use Mist\Core\Database;

abstract class Model
{
    protected $db;
    public $data;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function __get($name)
    {
        return $this->data[$name] ?? NULL;
    }

    /**
     * Get all records from table
     *
     * @return array
     */
    public function all($limit = 50, $offset = 0)
    {
        $query = "SELECT * FROM $this->table LIMIT $limit OFFSET $offset";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get record by id
     *
     * @param int $id
     *
     * @return Post
     */
    public function get($id)
    {
        $query = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        $this->data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $this;
    }
}