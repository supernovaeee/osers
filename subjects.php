<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects</title>
</head>
<?php
session_start();
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

// require_once("server.php");
$user = unserialize($_SESSION['user']);

echo "Hi, ";

echo $user->get_name();
echo "&nbsp;";
echo $user->get_surname();
echo "<br><br>";
echo $user->get_sID();




// To display subject list
if ($user->get_type() == 'admin') { // For admin user -> display all subjects
    $query = "SELECT * from `osers`.`subject`";
    displaySubject($conn, $query);
} else {
    $sID = $user->get_sID(); // For non-admin user -> display the subjects they are enrolled in / assigned to
    // echo $sID;
    $preQuery = "SELECT * from `osers`.`user-subject` WHERE `sID` = '$sID'";
    $preResult = mysqli_query($conn, $preQuery);
    // print_r($preResult);
    $codeArray = array();
    // $preRow = mysqli_fetch_assoc($preResult);
    // print_r($preRow);
    while ($preRow = mysqli_fetch_assoc($preResult)) {
        // echo $preRow['code'];
        array_push($codeArray, $preRow['code']);
    }
    for ($i = 0; $i < sizeof($codeArray); $i++) {
        $code = $codeArray[$i];
        $query = "SELECT * from `osers`.`subject` WHERE `code` = '$code'";
        displaySubject($conn, $query);
    }


}
function displaySubject($conn, $query)
{
    try {
        $result = mysqli_query($conn, $query);
    } catch (mysqli_sql_exception $e) {
        echo "Unable to retrieve subject list from database" . $e;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        echo $row["code"] . ' (' . $row["name"] . ')' . '<br>Lecturer: ' . $row["lecturer"] . '<br>Venue: ' . $row["venue"] . '<br>Status: ' . $row["type"] . '<br>' . '<br>';
    }
}




// ?>

<body>
    <link rel="stylesheet" type="text/css" href="<?php echo $storeInfo['css_file']; ?>" />
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    </head>

    <body>
    </body>

</html>