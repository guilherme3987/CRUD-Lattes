<?php
require __DIR__ . '/../WebSystem/vendor/autoload.php';

$conn = \App\Config\Database::getConnection();
$conn->exec('ALTER TABLE pesquisador MODIFY COLUMN senha VARCHAR(255) NOT NULL');
echo "OK: coluna senha alterada para VARCHAR(255)\n";
