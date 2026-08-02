<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/jwt.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


function verificarToken(){ // ! Função para fazer a verificação do token

    $headers = function_exists("getallheaders") ? getallheaders() : [];
    $autorizacao = $headers["Authorization"]
        ?? $_SERVER["HTTP_AUTHORIZATION"]
        ?? $_SERVER["REDIRECT_HTTP_AUTHORIZATION"]
        ?? null;


    if (!$autorizacao) {

        http_response_code(401);

        echo json_encode([
            "status" => false,
            "mensagem" => "Token não enviado."
        ]);

        exit;
    }


    $token = str_replace(
        "Bearer ",
        "",
        $autorizacao
    );


    try {

        $dados = JWT::decode(
            $token,
            new Key(JWT_SECRET, 'HS256')
        );


        return $dados;


    } catch(Exception $e){

        http_response_code(401);

        echo json_encode([
            "status" => false,
            "mensagem" => "Token inválido."
        ]);

        exit;

    }

}

?>
