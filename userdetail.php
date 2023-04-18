
        <?php

            header('Content-Type: application/json');
            $hostname = 'localhost';
            $database = 'weathertowear';
            $username = 'root';
            $password = ''; 
            $pdo = new PDO("mysql:dbname=$database;host=$hostname", $username, $password);

            if (!$pdo) {
                // create a JSON response with an error message
                $response = array(
                    'status' => 'error',
                    'message' => 'Could not connect to the database'
                );
                
                // output the JSON response
                echo json_encode($response);
                exit;
            }
            
            $sql = "SELECT * from users";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();

            foreach($result as $row)
            {
                $data[] = $row;
                
                // print_r($data);
                // echo "\n";
            }

            // add a new key to the data array
$data_with_key = array(
    'users' => $data,
    'orders'=>'orderarray with object'
);

// create a JSON response with the data
$response = array(
    'status' => 'success',
    'data' => $data_with_key
);

// output the JSON response
echo json_encode($response);
        ?>
