<?php
namespace App\Config;
use PDO;
use PDOException;
class Database {
    private static $conexao = null;

    public static function conectar() {
        if (self::$conexao === null) {
            $url = getenv('DATABASE_URL');

            if (!$url) {
                $env = parse_ini_file(__DIR__ . '/../.env');
                $url = $env ? $env['DATABASE_URL'] : null;
            }

            if (!$url) {
                die("DATABASE_URL não configurada. Defina a variável de ambiente DATABASE_URL.");
            }

            $dbparts = parse_url($url);

            $host = $dbparts['host'] ?? '';
            $port = $dbparts['port'] ?? '3306';
            $user = $dbparts['user'] ?? '';
            $pass = $dbparts['pass'] ?? '';
            $db   = isset($dbparts['path']) ? ltrim($dbparts['path'], '/') : '';

            try {
                self::$conexao = new PDO("mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4", $user, $pass);
                self::$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro na conexão com o banco: " . $e->getMessage());
            }
        }
        return self::$conexao;
    }
}