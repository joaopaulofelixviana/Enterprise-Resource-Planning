<?php
class Database {
    private static $host = 'localhost';
    private static $db = 'mini_erp';
    private static $user = 'root';
    private static $pass = 'root';
    public static function connect() {
        try {
            $pdo = new PDO('mysql:host='.self::$host.';dbname='.self::$db, self::$user, self::$pass, [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ]);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
    }
} 