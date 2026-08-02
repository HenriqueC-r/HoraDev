<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../config/jwt.php";


use Firebase\JWT\JWT;
use Firebase\JWT\Key;


function verificarToken(){

    $headers = getallheaders();

    if (!isset($headers["Authorization"])) {

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
        $headers["Authorization"]
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