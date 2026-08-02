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

$sessao = $pdo->prepare("SELECT st.id, st.iniciado_em FROM sessoes_tempo st
                         JOIN projetos p ON p.id = st.projeto_id
                         WHERE st.projeto_id = :projeto_id AND st.usuario_id = :usuario_id
                           AND p.usuario_id = :usuario_id AND st.finalizado_em IS NULL
                         ORDER BY st.iniciado_em DESC LIMIT 1");
$sessao->execute([":projeto_id" => $projetoId, ":usuario_id" => $usuario->id]);
$sessaoAtiva = $sessao->fetch(PDO::FETCH_ASSOC);

if (!$sessaoAtiva) {
    http_response_code(404);
    echo json_encode(["status" => false, "mensagem" => "Não há cronômetro em andamento para este projeto."]);
    exit;
}

$atualizar = $pdo->prepare("UPDATE sessoes_tempo
                            SET finalizado_em = NOW(), duracao_segundos = TIMESTAMPDIFF(SECOND, iniciado_em, NOW())
                            WHERE id = :id");
$atualizar->execute([":id" => $sessaoAtiva["id"]]);

$duracao = $pdo->prepare("SELECT duracao_segundos, finalizado_em FROM sessoes_tempo WHERE id = :id");
$duracao->execute([":id" => $sessaoAtiva["id"]]);

echo json_encode([
    "status" => true,
    "mensagem" => "Cronômetro parado.",
    "sessao" => $duracao->fetch(PDO::FETCH_ASSOC)
]);

