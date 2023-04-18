<?php
ob_start();
require "database.php";
$db = new DataBase();

$request_data = file_get_contents('php://input');

// decode the JSON data into a PHP array
$data = json_decode($request_data, true);

if ($data['fullName'] != null && $data['email'] != null && $data['phone'] != null
    && $data['gender'] != null && $data['city'] != null && $data['password'] != null) {
    if ($db->dbConnect()) {
        if ($db->signUp("users", $data['fullName'], $data['email'], $data['phone'], $data['gender'], $data['city'], $data['password'])) {
            $response = array("status" => "successfull", "message" => "Sign up successfull");
            http_response_code(200);
        } else {
            $response = array("status" => "failed", "message" => "Sign up failed");
            http_response_code(400);
        }
    } else {
        $response = array("status" => "error", "message" => "Database connection error");
        http_response_code(500);
    }
} else {
    $response = array("status" => "error", "message" => "All fields are required");
    http_response_code(400);
}
ob_clean();
echo json_encode($response);
?>
