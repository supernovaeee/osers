<?php
session_start();
session_unset();
include 'class_Subject.php';
include 'class_User.php';
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
// Create database
$sql = "CREATE DATABASE IF NOT EXISTS osers";
try {
    mysqli_query($conn, $sql);
} catch (mysqli_sql_exception $e) {
    die("Error creating database: " . mysqli_error($conn));
}

// LOG-IN
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Look up for row with email and password as entered by user
    // Store it as a result pointer array in $row
    $query = "SELECT * from `osers`.`user` WHERE `email` = '$email'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);
    // Return an error message if there is no account associated with data entered or if email is not registered
    if (mysqli_num_rows($result) == 0) {
        echo "Invalid login. Please register.";
    } else {
        // if the email and password match, successfully log in
        if ($row['email'] == $email && $row['password'] == $password) {
            // Store user's information to session variable upon successful authentication
            $sID = $row['sID'];
            $name = $row['name'];
            $phone = $row['phone'];
            $email = $row['email'];
            $type = $row['type'];
            $password = $row['password'];
            $user = new User($sID, $name, $phone, $email, $type, $password);
            $_SESSION['user'] = serialize($user);
            header('location: subjects.php');
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
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $type = $_POST['type'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    // To check if the email is already registered in the system
    $query = "SELECT * from `osers`.`user` WHERE `email` = '$email' or `sID` = '$sID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);

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
            $query = "INSERT INTO `user`(`sID`, `name`, `phone`, `email`, `type`, `password`) VALUES('$sID', '$name', '$phone', '$email', '$type', '$password')";
            try {
                mysqli_query($conn, $query);
                echo "You have registered. Please log in.";
                echo "<p class='reg-text'><span><a href='index.php'>Click here to log in</a></span></p>";
            } catch (mysqli_sql_exception $e) {
                echo "Unable to insert the the record" . $e;
            }
        }
    }
}
?>