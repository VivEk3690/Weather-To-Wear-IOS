<?php
require "./config/config.php";

class DataBase
{
    public $connect;
    public $data;
    private $sql;
    protected $servername;
    protected $username;
    protected $password;
    protected $databasename;

    protected $apiKey;


    public function __construct()
    {
        $this->connect = null;
        $this->data = null;
        $this->sql = null;
        $dbc = new DataBaseConfig();
        $this->servername = $dbc->servername;
        $this->username = $dbc->username;
        $this->password = $dbc->password;
        $this->databasename = $dbc->databasename;
        // Replace YOUR_API_KEY with your actual API key
        $this->apiKey = '36NSLHJRDCCLQ34VFD85JUWXU';
    }

    function dbConnect()
    {
        $this->connect = mysqli_connect($this->servername, $this->username, $this->password, $this->databasename);
        return $this->connect;
    }

    function prepareData($data)
    {
        return mysqli_real_escape_string($this->connect, stripslashes(htmlspecialchars($data)));
    }

    function logIn($table, $username, $password)
    {
        $username = $this->prepareData($username);
        $password = $this->prepareData($password);
        $this->sql = "select * from " . $table . " where email = '" . $username . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $dbusername = $row['email'];
            $dbpassword = $row['pwd'];
            // if ($dbusername == $username && password_verify($password, $dbpassword)) {
            if ($dbusername == $username && $password == $dbpassword) {
                $login = true;
                return $row;
            } else
                $login = false;
        } else
            $login = false;
        return false;
    }

    function signUp($table, $fullName, $email, $phone, $gender, $city, $pwd)
    {
        $fullName = $this->prepareData($fullName);
        $pwd = $this->prepareData($pwd);
        $email = $this->prepareData($email);
        $phone = $this->prepareData($phone);
        $gender = $this->prepareData($gender);
        $city = $this->prepareData($city);
        // $pwd = password_hash($pwd, PASSWORD_DEFAULT);

        $this->sql = "SELECT * FROM " . $table . " WHERE email='" . $email . "' OR phone='" . $phone . "'";
        $result = mysqli_query($this->connect, $this->sql);

        if (mysqli_num_rows($result) > 0) {
            return false;
        }

        $this->sql = "INSERT INTO " . $table . " (fullName, email, phone, gender, city, pwd) VALUES ('" . $fullName . "','" . $email . "','" . $phone . "','" . $gender . "','" . $city . "', '" . $pwd . "')";

        $result = mysqli_query($this->connect, $this->sql);

        if ($result) {
            return true;
        } else
            return false;
    }

    function getRangeWeather($city, $fromDate, $toDate, $gender)
    {
        $city = $this->prepareData($city);
        $fromDate = $this->prepareData($fromDate);
        $toDate = $this->prepareData($toDate);
        $gender = $this->prepareData($gender);




        // Set up the URL for the API call
        $url = 'https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/' . $city . '/' . $fromDate . '/' . $toDate . '?unitGroup=metric&include=days&key=' . $this->apiKey . '&contentType=json';

        // Initialize cURL and set the options
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Execute the API call and get the response
        $weathersData = json_decode(curl_exec($curl));

        // Close the cURL session
        curl_close($curl);

        $weatherShortData = array();

        if ($weathersData != null) {

            $weatherShortData['city'] = $weathersData->address;
            $weatherShortData['gender'] = $gender;
            $weatherShortData['days'] = array();

            foreach ($weathersData->days as $day) {

                $preciptype = $day->preciptype == null ? "cloud" : $day->preciptype[0];

                $weatherShortData['days'][] = array(
                    'datetime' => $day->datetime,
                    'temp' => number_format($day->temp, 2),
                    'tempmax' => number_format($day->tempmax, 2),
                    'tempmin' => number_format($day->tempmin, 2),
                    'feelslike' => number_format($day->feelslike, 2),
                    'preciptype' => $preciptype,
                    "outfits" => $this->getOutfits($preciptype, $gender)

                );
            }


            // return json_decode($weatherResponse);
            return array(
                "weather" => $weatherShortData,
            );

        }

        return null;

    }

    function getOutfits($preciptype, $gender)
    {
        // Read the contents of the JSON file into a string
        $jsonString = file_get_contents('./dataset/outfits.json');

        // Decode the JSON string into a PHP array
        $data = json_decode($jsonString, true);

        $outfits = $data['outfits'][$preciptype][$gender];

        // Prepare the response object
        $response = array();
        foreach ($outfits as $outfit) {
            $item = array(
                "name" => $outfit['name'],
                "image" => $outfit['image']
            );
            array_push($response, $item);
        }

        // Convert the response object to JSON and print it
        return $response;

    }
}
?>