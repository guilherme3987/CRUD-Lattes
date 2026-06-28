<?php
namespace App\Config;
use PDO;
use PDOException;
class Database {
    private static $conexao = null;

    public static function conectar() {
        if (self::$conexao === null) {
            $env = parse_ini_file(__DIR__ . '/../.env');
            $url = $env['DATABASE_URL'];

            // O PHP analisa a URI e extrai os componentes automaticamente
            $dbparts = parse_url($url);

            $host = $dbparts['host'];
            $port = $dbparts['port'];
            $user = $dbparts['user'];
            $pass = $dbparts['pass'];
            // Remove a barra inicial do path para pegar o nome do banco
            $db   = ltrim($dbparts['path'], '/'); 

            try {
                self::$conexao = new PDO("mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4", $user, $pass);
                self::$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro na conexão com o Aiven via URI: " . $e->getMessage());
            }
        }
        return self::$conexao;
    }
}