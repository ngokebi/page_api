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

            switch ($id) {

                case 'bvn-inp-face':
                    $input_data =  (array) json_decode(file_get_contents("php://input"), true);
                    $errors = $this->check_Validation_inp($input_data);
                    if (!empty($errors)) {
                        $this->respondUnprocessableEntity($errors);
                        return;
                    }
                    echo json_encode($input_data);
                    break;

                case 'bvn-nin-face':
                    $input_data =  (array) json_decode(file_get_contents("php://input"), true);
                    $errors = $this->check_Validation_nin($input_data);
                    if (!empty($errors)) {
                        $this->respondUnprocessableEntity($errors);
                        return;
                    }
                    echo json_encode($input_data);
                    break;

                default:
                    $this->respondMethodAllowed("POST");
            }
        }
    }

    private function respondMethodAllowed(string $allowed_methods): void
    {
        http_response_code(405);
        header("Allow: $allowed_methods");
    }

    private function check_Validation_inp(array $data, bool $is_new = true): array
    {
        $error = [];


        if (empty($data["BVN"]) || $data["BVN"] == "") {
            $error[] = "BVN is required";
        } elseif (!empty($data["BVN"])) {
            if (filter_var($data["BVN"], FILTER_VALIDATE_INT) === false) {
                $error[] = "BVN must be an integer";
            }
        }

        if (empty($data["Passport_No"]) || $data["Passport_No"] == "") {
            $error[] = "Passport_No is required";
        } elseif (!empty($data["Passport_No"])) {
            if (filter_var($data["Passport_No"], FILTER_VALIDATE_INT) === false) {
                $error[] = "Passport_No must be an integer";
            }
        }

        return $error;
    }

    private function check_Validation_nin(array $data, bool $is_new = true): array
    {
        $error = [];

        if (empty($data["BVN"]) || $data["BVN"] == "") {
            $error[] = "BVN is required";

        } elseif (!empty($data["BVN"])) {
            if (filter_var($data["BVN"], FILTER_VALIDATE_INT) === false) {
                $error[] = "BVN must be an integer";
            }
        }

        if (empty($data["NIN"]) || $data["NIN"] == "") {
            $error[] = "NIN is required";

        } elseif (!empty($data["NIN"])) {
            if (filter_var($data["NIN"], FILTER_VALIDATE_INT) === false) {
                $error[] = "NIN must be an integer";
            }
        }
        return $error;
    }


    private function respondUnprocessableEntity(array $errors): void
    {
        http_response_code(422);
        echo json_encode(["errors" => $errors]);
    }
}
