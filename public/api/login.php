<?php

    require_once __DIR__ . "/../../config/cors.php";
    require_once __DIR__ . "/../../vendor/autoload.php";
    require_once __DIR__ . "/../../config/jwt.php";
    require_once __DIR__ . "/../../conexao.php";


    $dados = json_decode(file_get_contents("php://input"), true);


    if (
        !isset($dados["email"]) ||
        !isset($dados["senha"])
    ) {

        http_response_code(400);

        echo json_encode([
            "status" => false,
            "mensagem" => "Email e senha são obrigatórios."
        ]);

        exit;
    }


    $email = trim($dados["email"]);
    $senha = $dados["senha"];



    $sql = "SELECT * FROM usuarios WHERE email = :email";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":email" => $email
    ]);


    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);



    if ($usuario && password_verify($senha, $usuario["senha"])) {

        $payload = [
            "id" => $usuario["id"],
            "email" => $usuario["email"],
            "iat" => time(),
            "exp" => time() + 3600
        ];

        $token = \Firebase\JWT\JWT::encode(
            $payload,
            JWT_SECRET,
            'HS256'
        );

        echo json_encode([
            "status" => true,
            "mensagem" => "Login realizado com sucesso.",
            "token" => $token,
            "usuario" => [
                "id" => $usuario["id"],
                "nome" => $usuario["nome"],
                "email" => $usuario["email"]
            ]
        ]);


    } else {


        http_response_code(401);

        echo json_encode([
            "status" => false,
            "mensagem" => "Email ou senha inválidos."
        ]);

    }

?>
