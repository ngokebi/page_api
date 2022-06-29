<?php

declare(strict_types=1);

require "bootstrap.php";

$input_data =  (array) json_decode(file_get_contents("php://input"), true);
$verification = new Verification();
$make_call = $verification->callAPI('POST', 'https://api.myidentitypay.com/api/v2/biometrics/merchant/data/verification/cac/image', json_encode($input_data));
$response = json_decode($make_call, true);

print_r(json_encode($response));


