<?php

require_once __DIR__ . "/../../../config/cors.php";
require_once __DIR__ . "/../../../config/verificarToken.php";
require_once __DIR__ . "/../../../conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["status" => false, "mensagem" => "Método não permitido."]);
    exit;
}

$usuario = verificarToken();
$dados = json_decode(file_get_contents("php://input"), true);
$projetoId = filter_var($dados["projeto_id"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
$comentario = trim($dados["comentario"] ?? "");

if (!$projetoId || $comentario === "") {
    http_response_code(400);
    echo json_encode(["status" => false, "mensagem" => "Projeto e comentário são obrigatórios."]);
    exit;
}
if (mb_strlen($comentario) > 5000) {
    http_response_code(422);
    echo json_encode(["status" => false, "mensagem" => "O comentário deve ter no máximo 5000 caracteres."]);
    exit;
}

$projeto = $pdo->prepare("SELECT id FROM projetos WHERE id = :id AND usuario_id = :usuario_id");
$projeto->execute([":id" => $projetoId, ":usuario_id" => $usuario->id]);
if (!$projeto->fetch()) {
    http_response_code(404);
    echo json_encode(["status" => false, "mensagem" => "Projeto não encontrado."]);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO comentarios_projeto (projeto_id, usuario_id, comentario)
                       VALUES (:projeto_id, :usuario_id, :comentario)");
$stmt->execute([":projeto_id" => $projetoId, ":usuario_id" => $usuario->id, ":comentario" => $comentario]);

http_response_code(201);
echo json_encode([
    "status" => true,
    "mensagem" => "Comentário adicionado.",
    "comentario" => [
        "id" => (int) $pdo->lastInsertId(),
        "comentario" => $comentario,
        "criado_em" => date("Y-m-d H:i:s")
    ]
]);

