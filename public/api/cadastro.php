<?php

require_once __DIR__ . "/../../config/cors.php";
require_once __DIR__ . "/../../conexao.php";

$dados = json_decode(file_get_contents("php://input"), true);

if (
    !isset($dados["nome"]) ||
    !isset($dados["email"]) ||
    !isset($dados["senha"])
) {
    http_response_code(400);

    echo json_encode([
        "status" => false,
        "mensagem" => "Dados incompletos."
    ]);

    exit;
}

$nome = trim($dados["nome"]);
$email = trim($dados["email"]);
$senha = password_hash($dados["senha"], PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios(nome, email, senha)
        VALUES(:nome, :email, :senha)";

$stmt = $pdo->prepare($sql);

try {

    $stmt->execute([
        ":nome" => $nome,
        ":email" => $email,
        ":senha" => $senha
    ]);

    http_response_code(201);

    echo json_encode([
        "status" => true,
        "mensagem" => "Conta cadastrada com sucesso."
    ]);

} catch (PDOException $e) {

    if ($e->getCode() == "23000") {

        http_response_code(409);

        echo json_encode([
            "status" => false,
            "mensagem" => "E-mail já cadastrado."
        ]);

    } else {

        http_response_code(500);

        echo json_encode([
            "status" => false,
            "mensagem" => "Erro interno do servidor."
        ]);

    }
}
