<?php

namespace App\Config;

class Database {
    private static ?\PDO $instance = null;

    public static function getConnection(): \PDO {
        if (self::$instance === null) {
            $env = parse_ini_file(__DIR__ . '/../.env');
            $host = $env['DB_HOST'] ?? 'localhost';
            $user = $env['DB_USER'] ?? 'root';
            $pass = $env['DB_PASSWORD'] ?? '';
            $name = $env['DB_NAME'] ?? 'database_lattes';
            $port = $env['DB_PORT'] ?? '3306';

            self::$instance = new \PDO(
                "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4",
                $user,
                $pass,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        }
        return self::$instance;
    }
}
