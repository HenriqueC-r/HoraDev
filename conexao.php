<?php

require_once __DIR__ . "/config/env.php";

$host = $_ENV["DB_HOST"];
$banco = $_ENV["DB_NAME"];
$usuario = $_ENV["DB_USER"];
$senha = $_ENV["DB_PASS"];

try {
    $pdo = new PDO(

"mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha    
    );

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "Erro na conexão " . $e->getMessage();
}



?>