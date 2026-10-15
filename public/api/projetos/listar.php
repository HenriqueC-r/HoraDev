<?php

require_once __DIR__ . "/../../../config/cors.php";
require_once __DIR__ . "/../../../config/verificarToken.php";
require_once __DIR__ . "/../../../conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(["status" => false, "mensagem" => "Método não permitido."]);
    exit;
}

$usuario = verificarToken();

$sql = "SELECT p.id, p.titulo, p.descricao, p.repositorio_url, p.demo_url,
               p.criado_em, p.atualizado_em,
               COALESCE(SUM(CASE
                   WHEN st.finalizado_em IS NULL THEN TIMESTAMPDIFF(SECOND, st.iniciado_em, NOW())
                   ELSE st.duracao_segundos
               END), 0) AS total_segundos,
               MAX(CASE WHEN st.finalizado_em IS NULL THEN 1 ELSE 0 END) AS timer_ativo
        FROM projetos p
        LEFT JOIN sessoes_tempo st ON st.projeto_id = p.id
        WHERE p.usuario_id = :usuario_id
        GROUP BY p.id, p.titulo, p.descricao, p.repositorio_url, p.demo_url, p.criado_em, p.atualizado_em
        ORDER BY p.criado_em DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([":usuario_id" => $usuario->id]);

echo json_encode([
    "status" => true,
    "projetos" => $stmt->fetchAll(PDO::FETCH_ASSOC)
]);
