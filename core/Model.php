<?php

namespace Core;

use Core\Database;

/**
 * Base Model Class
 * All models should extend this class
 */
abstract class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Find record by ID
     * 
     * @param int $id
     * @return array|false
     */
    public function find($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        return $this->db->fetchOne($query, ['id' => $id]);
    }

    /**
     * Find all records
     * 
     * @param array $conditions
     * @param string $orderBy
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findAll($conditions = [], $orderBy = null, $limit = null, $offset = null)
    {
        $query = "SELECT * FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $column => $value) {
                $where[] = "{$column} = :{$column}";
                $params[$column] = $value;
            }
            $query .= " WHERE " . implode(' AND ', $where);
        }

        if ($orderBy) {
            $query .= " ORDER BY {$orderBy}";
        }

        if ($limit) {
            $query .= " LIMIT {$limit}";
        }

        if ($offset) {
            $query .= " OFFSET {$offset}";
        }

        return $this->db->fetchAll($query, $params);
    }

    /**
     * Find one record by conditions
     * 
     * @param array $conditions
     * @return array|false
     */
    public function findOne($conditions)
    {
        $where = [];
        $params = [];

        foreach ($conditions as $column => $value) {
            $where[] = "{$column} = :{$column}";
            $params[$column] = $value;
        }

        $query = "SELECT * FROM {$this->table} WHERE " . implode(' AND ', $where) . " LIMIT 1";
        return $this->db->fetchOne($query, $params);
    }

    /**
     * Create new record
     * 
     * @param array $data
     * @return int|false Last insert ID or false
     */
    public function create($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update record by ID
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $where = "{$this->primaryKey} = :id";
        return $this->db->update($this->table, $data, $where, ['id' => $id]);
    }

    /**
     * Delete record by ID
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $where = "{$this->primaryKey} = :id";
        return $this->db->delete($this->table, $where, ['id' => $id]);
    }

    /**
     * Count records
     * 
     * @param array $conditions
     * @return int
     */
    public function count($conditions = [])
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $column => $value) {
                $where[] = "{$column} = :{$column}";
                $params[$column] = $value;
            }
            $query .= " WHERE " . implode(' AND ', $where);
        }

        $result = $this->db->fetchOne($query, $params);
        return $result ? (int) $result['total'] : 0;
    }

    /**
     * Execute custom query
     * 
     * @param string $query
     * @param array $params
     * @return \PDOStatement|false
     */
    protected function query($query, $params = [])
    {
        return $this->db->query($query, $params);
    }

    /**
     * Fetch one with custom query
     * 
     * @param string $query
     * @param array $params
     * @return array|false
     */
    protected function fetchOne($query, $params = [])
    {
        return $this->db->fetchOne($query, $params);
    }

    /**
     * Fetch all with custom query
     * 
     * @param string $query
     * @param array $params
     * @return array
     */
    protected function fetchAll($query, $params = [])
    {
        return $this->db->fetchAll($query, $params);
    }

    /**
     * Begin transaction
     */
    public function beginTransaction()
    {
        return $this->db->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public function commit()
    {
        return $this->db->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback()
    {
        return $this->db->rollback();
    }
}
