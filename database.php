<?php
require "config.php";

class DataBase
{
    public $connect;
    public $data;
    private $sql;
    protected $servername;
    protected $username;
    protected $password;
    protected $databasename;

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
            if ($dbusername == $username && $password==$dbpassword) {                
                $login = true;
                return $row;
            } else $login = false;
        } else $login = false;
        return false;
    }

    function signUp($table, $fullName, $email,$phone, $gender, $city, $pwd)
    {
        $fullName = $this->prepareData($fullName);
        $pwd = $this->prepareData($pwd);
        $email = $this->prepareData($email);
        $phone = $this->prepareData($phone);
        $gender = $this->prepareData((int)$gender);
        $city = $this->prepareData($city);
        // $pwd = password_hash($pwd, PASSWORD_DEFAULT);
        $this->sql =
            "INSERT INTO " . $table . " (fullName, email, phone, gender, city, pwd) VALUES ('" . $fullName . "','" . $email . "','" . $phone . "','" . $gender . "','" . $city . "', '" . $pwd . "')";
        $result = mysqli_query($this->connect, $this->sql);
        echo $result; 
            if ($result) {
            return true;
        } else return false;
    }
    
}

?>