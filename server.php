<?php

class User
{
    public $sID;
    public $name;
    public $surname;
    public $email;
    public $phone;
    public $password;
    public $type;

    function __construct($sID, $name, $surname, $phone, $email, $type, $password)
    {
        $this->sID = $sID;
        $this->name = $name;
        $this->surname = $surname;
        $this->phone = $phone;
        $this->email = $email;
        $this->type = $type;
        $this->password = $password;
    }
    function get_sID()
    {
        return $this->sID;
    }
    function get_name()
    {
        return $this->name;
    }
    function get_surname()
    {
        return $this->surname;
    }
    function get_phone()
    {
        return $this->phone;
    }
    function get_email()
    {
        return $this->email;
    }
    function get_type()
    {
        return $this->type;
    }
    function get_password()
    {
        return $this->password;
    }
}

session_start();

// if (!isset($_SESSION['fromRegistration'])) {
//     $_SESSION['fromRegistration'] = 0;
//     echo "Your session number:" . $_SESSION['fromRegistration'];
// } else {
//     echo "You are returning from registration";
// }

// $errors = array();
// $search_by = "";

// Initialise and declare variables for MySQL connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "osers";

// Create connection
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
} catch (mysqli_sql_exception $e) {
    die("Connection failed:" . mysqli_connect_errno() . "=" . mysqli_connect_error());
}

// // Create database
// $sql = "CREATE DATABASE IF NOT EXISTS osers";
// try {
//     mysqli_query($conn, $sql);
// } catch (mysqli_sql_exception $e) {
//     die("Error creating database: " . mysqli_error($conn));
// } // do we need this??

// LOG-IN
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Look up for row with email and password as entered by user
    // Store it as a result pointer array in $row
    $query = "SELECT * from `osers`.`user` WHERE `email` = '$email'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);
    // print_r($row);

    // Return an error message if there is no account associated with data entered or if email is not registered
    if (mysqli_num_rows($result) == 0) {
        echo "Invalid login. Please register.";
    } else {
        // if the email and password match, successfully log in
        if ($row['email'] == $email && $row['password'] == $password) {
            // Store user's information to session variable upon successful authentication
            $sID = $row['sID'];
            $name = $row['name'];
            $surname = $row['surname'];
            $phone = $row['phone'];
            $email = $row['email'];
            $type = $row['type'];
            $password = $row['password'];
            $user = new User($sID, $name, $surname, $phone, $email, $type, $password);
            $_SESSION['user'] = $user;
            echo "You are logged in, ";
            echo $_SESSION['user']->get_name();
            echo "&nbsp;";
            echo $_SESSION['user']->get_surname();
            // header('location: index.php');
        } else {
            // if password is wrong
            echo "Wrong username/password combination";
        }
    }
}

// REGISTER
if (isset($_POST['submit'])) {
    $sID = $_POST['sID'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $type = $_POST['type'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];


    // To check if the email is already registered in the system
    $query = "SELECT * from `osers`.`user` WHERE `email` = '$email' or `sID` = '$sID'";
    $result = mysqli_query($conn, $query);
    // print_r($result);
    $row = mysqli_fetch_array($result);
    // print_r($row);

    if (mysqli_num_rows($result) > 0) {
        // To check if email / student ID is already registered in the database. 
        if ($row['email'] == $email) {
            echo "Invalid registration: email already registered to an account.";
        }
        if ($row['sID'] == $sID) {
            echo "Invalid registration: student ID already registered to an account.";
        }
        // Prompt user to log in. 
        echo "Please <a href='index.php'>log in</a> or contact the IT Helpdesk.";
    } else {
        if (($type == "student" && strlen($sID) != 8) || ($type == "educator" && strlen($sID) != 6) || ($type == "admin" && strlen($sID) != 4)) {
            echo "Invalid ID entered. Please try again.";
        } else if ($password != $password2) {
            echo "Different passwords entered. Please try again.";
        } else {
            // echo "Type is:" . $type . "Length of ID:" . strlen($sID);
            // SQL suery to insert the registration data variables into the database
            $query = "INSERT INTO `user`(`sID`, `name`, `surname`, `phone`, `email`, `type`, `password`) VALUES('$sID', '$name', '$surname', '$phone', '$email', '$type', '$password')";
            try {
                mysqli_query($conn, $query);
                echo "You have registered.";
                $GuestID = mysqli_insert_id($conn);
                echo "Your ID is $GuestID <br />";
                echo "<p class='reg-text'><span><a href='index.php'>Click here to log in</a></span></p>";
                // header('location: index.php');
            } catch (mysqli_sql_exception $e) {
                echo "Unable to insert the the record" . $e;
            }
        }
    }
}
// session_destroy();
?>