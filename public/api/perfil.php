<?php

require_once __DIR__ . "/../../config/cors.php";
require_once __DIR__ . "/../../config/verificarToken.php";
require_once __DIR__ . "/../../conexao.php";


$tokenUsuario = verificarToken();


$id = $tokenUsuario->id;


$sql = "SELECT id, nome, email, criado_em 
        FROM usuarios 
        WHERE id = :id";


$stmt = $pdo->prepare($sql);


$stmt->execute([
    ":id" => $id
]);


$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {

    http_response_code(404);

    echo json_encode([
        "status" => false,
        "mensagem" => "Usuário não encontrado."
    ]);

    exit;
}

echo json_encode([
    "status" => true,
    "usuario" => $usuario,
    "resumo" => obterResumoPerfil($pdo, $id)
]);

function obterResumoPerfil(PDO $pdo, int $usuarioId): array {
    $sql = "SELECT COUNT(DISTINCT p.id) AS total_projetos,
                   COALESCE(SUM(CASE
                       WHEN st.finalizado_em IS NULL THEN TIMESTAMPDIFF(SECOND, st.iniciado_em, NOW())
                       ELSE st.duracao_segundos
                   END), 0) AS total_segundos
            FROM projetos p
            LEFT JOIN sessoes_tempo st ON st.projeto_id = p.id
            WHERE p.usuario_id = :usuario_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([":usuario_id" => $usuarioId]);
    $resumo = $stmt->fetch(PDO::FETCH_ASSOC);

    return [
        "total_projetos" => (int) $resumo["total_projetos"],
        "total_segundos" => (int) $resumo["total_segundos"]
    ];
}

?>
