
        <?php
            header('Content-Type: application/json');

            // get the request data as JSON
            $request_data = file_get_contents('php://input');

            // decode the JSON data into a PHP array
            $data = json_decode($request_data, true);
            $hostname = 'localhost';
            $database = 'weathertowear';
            $username = 'root';
            $password = ''; 

            
            $pdo = new PDO("mysql:dbname=$database;host=$hostname", $username, $password);

            // $fullName = "mimoh";
            // $email = "mimoh@gmail.com";
            // $phone = "4389289072";
            // $city = "Montreal";
            // $gender = 0;
            // $pwd = "654321";

            
            $sql = "INSERT INTO users ( )VALUES (:fullname, :email, :phone, :gender,:city, :pwd)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':fullname', $data['name']);
            $stmt->bindValue(':email', $data['email']);
            $stmt->bindValue(':phone', $data['phone']);
            $stmt->bindValue(':gender', $data['gender']);
            $stmt->bindValue(':city', $data['city']);
            $stmt->bindValue(':pwd', $data['pwd']);
            //$stmt->execute();

            if (!$stmt->execute()) {
                // create a JSON response with an error message
                $response = array(
                    'status' => 'error',
                    'message' => 'Could not insert data into the database'
                );
            
                // output the JSON response
                echo json_encode($response);
                exit;
            }
            
            // create a JSON response with a success message
            $response = array(
                'status' => 'success',
                'message' => 'Data inserted into the database'
            );
            
            // output the JSON response
            echo json_encode($response);
            
            
   ?>