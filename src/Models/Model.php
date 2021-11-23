<?php

namespace Mist\Models;

use Mist\Core\Database;

abstract class Model implements \JsonSerializable
{
    /**
     * Model table name
     *
     * @var string
     */
    protected $table;

    /**
     * list of properties to hide after serialization
     *
     * @var array
     */
    protected $hide = [];

    /**
     * Database instance
     *
     * @var Database
     */
    protected $db;

    /**
     * Raw data from database
     *
     * @var array
     */
    protected $rawData;

    /**
     * Raw query
     *
     * @var string
     */
    protected $rawQuery;

    /**
     * Columns to select
     *
     * @var array
     */
    protected $select = [];

    /**
     * Where conditions
     *
     * @var array
     */
    protected $where = [];

    /**
     * Values to bind to where conditions
     *
     * @var array
     */
    protected $whereValues = [];

    /**
     * Values to bind
     *
     * @var array
     */
    protected $values = [];

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function __get($name)
    {
        return $this->rawData[$name] ?? null;
    }

    public function jsonSerialize()
    {
        $result = $this->rawData;

        foreach($this->hide as $hide) {
            unset($result[$hide]);
        }

        return $result;
    }

    /**
     * Get all records from table
     *
     * @param int $limit limit default 50
     * @param int $offset offset default 0
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
     * Get data
     *
     * @param int $id
     *
     * @return Post
     */
    public function get($id = null)
    {
        if ($id === null) {
            $query = $this->_getSelectQuery() . " FROM $this->table " . $this->_getWhereQuery();
            $stmt = $this->db->prepare($query);
            $stmt->execute($this->whereValues);
            $this->_cleanup();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $this->getById($id);
    }

    /**
     * Get record by id
     *
     * @param int $id
     *
     * @return Post
     */
    public function getById($id)
    {
        $query = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        $this->rawData = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $this;
    }

    /**
     * Save select parameters
     *
     * @param string $column column
     *
     * @return Model
     */
    public function select($columns)
    {
        $this->select = $columns;
        return $this;
    }

    /**
     * Get select query part
     *
     * @return string
     */
    private function _getSelectQuery()
    {
        $length = count($this->select);

        if ($length === 0) {
            return 'SELECT *';
        }

        $index = 1;
        $fields = '';

        foreach ($this->select as $value) {
            $fields .= $value . ($index < $length ? ', ' : '');
            $index++;
        }

        $query = "SELECT $fields";
        return $query;
    }

    /**
     * Save where parameters
     *
     * @param string $column column
     * @param string $operator operator
     * @param string $value value
     *
     * @return Model
     */
    public function where($column, $operator, $value)
    {
        $this->where[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value
        ];

        return $this;
    }

    /**
     * Get where query part
     *
     * @return string
     */
    private function _getWhereQuery()
    {
        $length = count($this->where);
        $query = '';

        foreach ($this->where as $index => $value) {
            $query = 'WHERE ' . $value['column'] . ' ' . $value['operator'] . ' :' . 'where_' . $value['column'] . ($index + 1 < $length ? ' AND ' : ' ');
            $this->whereValues = array_merge($this->whereValues, ['where_' . $value['column'] => $value['value']]);
        }

        return $query;
    }

    /**
     * Insert data
     *
     * @param array $data key value pairs
     *
     * @return Model
     */
    public function insert($data)
    {
        $length = count($data);
        $index = 1;
        $fields = '';
        $values = '';

        foreach ($data as $key => $value) {
            $fields .= $key . ($index < $length ? ', ' : '');
            $values .= ':' . $key . ($index < $length ? ', ' : '');
            $index++;
        }

        $this->rawQuery = "INSERT INTO $this->table ($fields) VALUES ($values)";
        $this->values = array_merge($this->values, $data);
        return $this;
    }

    /**
     * Update data
     *
     * @param array $data key value pairs
     *
     * @return Model
     */
    public function update($data)
    {
        $length = count($data);
        $index = 1;
        $pair = '';

        foreach ($data as $key => $value) {
            $pair .= $key . ' = :' . $key . ($index < $length ? ', ' : '');
            $index++;
        }

        $this->rawQuery = "UPDATE $this->table SET $pair";
        $this->values = array_merge($this->values, $data);
        return $this;
    }

    /**
     * Execute statement
     *
     * @return void
     */
    public function execute()
    {
        $stmt = $this->db->prepare($this->rawQuery . " " . $this->_getWhereQuery());
        $result = $stmt->execute(array_merge($this->whereValues, $this->values));
        $this->_cleanup();
        return $result;
    }

    /**
     * Reset parameters
     *
     * @return void
     */
    private function _cleanup()
    {
        $this->rawQuery = '';
        $this->select = [];
        $this->where = [];
        $this->whereValues = [];
        $this->values = [];
    }
}
