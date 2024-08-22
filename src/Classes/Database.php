<?php

namespace App\Classes;

class Database
{
    private static $conn;

    public static function init($host, $username, $password, $db_name)
    {
        self::$conn = new \mysqli($host, $username, $password, $db_name);
    }

    public static function query($sql)
    {
        return self::$conn->query($sql);
    }

    public static function insert($table, $columns, $data)
    {
        $columns = implode(", ", $columns);
        $placeholders = implode(", ", array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        $stmt = self::$conn->prepare($sql);
        $types = str_repeat('s', count($data));
        $stmt->bind_param($types, ...$data);

        $stmt->execute();
        $stmt->close();
    }

    public static function select($table, $offset = 0, $needle = '*', $whereArg = null)
    {
        $where = ($whereArg !== null) ? "WHERE $whereArg" : '';
        $sql = "SELECT $needle FROM $table $where LIMIT 50 OFFSET $offset";
        return self::query($sql);
    }
}
