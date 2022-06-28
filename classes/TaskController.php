<?php

class TaskController
{
    private $taskgateway;


    public function __construct()
    {
        // TaskGateway $gateway,  int $user_id
        // $this->taskgateway = $gateway;
        // $this->user_id = $user_id;
    }
    public function processRequest(string $method, string $id): void
    {
        // if the id is null
        if ($id === null) {
            http_response_code(404);
            exit;
        } elseif ($method == "POST") {
            $input_data =  (array) json_decode(file_get_contents("php://input"), true);
            echo json_encode($input_data);
        } else {
            $this->respondMethodAllowed('POST');
        }
    }
    private function respondMethodAllowed(string $allowed_methods): void
    {
        http_response_code(405);
        header("Allow: $allowed_methods");
    }
}
