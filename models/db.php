<?php
class DB
{
    private static $connection;
    
    public static function connect()
    {
        require('config.php');
        $host = $config['DB_HOST'];
        $username = $config['DB_USERNAME'];
        $password = $config['DB_PASSWORD'];
        $database = $config['DB_DATABASE'];
        self::$connection = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function insert($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        $statement = self::$connection->prepare($query);
        
        foreach ($data as $column => &$value) {
            $statement->bindParam(":$column", $value);
        }
        
        $statement->execute();
        
        return $statement;
        return self::$connection->lastInsertId();
    }



    public static function update($table, $data, $where)
    {
        $set = "";
        foreach ($data as $column => $value) {
            $set .= "$column = :$column, ";
        }
        $set = rtrim($set, ", ");
        $query = "UPDATE $table SET $set WHERE $where";

        $statement = self::$connection->prepare($query);
        $statement->execute($data);

        return $statement->rowCount();
    }

    public static function delete($table, $where)
    {
        $query = "DELETE FROM $table WHERE $where";

        $statement = self::$connection->prepare($query);
        $statement->execute();

        return $statement->rowCount();
    }

    public static function select($table, $columns = "*", $where = "", $orderBy = "", $limit = "")
    {
        $query = "SELECT $columns FROM $table";

        if ($where) {
            $query .= " WHERE $where";
        }

        if ($orderBy) {
            $query .= " ORDER BY $orderBy";
        }

        if ($limit) {
            $query .= " LIMIT $limit";
        }
        //return $query;
        $statement = self::$connection->prepare($query);
        $statement->execute();

        return $statement;
    }

    public static function query($query, $bindings = [])
    {
        $statement = self::$connection->prepare($query);
        $statement->execute($bindings);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function count($table, $where = "")
    {
        $query = "SELECT COUNT(*) as count FROM $table";

        if ($where) {
            $query .= " WHERE $where";
        }

        $statement = self::$connection->prepare($query);
        $statement->execute();

        return $statement->fetchColumn();
    }

    public static function exists($table, $where = "")
    {
        $query = "SELECT EXISTS(SELECT 1 FROM $table";

        if ($where) {
            $query .= " WHERE $where";
        }

        $query .= ") as result";

        $statement = self::$connection->prepare($query);
        $statement->execute();

        return $statement->fetchColumn();
    }

    public static function truncate($table)
    {
        $query = "TRUNCATE TABLE $table";

        $statement = self::$connection->prepare($query);
        $statement->execute();

        return $statement->rowCount();
    }

    public static function execute($query, $bindings = [])
    {
        $statement = self::$connection->prepare($query);
        $statement->execute($bindings);

        return $statement->rowCount();
    }

    public static function beginTransaction()
    {
        self::$connection->beginTransaction();
    }

    public static function commit()
    {
        self::$connection->commit();
    }

    public static function rollback()
    {
        self::$connection->rollBack();
    }

    public static function close()
    {
        self::$connection = null;
    }

    public static function setConnection(PDO $connection)
    {
        self::$connection = $connection;
    }

    public static function getConnection()
    {
        return self::$connection;
    }

    public static function isConnected()
    {
        return self::$connection !== null;
    }
}
