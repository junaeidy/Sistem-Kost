<?php

namespace Core;

use PDO;
use PDOException;

/**
 * Database Class - Singleton Pattern
 * Handles database connection using PDO
 */
class Database
{
    private static $instance = null;
    private $connection;
    private $config;

    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct()
    {
        $this->config = require dirname(__DIR__) . '/config/database.php';
        $this->connect();
    }

    /**
     * Get singleton instance of Database
     * 
     * @return Database
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Establish database connection
     */
    private function connect()
    {
        try {
            $dsn = sprintf(
                "%s:host=%s;port=%s;dbname=%s;charset=%s",
                $this->config['driver'],
                $this->config['host'],
                $this->config['port'],
                $this->config['database'],
                $this->config['charset']
            );

            $this->connection = new PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                $this->config['options']
            );

            // Set collation
            $this->connection->exec("SET NAMES {$this->config['charset']} COLLATE {$this->config['collation']}");
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    /**
     * Get PDO connection
     * 
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Execute a query and return statement
     * 
     * @param string $query
     * @param array $params
     * @return \PDOStatement|false
     */
    public function query($query, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            $this->handleError($e);
            return false;
        }
    }

    /**
     * Fetch single row
     * 
     * @param string $query
     * @param array $params
     * @return array|false
     */
    public function fetchOne($query, $params = [])
    {
        $stmt = $this->query($query, $params);
        return $stmt ? $stmt->fetch() : false;
    }

    /**
     * Fetch all rows
     * 
     * @param string $query
     * @param array $params
     * @return array
     */
    public function fetchAll($query, $params = [])
    {
        $stmt = $this->query($query, $params);
        return $stmt ? $stmt->fetchAll() : [];
    }

    /**
     * Insert data and return last insert ID
     * 
     * @param string $table
     * @param array $data
     * @return int|false
     */
    public function insert($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $query = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        if ($this->query($query, $data)) {
            return $this->connection->lastInsertId();
        }
        
        return false;
    }

    /**
     * Update data
     * 
     * @param string $table
     * @param array $data
     * @param string $where
     * @param array $whereParams
     * @return bool
     */
    public function update($table, $data, $where, $whereParams = [])
    {
        $set = [];
        foreach (array_keys($data) as $column) {
            $set[] = "{$column} = :{$column}";
        }
        $setClause = implode(', ', $set);
        
        $query = "UPDATE {$table} SET {$setClause} WHERE {$where}";
        
        $params = array_merge($data, $whereParams);
        
        return $this->query($query, $params) !== false;
    }

    /**
     * Delete data
     * 
     * @param string $table
     * @param string $where
     * @param array $params
     * @return bool
     */
    public function delete($table, $where, $params = [])
    {
        $query = "DELETE FROM {$table} WHERE {$where}";
        return $this->query($query, $params) !== false;
    }

    /**
     * Begin transaction
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public function commit()
    {
        return $this->connection->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback()
    {
        return $this->connection->rollBack();
    }

    /**
     * Handle database errors
     * 
     * @param PDOException $e
     */
    private function handleError(PDOException $e)
    {
        // Log error
        error_log("Database Error: " . $e->getMessage());
        
        // Show error in development mode
        $appConfig = require dirname(__DIR__) . '/config/app.php';
        if ($appConfig['debug']) {
            die("Database Error: " . $e->getMessage());
        } else {
            die("An error occurred. Please try again later.");
        }
    }

    /**
     * Prevent cloning of singleton
     */
    private function __clone() {}

    /**
     * Prevent unserialization of singleton
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}
