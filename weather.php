<?php
header('Content-Type: application/json');
require "controller/DataBase.php";
$db = new DataBase();

$request_data = file_get_contents('php://input');

// decode the JSON data into a PHP array
$data = json_decode($request_data, true);


if (isset($data['city']) && isset($data['fromDate']) && isset($data['toDate']) && isset($data['gender']) ) {
    if ($db->dbConnect()) {
        $result = $db->getRangeWeather($data['city'], $data['fromDate'],$data['toDate'],$data['gender']);
        if ($result != null) {
            $response = array("status"=>200, "message" => "weather data successfully get", "data" => $result);
            http_response_code(200);
        } else {
            $response = array("status" => 401, "message" => "missing data");
            http_response_code(401);
        }
    } else {
        $response = array("status" => 500, "message" => "Database connection error");
        http_response_code(500);
    }
} else {
    $response = array("status" => 400, "message" => "Wrong parameter names passsed");
    http_response_code(400);
}
echo json_encode($response);


?>