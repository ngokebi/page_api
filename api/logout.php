<?php

declare(strict_types=1);

require "bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    
    http_response_code(405);
    header("Allow: POST");
    exit;
}

$data = (array) json_decode(file_get_contents("php://input"), true);

if ( ! array_key_exists("access_token", $data)) {

    http_response_code(400);
    echo json_encode(["message" => "missing token"]);
    exit;
}


$jwt = new JwtHandler();





















