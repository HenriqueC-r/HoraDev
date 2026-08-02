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

if (!$projetoId) {
    http_response_code(400);
    echo json_encode(["status" => false, "mensagem" => "Informe um projeto válido."]);
    exit;
}

$projeto = $pdo->prepare("SELECT id FROM projetos WHERE id = :id AND usuario_id = :usuario_id");
$projeto->execute([":id" => $projetoId, ":usuario_id" => $usuario->id]);
if (!$projeto->fetch()) {
    http_response_code(404);
    echo json_encode(["status" => false, "mensagem" => "Projeto não encontrado."]);
    exit;
}

$ativo = $pdo->prepare("SELECT st.id, st.projeto_id, p.titulo FROM sessoes_tempo st
                        JOIN projetos p ON p.id = st.projeto_id
                        WHERE st.usuario_id = :usuario_id AND st.finalizado_em IS NULL
                        ORDER BY st.iniciado_em DESC LIMIT 1");
$ativo->execute([":usuario_id" => $usuario->id]);
$sessaoAtiva = $ativo->fetch(PDO::FETCH_ASSOC);
if ($sessaoAtiva) {
    http_response_code(409);
    echo json_encode([
        "status" => false,
        "mensagem" => "Já existe um cronômetro em andamento.",
        "timer_ativo" => $sessaoAtiva
    ]);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO sessoes_tempo (projeto_id, usuario_id) VALUES (:projeto_id, :usuario_id)");
$stmt->execute([":projeto_id" => $projetoId, ":usuario_id" => $usuario->id]);

http_response_code(201);
echo json_encode([
    "status" => true,
    "mensagem" => "Cronômetro iniciado.",
    "timer_ativo" => ["id" => (int) $pdo->lastInsertId(), "projeto_id" => $projetoId, "iniciado_em" => date("Y-m-d H:i:s")]
]);

