<?php

require_once __DIR__ . "/../../config/cors.php";
require_once __DIR__ . "/../../config/verificarToken.php";
require_once __DIR__ . "/../../conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(["status" => false, "mensagem" => "Método não permitido."]);
    exit;
}

$usuario = verificarToken();
$projetoId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);

if (!$projetoId) {
    http_response_code(400);
    echo json_encode(["status" => false, "mensagem" => "Informe um id de projeto válido."]);
    exit;
}

$sql = "SELECT p.id, p.titulo, p.descricao, p.repositorio_url, p.demo_url, p.criado_em, p.atualizado_em,
               COALESCE(SUM(CASE
                   WHEN st.finalizado_em IS NULL THEN TIMESTAMPDIFF(SECOND, st.iniciado_em, NOW())
                   ELSE st.duracao_segundos
               END), 0) AS total_segundos
        FROM projetos p
        LEFT JOIN sessoes_tempo st ON st.projeto_id = p.id
        WHERE p.id = :projeto_id AND p.usuario_id = :usuario_id
        GROUP BY p.id, p.titulo, p.descricao, p.repositorio_url, p.demo_url, p.criado_em, p.atualizado_em";
$stmt = $pdo->prepare($sql);
$stmt->execute([":projeto_id" => $projetoId, ":usuario_id" => $usuario->id]);
$projeto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$projeto) {
    http_response_code(404);
    echo json_encode(["status" => false, "mensagem" => "Projeto não encontrado."]);
    exit;
}

$timer = $pdo->prepare("SELECT id, iniciado_em FROM sessoes_tempo
                        WHERE projeto_id = :projeto_id AND usuario_id = :usuario_id AND finalizado_em IS NULL
                        ORDER BY iniciado_em DESC LIMIT 1");
$timer->execute([":projeto_id" => $projetoId, ":usuario_id" => $usuario->id]);
$timerAtivo = $timer->fetch(PDO::FETCH_ASSOC) ?: null;

$comentarios = $pdo->prepare("SELECT id, comentario, criado_em, atualizado_em
                              FROM comentarios_projeto
                              WHERE projeto_id = :projeto_id AND usuario_id = :usuario_id
                              ORDER BY criado_em DESC");
$comentarios->execute([":projeto_id" => $projetoId, ":usuario_id" => $usuario->id]);

echo json_encode([
    "status" => true,
    "projeto" => $projeto,
    "timer_ativo" => $timerAtivo,
    "comentarios" => $comentarios->fetchAll(PDO::FETCH_ASSOC)
]);

