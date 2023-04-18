<?php
require "DataBase.php";
$db = new DataBase();

$request_data = file_get_contents('php://input');

// decode the JSON data into a PHP array
$data = json_decode($request_data, true);

if ($data['email'] != null && $data['password'] != null) {
    if ($db->dbConnect()) {
        $result = $db->logIn("users", $data['email'], $data['password']);
        if ($result != false) {
            $response = array("status" => "success", "message" => "Login successfull", "data" => $result);
            http_response_code(200);
        } else {
            $response = array("status" => "error", "message" => "Invalid email or password");
            http_response_code(401);
        }
    } else {
        $response = array("status" => "error", "message" => "Database connection error");
        http_response_code(500);
    }
} else {
    $response = array("status" => "error", "message" => "Missing email or password");
    http_response_code(400);
}
echo json_encode($response);
?>
