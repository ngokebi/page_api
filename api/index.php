<?php

declare(strict_types=1);

require "bootstrap.php";

<<<<<<< HEAD
$full_path =  parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$parts = explode("/", $full_path);

$resource = $parts[7];

$id = $parts[8] ?? null; // if not set, then set to null 

if ($resource != "page") {
    http_response_code(404);
    exit;
}

if (($id != "bvn-inp-face") && ($id != "bvn-nin-face")) {
    http_response_code(404);
    exit;
}

$database = new Database();

$user_gateway = new UserGateway($database);

// // add auth class that checks for api key
$auth = new Auth($user_gateway);

// // validate using authetication key
if (!$auth->authenticateAPIkey()) {
    exit;
}

$user_id = $auth->getUserID();


$gateway = new TaskGateway($database);

$taskcontroller = new TaskController();
// $gateway, $user_id

$taskcontroller->processRequest($_SERVER["REQUEST_METHOD"], $id);
=======
$input_data =  (array) json_decode(file_get_contents("php://input"), true);
$verification = new Verification();
$make_call = $verification->callAPI('POST', 'https://api.myidentitypay.com/api/v2/biometrics/merchant/data/verification/cac/image', json_encode($input_data));
$response = json_decode($make_call, true);

print_r(json_encode($response));


>>>>>>> c9d44b60155cc88013c3a6a44f4db701688d4628
