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
// try {
//     $conn = new mysqli($servername, $username, $password, $dbname);
// } catch (mysqli_sql_exception $e) {
//     die("Connection failed:" . mysqli_connect_errno() . "=" . mysqli_connect_error());
// }
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    exit('Error connecting to database'); //Should be a message a typical user could understand in production
}
// require_once("server.php");
$user = unserialize($_SESSION['user']);

// Greeting and user information
echo "Hi, ";
echo $user->get_name();
echo "<br>";
if ($user->get_type() == 'student') {
    echo "You are a " . $user->get_type();
    echo "<br>Student ID: " . $user->get_sID();
} else {
    echo "You are an " . $user->get_type();
    echo "<br>Staff ID: " . $user->get_sID();
}
echo "<br>Phone No.: " . $user->get_phone();
echo "<br>Email: " . $user->get_email();
echo "<br><br>";

// To display options to filter subjects based on subject status: active, inactive, removed (ONLY FOR ADMIN USER)
if ($user->get_type() == 'admin') {
    echo "<form action='subjects.php' method='POST'> <div class='submitResetItem submitContainer'>
    <button type='prompt' name='prompt' value='Prompt'>Add Subject</button><br><br>
    <span>Display subjects:</span><br><br>
    <button type='active' name='active' value='active'>Active</button>
    <button type='inactive' name='inactive' value='inactive'>Inactive</button>
    <button type='removed' name='removed' value='removed'>Removed</button>
</div></form>";
}

// Search box feature
echo "<form action='subjects.php' method='post'>
<br>
<label for='search_by'>Search by:</label>
<select id='search_by' name='search_by'>
    <option value='code'>Subject Code</option>
    <option value='name'>Subject Name</option>
    <option value='lecturer'>Lecturer</option>
    <option value='venue'>Venue</option>
</select>
<br>
<label for='search_input'>Search Input:</label>
<input type='text' id='search_input' name='search_input'>
<br><br>
<input type='submit' name='search' value='Search'>
</form>";

if (isset($_POST['prompt'])) {
    echo "<form action='subjects.php' method='POST'>
    <div class='wrap'>
        <label for='name'>Subject Name</label>
        <input type='text' name='name' id='name' placeholder='Enter subject name (e.g. Web Server Programming)' required>
    </div>
    <div class='wrap'>
        <label for='code'>Subject Code</label>
        <input type='text' name='code' id='code' placeholder='Enter subject code (e.g. ISIT307)' required>
    </div>
    <div class='wrap'>
        <label for='lecturer'>Lecturer Name</label>
        <input type='text' name='lecturer' id='lecturer' placeholder='Enter the lecturer's name for this subject (e.g. John Doe)' required>
    </div>
    <div class='wrap'>
    <label for='venue'>Lecture Venue</label>
    <input type='text' name='venue' id='venue' placeholder='Enter the venue's name for this subject (e.g. A.3.03)' required>
</div>
    <div class='wrap'>
        <label for='type'>Status</label>
        <select name='type' id='type' required>
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
// When user add subject
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
    // To handle search for subjects
    if (isset($_POST['search'])) {
        $search_input = $_POST['search_input'];
        $search_by = $_POST['search_by'];
        echo "<h4>Your search result..</h4>";
        displayBy($conn, $search_input, $search_by);
        // echo "displayBy runs successfully";
    } else if (isset($_POST['inactive'])) {
        echo "<h2> Inactive Subjects</h2>";
        displayBy($conn, 'inactive', 'type');
    } else if (isset($_POST['removed'])) {
        echo "<h2> Removed Subjects</h2>";
        displayBy($conn, 'removed', 'type');
    } else {
        echo "<h2> Active Subjects</h2>";
        displayBy($conn, 'active', 'type');
    }

} else {
    $sID = $user->get_sID(); // For non-admin user -> display the subjects they are enrolled in / assigned to
    $stmt = $conn->prepare("SELECT * from `osers`.`user-subject` WHERE `sID` = ? ");
    $stmt->bind_param("s", $sID);
    $stmt->execute();
    $result = $stmt->get_result();
    echo "<h2> Your Subjects</h2>";
    // To handle search for subjects 
    if (isset($_POST['search'])) {
        $search_input = $_POST['search_input'];
        $search_by = $_POST['search_by'];
        echo "<h4>Your search result..</h4>";

        // Fetch the data for the user's subject list first, to prevent subjects not associated with the users from displaying. 
        // Make a verification by comparing search input with the value field from the fetched data => only if the two are the same, call the displayBy function to display the subject list
        while ($row = mysqli_fetch_assoc($result)) {
            $code = $row['code'];
            switch ($search_by) {
                case "code":
                    if ($search_input == $code) {
                        displayBy($conn, $code, 'code');
                    }
                    break;
                case "name":
                    $stmt2 = $conn->prepare("SELECT * from `osers`.`subject` WHERE `code` = ? AND `name` = ? ");
                    $stmt2->bind_param("ss", $code, $search_input);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();
                    displaySubject($result2);
                    break;
                case "lecturer":
                    $stmt2 = $conn->prepare("SELECT * from `osers`.`subject` WHERE `code` = ? AND `lecturer` = ? ");
                    $stmt2->bind_param("ss", $code, $search_input);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();
                    displaySubject($result2);
                    break;
                default:
                    $stmt2 = $conn->prepare("SELECT * from `osers`.`subject` WHERE `code` = ? AND `venue` = ? ");
                    $stmt2->bind_param("ss", $code, $search_input);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();
                    displaySubject($result2);
                    break;
            }
        }
        echo "<form action='subjects.php' method='POST'> 
        <button type='submit' name='undoSearch' value='Undo Search'>Undo Search</button></form>";
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $code = $row['code'];
            displayBy($conn, $code, 'code');
        }
    }
}
// Function to take search input value and search by key to prepare and execute SQL statement
// will call displaySubject() function with the result of the query
function displayBy($conn, $search_input, $search_by)
{
    if ($search_by == 'type') {
        $stmt = $conn->prepare("SELECT * from `osers`.`subject` WHERE `type` = ? ");
    } else if ($search_by == 'code') {
        $stmt = $conn->prepare("SELECT * from `osers`.`subject` WHERE `code` = ? ");
    } else if ($search_by == 'name') {
        $stmt = $conn->prepare("SELECT * from `osers`.`subject` WHERE `name` = ? ");
    } else if ($search_by == 'lecturer') {
        $stmt = $conn->prepare("SELECT * from `osers`.`subject` WHERE `lecturer` = ? ");
    } else {
        $stmt = $conn->prepare("SELECT * from `osers`.`subject` WHERE `venue` = ? ");
    }
    $stmt->bind_param("s", $search_input);
    $stmt->execute();
    $result = $stmt->get_result();

    displaySubject($result);
}

// Function to display the result of the SQL query in displayBy()
function displaySubject($result)
{
    while ($row = mysqli_fetch_assoc($result)) {
        $code = $row['code'];
        $name = $row['name'];
        $lecturer = $row['lecturer'];
        $venue = $row['venue'];
        $type = $row['type'];
        // Create a Subject object and call a method in Subject class
        $subject = new Subject($code, $name, $lecturer, $venue, $type);
        $subject->display();
    }
}
echo "<form action='subjects.php' method='POST'>
        <div class='wrap'>
            <p class='reg-text'> <span><a href='index.php'>Log Out</a></span></p>
        </div>
    </form>";
?>

<body>
    <link rel="stylesheet" type="text/css" href="<?php echo $storeInfo['css_file']; ?>" />
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    </head>

    <body>
    </body>

</html>