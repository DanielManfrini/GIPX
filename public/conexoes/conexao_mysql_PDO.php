<?php

$usuario_db = '';
$senha_db = '';
$database_db = '';
$host_db = '';

try {

    $dsn = "mysql:host=$host_db;dbname=$database_db;charset=utf8";
    $conn = new PDO($dsn, $usuario_db, $senha_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Resto do código...
} catch (PDOException $e) {
    
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
}
?>