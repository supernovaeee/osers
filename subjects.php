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
// echo "&nbsp;";
echo "<br><br>";
// echo $user->get_sID();

if ($user->get_type() == 'admin') { // For admin user -> display option to add subject
    echo "<form action='subjects.php' method='POST'> <div class='submitResetItem submitContainer'>
    <button type='prompt' name='prompt' value='Prompt'>Add Subject</button>
</div></form>";
    echo "<br>";
}

if (isset($_POST['prompt'])) {
    echo "<form action='subjects.php' method='POST'>
    <div class='wrap'>
        <label for='name'>Subject Name</label>
        <input type='text' name='name' id='name' placeholder='Enter subject name (e.g. Web Server Programming)'>
    </div>
    <div class='wrap'>
        <label for='code'>Subject Code</label>
        <input type='text' name='code' id='code' placeholder='Enter subject code (e.g. ISIT307)'>
    </div>
    <div class='wrap'>
        <label for='lecturer'>Lecturer Name</label>
        <input type='text' name='lecturer' id='lecturer' placeholder='Enter the lecturer's name for this subject (e.g. John Doe)'>
    </div>
    <div class='wrap'>
    <label for='venue'>Lecture Venue</label>
    <input type='text' name='venue' id='venue' placeholder='Enter the venue's name for this subject (e.g. A.3.03)'>
</div>
    <div class='wrap'>
        <label for='type'>Status</label>
        <select name='type' id='type'>
            <option value='active'>Active</option>
            <option value='inactive'>Inactive</option>
        </select>
    </div>
    <div class='submitResetContainer'>
        <div class='submitResetItem resetContainer'>
            <button type='reset' name='reset' value='Reset'>Reset</button>
        </div>
        <div class='submitResetItem submitContainer'>
            <button type='submit' name='addSubject' value='Add Subject'>Add Subject</button>
        </div>
    </div>
</form>";
}
if (isset($_POST['addSubject'])) {
    $code = $_POST['code'];
    $name = $_POST['name'];
    $lecturer = $_POST['lecturer'];
    $venue = $_POST['venue'];
    $type = $_POST['type'];
    $preQuery = "SELECT * from `osers`.`user` WHERE `name` = '$lecturer'";
    $preResult = mysqli_query($conn, $preQuery);
    // print_r($row);
    if (mysqli_num_rows($preResult) == 0) {
        echo "Lecturer is not in the database: " . $lecturer;
    } else {
        function testAdd($conn, $query)
        {
            try {
                mysqli_query($conn, $query);
                return 1;
            } catch (mysqli_sql_exception $e) {
                echo $e;
                return 0;
            }
        }
        $query = "INSERT INTO `subject`(`code`,`name`, `lecturer`, `venue`, `type`) VALUES 
        ('$code', '$name', '$lecturer', '$venue','$type')";
        $success = testAdd($conn, $query);
        $row = mysqli_fetch_assoc($preResult);
        $sID = $row['sID'];
        if ($success < 1) {
            echo "Fail to insert subject.";
        } else {
            $query = "INSERT INTO `user-subject`(`sID`,`code`) VALUES 
            ('$sID', '$code')";
            $success += testAdd($conn, $query);
            if ($success < 2) {
                echo "Fail to insert subject";
            }
        }
    }



}
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

?>

<body>
    <link rel="stylesheet" type="text/css" href="<?php echo $storeInfo['css_file']; ?>" />
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    </head>

    <body>
    </body>

</html>