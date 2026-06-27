<?php
require_once __DIR__ . '/../WebSystem/config/database.php';

$conn = \App\Config\Database::getConnection();
$conn->exec("ALTER TABLE pesquisador MODIFY senha VARCHAR(255) NOT NULL");
echo "Coluna senha alterada para VARCHAR(255).\n";
