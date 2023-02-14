<?php

class User
{
    public $userID;
    public $name;
    public $surname;
    public $email;
    public $phone;
    public $password;
    public $userType;
}

session_start();
// $name = "";
// $surname = "";
// $email = "";
// $phone = "";
// $password = "";
// $errors = array();
// $search_by = "";

// Initialise and declare variables for MySQL connection
$servername = "localhost";
$username = "root";
$password = "root";

// Create connection
try {
    $conn = mysqli_connect($servername, $username, $password);
} catch (mysqli_sql_exception $e) {
    die("Connection failed:" . mysqli_connect_errno() . "=" . mysqli_connect_error());
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS osers";
try {
    mysqli_query($conn, $sql); //pre PHP8.1 - if (mysqli_query($conn, $sql))
} catch (mysqli_sql_exception $e) {
    die("Error creating database: " . mysqli_error($conn));
} // do we need this??

// When user press the login button
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Look up for row with email and password as entered by user
    // Store it as a result pointer array in $row
    $query = "SELECT * from `osers`.`user` WHERE `email` = '$email' or `password` = '$password' ";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);
    // print_r($row);

    // Return an error message if there is no account associated with data entered
    if (mysqli_num_rows($result) <= 0) {
        echo "Invalid login. Please register.";
    } else {
        // if the email and password match, successfully log in
        if ($row['email'] == $email && $row['password'] == $password) {
            $_SESSION['username'] = $row['name'];
            $_SESSION['userID'] = $row['ID'];
            $_SESSION['useremail'] = $row['email'];
            echo "You are logged in";
            // header('location: index.php');
        } else {
            // if one of them does not match, throw an error message
            echo "Wrong username/password combination";
        }
    }
}



?>