<?php
ob_start();
header('Content-Type: application/json');
require "controller/database.php";
$db = new DataBase();

$request_data = file_get_contents('php://input');

// decode the JSON data into a PHP array
$data = json_decode($request_data, true);

if (
    !empty($data['fullName']) && !empty($data['email']) && !empty($data['phone'])
    && isset($data['gender']) && !empty($data['city']) && !empty($data['password'])
) {

    // Check if the email and phone are in the correct format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || !preg_match('/^[0-9]{10}$/', $data['phone'])) {
        $response = array("status" => 400, "message" => "Invalid email or phone number");
        http_response_code(400);
        echo json_encode($response);
        exit();
    }

    if ($db->dbConnect()) {
        if ($db->signUp("users", $data['fullName'], $data['email'], $data['phone'], $data['gender'], $data['city'], $data['password'])) {
            $response = array("status" => 200, "message" => "Sign up successful");
            http_response_code(200);
        } else {
            $response = array("status" => 400, "message" => "Sign up failed: email or phone number already exists");
            http_response_code(409);
        }
    } else {
        $response = array("status" => 500, "message" => "Database connection error");
        http_response_code(500);
    }
} else {
    $response = array("status" => 400, "message" => "Missing or invalid parameters");
    http_response_code(400);
}
echo json_encode($response);
?>