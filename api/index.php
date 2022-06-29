<?php

declare(strict_types=1);

require "bootstrap.php";

$full_path =  parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$parts = explode("/", $full_path);

if (empty($parts[7])) {
    http_response_code(404);
    exit;
}

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
