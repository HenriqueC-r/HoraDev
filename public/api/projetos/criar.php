<?php

require_once __DIR__ . "/../../config/cors.php";
require_once __DIR__ . "/../../config/verificarToken.php";
require_once __DIR__ . "/../../conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["status" => false, "mensagem" => "Método não permitido."]);
    exit;
}

$usuario = verificarToken();
$dados = json_decode(file_get_contents("php://input"), true);

if (!is_array($dados) || !isset($dados["titulo"])) {
    http_response_code(400);
    echo json_encode(["status" => false, "mensagem" => "O título do projeto é obrigatório."]);
    exit;
}

$titulo = trim($dados["titulo"]);
$descricao = isset($dados["descricao"]) ? trim($dados["descricao"]) : null;
$repositorioUrl = isset($dados["repositorio_url"]) ? trim($dados["repositorio_url"]) : null;
$demoUrl = isset($dados["demo_url"]) ? trim($dados["demo_url"]) : null;

if (mb_strlen($titulo) < 3 || mb_strlen($titulo) > 120) {
    http_response_code(422);
    echo json_encode(["status" => false, "mensagem" => "O título deve ter entre 3 e 120 caracteres."]);
    exit;
}

foreach ([$repositorioUrl, $demoUrl] as $url) {
    if ($url && !filter_var($url, FILTER_VALIDATE_URL)) {
        http_response_code(422);
        echo json_encode(["status" => false, "mensagem" => "Informe links válidos para repositório e demonstração."]);
        exit;
    }
}

$sql = "INSERT INTO projetos (usuario_id, titulo, descricao, repositorio_url, demo_url)
        VALUES (:usuario_id, :titulo, :descricao, :repositorio_url, :demo_url)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ":usuario_id" => $usuario->id,
    ":titulo" => $titulo,
    ":descricao" => $descricao ?: null,
    ":repositorio_url" => $repositorioUrl ?: null,
    ":demo_url" => $demoUrl ?: null
]);

http_response_code(201);
echo json_encode([
    "status" => true,
    "mensagem" => "Projeto criado com sucesso.",
    "projeto" => [
        "id" => (int) $pdo->lastInsertId(),
        "titulo" => $titulo,
        "descricao" => $descricao ?: null,
        "repositorio_url" => $repositorioUrl ?: null,
        "demo_url" => $demoUrl ?: null
    ]
]);

