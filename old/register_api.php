<?php

declare(strict_types=1);

require "../page_api/vendor/autoload.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Allow: POST");
    exit;
}

$input_data =  (array) json_decode(file_get_contents("php://input"), true);

if (!array_key_exists("email", $input_data) || !array_key_exists("name", $input_data) ||  !array_key_exists("password", $input_data)) {
    http_response_code(400);
    echo json_encode(["message" => "missing registration credentials"]);
    exit;
}

$database = new Database();

$database = $database->getConnection();
$api_key = 'iuuBatOi.NUitkNS6pmnL1ERLLdUfremAdrvyDZRc';

$sql = "INSERT INTO users (name, email, password, api_key) VALUES ( :name, :email, :password, :api_key)";

$stmt = $database->prepare($sql);

$stmt->bindValue(':name', $input_data["name"], PDO::PARAM_STR);
$stmt->bindValue(':email', $input_data["email"], PDO::PARAM_STR);
$stmt->bindValue(':password', password_hash($input_data["password"], PASSWORD_DEFAULT), PDO::PARAM_STR);
$stmt->bindValue(':api_key', $api_key);

$stmt->execute();
echo json_encode("You have been Registered. Your Api Key is " . $api_key);
