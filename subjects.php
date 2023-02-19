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
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
include 'class_Subject.php';
include 'class_User.php';
mysqli_report(MYSQLI_REPORT_STRICT);

// if (!isset($_SESSION['displayed-userSubject'])) {
//     $_SESSION['displayed-userSubject'] = 0;
// }
// if (!isset($_SESSION['displayed'])) {
//     $_SESSION['displayed'] = 0;
// }
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

// If user click "Add" button, it will prompt the display of an Add box 
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

// If user click "Edit" button on any of the subject item, it will prompt the display of an Edit box
if (isset($_POST['promptEdit'])) {
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
            <button type='submit' name='editSubject' value='Finalize Edit'>Finalize Edit</button>
        </div>
    </div>
</form>";
}
// After user fill up the Add box and click Add Subject
// To add subject (FOR ADMIN)
if (isset($_POST['addSubject'])) {
    $code = $_POST['code'];
    $name = $_POST['name'];
    $lecturer = $_POST['lecturer'];
    $venue = $_POST['venue'];
    $type = $_POST['type'];

    // Prepare a statement to select the lecturer by name
    $preQuery = "SELECT * FROM `osers`.`user` WHERE `name` = ?";
    $preStmt = $conn->prepare($preQuery);
    $preStmt->bind_param('s', $lecturer);
    $preStmt->execute();
    $preResult = $preStmt->get_result();

    if (mysqli_num_rows($preResult) == 0) {
        echo "Lecturer is not in the database: " . $lecturer;
    } else {
        $row = $preResult->fetch_assoc();
        $sID = $row['sID'];

        // Prepare a statement to insert the subject
        $query = "INSERT INTO `subject`(`code`,`name`, `lecturer`, `venue`, `type`) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssss', $code, $name, $lecturer, $venue, $type);

        // Execute the statement to insert the subject
        if ($stmt->execute()) {
            $success = 1;

            // Prepare a statement to insert the subject-user relation
            $query = "INSERT INTO `user-subject`(`sID`,`code`) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('is', $sID, $code);

            // Execute the statement to insert the subject-user relation
            if ($stmt->execute()) {
                $success += 1;
            }

            if ($success < 2) {
                echo "Failed to insert subject";
            } else {
                // Redirect to the page that shows the subjects
                header("Location: subjects.php");
                exit();
            }
        } else {
            echo "Failed to insert subject: " . $conn->error;
        }
    }
}

// When user fill up Edit box and click on Edit Subject button
// To edit subject (FOR ADMIN)
if (isset($_POST['editSubject'])) {
    $code = $_POST['code'];
    $name = $_POST['name'];
    $lecturer = $_POST['lecturer'];
    $venue = $_POST['venue'];
    $type = $_POST['type'];
    $subjectCode = $_SESSION['subjecttoChange'];
    // Prepare a statement to select the lecturer by name
    $preQuery = "SELECT * FROM `osers`.`user` WHERE `name` = ?";
    $preStmt = $conn->prepare($preQuery);
    $preStmt->bind_param('s', $lecturer);
    $preStmt->execute();
    $preResult = $preStmt->get_result();

    if (mysqli_num_rows($preResult) == 0) {
        echo "Lecturer is not in the database: " . $lecturer;
    } else {
        $row = $preResult->fetch_assoc();
        $sID = $row['sID'];

        // Prepare a statement to insert the subject
        $query = "UPDATE `subject` SET `code` = ?, `name` = ?, `lecturer` = ?, `venue` = ?, `type` = ? WHERE `code` = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssssss', $code, $name, $lecturer, $venue, $type, $subjectCode);

        // Execute the statement to insert the subject
        if ($stmt->execute()) {
            $success = 1;

            // Prepare a statement to insert the subject-user relation
            $query = "UPDATE `user-subject` SET `sID` = ?, `code` = ? WHERE `code` = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('iss', $sID, $code, $subjectCode);

            // Execute the statement to insert the subject-user relation
            if ($stmt->execute()) {
                $success += 1;
            }

            if ($success < 2) {
                echo "Failed to insert subject";
            } else {
                // Redirect to the page that shows the subjects
                header("Location: subjects.php");
                exit();
            }
        } else {
            echo "Failed to insert subject: " . $conn->error;
        }
    }
}
// Save subjectCode from hidden value in HTML element to global session variable, to be used in the function to change the status of subject
if (isset($_POST['subjectCode'])) {
    $_SESSION['subjecttoChange'] = $_POST['subjectCode'];
}
// To change subject status (type) when user prompts: remove/deactivate,activate. Call changeStatus() function.
// (FOR ADMIN)
if (isset($_POST['remove'])) {
    changeStatus('remove', 'removed');
}
if (isset($_POST['deactivate'])) {
    changeStatus('deactivate', 'inactive');
}
if (isset($_POST['activate'])) {
    changeStatus('activate', 'active');
}
// If user press yes confirming their choice, call changeSubject() function with the GET request result for type change
if (isset($_POST['yesChange'])) {
    $subjectCode = $_SESSION['subjecttoChange'];
    changeSubject($conn, $subjectCode, $_GET['typeChange']);
    echo $subjectCode . " has been " . $_GET['typeChange'];
} else if (isset($_POST['noChange'])) {
    echo "Subject removal cancelled";
}

// After clicking on any of "Remove", "Deactivate", or "Activate" buttons -> User will be ask to confirm their choice. 
function changeStatus($todo, $changeto)
{
    $subjectCode = $_SESSION['subjecttoChange'];
    echo "Are you sure you want to " . $todo . " " . $subjectCode . " ?";
    echo "<form action='subjects.php?typeChange=$changeto' method='POST'> 
        <div class='submitResetItem submitContainer'>
        <button type='submit' name='yesChange' value='yesChange'>Yes</button> 
        <button type='submit' name='noChange' value='noChange'>No</button> 
        </div>
        </form>";

}

// To display subject list the user is assigned to (for non-admin)
if ($user->get_type() != 'admin') {
    $sID = $user->get_sID(); // For non-admin user -> display the subjects they are enrolled in / assigned to
    $stmt = $conn->prepare("SELECT * from `osers`.`user-subject` WHERE `sID` = ? ");
    $stmt->bind_param("s", $sID);
    $stmt->execute();
    $result = $stmt->get_result();
    if (isset($_POST['search'])) {
        echo "<h4>Your search result..</h4>";
    }
    echo "<h2> Your Subjects</h2>";
    if (isset($_POST['withdraw'])) {
        // echo "you press withdraw";
        withdrawSubject($conn, $user->get_sID(), $_SESSION['subjecttoChange']);
    }
    // To handle search for subjects 
    if (isset($_POST['search'])) {
        $search_input = $_POST['search_input'];
        $search_by = $_POST['search_by'];
        $_SESSION['displayed-userSubject'] = 0;
        // var_dump($result);
        // var_dump(mysqli_fetch_all($result));
        // Fetch the data for the user's subject list first, to prevent subjects not associated with the users from displaying. 
        // Make a verification by comparing search input with the value field from the fetched data => only if the two are the same, call the displayBy function to display the subject list
        while ($row = mysqli_fetch_assoc($result)) {
            // echo "im here";
            $code = $row['code'];
            switch ($search_by) {
                case "code":
                    // $_SESSION['displayed-userSubject'] = 0;
                    if ($search_input == $code) {
                        displayBy($conn, $code, 'code');
                        // echo $_SESSION['displayed-userSubject'];
                        echo "<form action='subjects.php' method='POST'> <input type='hidden' name='subjectCode' value='$code'>
                        <button type='submit' name='withdraw' value='Withdraw'>Withdraw</button></form>";
                    }
                    break;
                case "name":
                    // $_SESSION['displayed-userSubject'] = 0;
                    $stmt2 = $conn->prepare("SELECT * from `osers`.`subject` WHERE `code` = ? AND `name` = ? ");
                    $stmt2->bind_param("ss", $code, $search_input);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();
                    if (displaySubject($result2)) {
                        echo "<form action='subjects.php' method='POST'> <input type='hidden' name='subjectCode' value='$code'>
                        <button type='submit' name='withdraw' value='Withdraw'>Withdraw</button></form>";
                    }

                    break;
                case "lecturer":
                    // $_SESSION['displayed-userSubject'] = 0;
                    $stmt2 = $conn->prepare("SELECT * from `osers`.`subject` WHERE `code` = ? AND `lecturer` = ? ");
                    $stmt2->bind_param("ss", $code, $search_input);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();
                    if (displaySubject($result2)) {
                        echo "<form action='subjects.php' method='POST'> <input type='hidden' name='subjectCode' value='$code'>
                        <button type='submit' name='withdraw' value='Withdraw'>Withdraw</button></form>";
                    }
                    break;
                default:
                    // $_SESSION['displayed-userSubject'] = 0;
                    $stmt2 = $conn->prepare("SELECT * from `osers`.`subject` WHERE `code` = ? AND `venue` = ? ");
                    $stmt2->bind_param("ss", $code, $search_input);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();
                    if (displaySubject($result2)) {
                        echo "<form action='subjects.php' method='POST'> <input type='hidden' name='subjectCode' value='$code'>
                        <button type='submit' name='withdraw' value='Withdraw'>Withdraw</button></form>";
                    }

                    break;
            }
        }
        // echo $_SESSION['displayed-userSubject'];
        if ($_SESSION['displayed-userSubject'] == 0) {
            echo "<h4>No search result found.</h4>";
        }
        // echo "<form action='subjects.php' method='POST'> 
        // <button type='submit' name='undoSearch' value='Undo Search'>Undo Search</button></form>";
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $code = $row['code'];
            if (displayBy($conn, $code, 'code')) {
                echo "<form action='subjects.php' method='POST'> <input type='hidden' name='subjectCode' value='$code'>
                <button type='submit' name='withdraw' value='Withdraw'>Withdraw</button></form>";
            }

        }
    }

}
// To display all subjects (including subjects the user is not assigned to/not enrolled in)
// If user search something
if (isset($_POST['search'])) {
    $search_input = $_POST['search_input'];
    $search_by = $_POST['search_by'];
    if ($user->get_type() == 'admin') {
        adminDisplayBy($conn, $search_input, $search_by);
    } else {
        echo "<h2>Active Subjects</h2>";
        // if (isset($_POST['enroll'])) {
        //     enrollSubject($conn, $user->get_sID(), $_SESSION['subjecttoChange']);
        // }
    }
    if (displayBy($conn, $search_input, $search_by, isEnrolment: true) == false) {
        echo "<h4>No search result found.</h4>";
    }
    echo "<br><br>";
    echo "<form action='subjects.php' method='POST'> 
    <button type='submit' name='undoSearch' value='Undo Search'>Undo Search</button></form>";
    // Will only be available for admin user: inactive, removed buttons)
} else if (isset($_POST['inactive'])) {
    echo "<h2> Inactive Subjects</h2>";
    adminDisplayBy($conn, 'inactive', 'type');
} else if (isset($_POST['removed'])) {
    echo "<h2> Removed Subjects</h2>";
    adminDisplayBy($conn, 'removed', 'type');
}
// Default (not searching something)
else {
    echo "<h2> Active Subjects</h2>";
    if (isset($_POST['enroll'])) {
        enrollSubject($conn, $user->get_sID(), $_SESSION['subjecttoChange']);
    }

    if ($user->get_type() == 'admin') {
        adminDisplayBy($conn, 'active', 'type');
    } else if ($user->get_type() == 'student') {
        displayBy($conn, 'active', 'type', isEnrolment: true); // Enable Enroll button for student by isEnrolment = true as a parameter for displayBy
    } else {
        displayBy($conn, 'active', 'type', isEnrolment: true);
    }
}

// Function to display enroll result message
function enrollSubject($conn, $sID, $subject)
{
    // echo "you click enroll !";
    $subject = mysqli_real_escape_string($conn, $subject); // sanitise to escape special characters before passing it to SQL query
    $query = "INSERT INTO `user-subject`(`sID`,`code`) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $sID, $subject);
    // var_dump(($stmt->execute()));
    if ($stmt->execute()) {
        echo "You are now enrolled in " . $subject . " Please refresh the page.";
    } else {
        echo "You are already enrolled in " . $subject;
    }
    echo "<br>";
}

// Function to display enroll result message
function withdrawSubject($conn, $sID, $subject)
{
    // echo "im here at wS";
    $subject = mysqli_real_escape_string($conn, $subject); // sanitise to escape special characters before passing it to SQL query
    $query = "DELETE FROM `user-subject` WHERE `sID` = ? AND `code` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $sID, $subject);
    // var_dump(($stmt->execute()));
    $stmt->execute();
    echo "You have withdrawn from " . $subject . " Please refresh the page.";
    echo "<br>";

}
// Function to change subject status (to inactive, removed, or back to active from inactive)
function changeSubject($conn, $subjectCode, $changeto)
{
    $subjectCode = mysqli_real_escape_string($conn, $subjectCode); // sanitise to escape special characters before passing it to SQL query
    $stmt = $conn->prepare("UPDATE `osers`.`subject` SET `type` = ? WHERE `code` = ? ");
    $stmt->bind_param("ss", $changeto, $subjectCode);
    $stmt->execute();
}
// Function to take search input value and search by key to prepare and execute SQL statement
// will call displaySubject() function with the result of the query
function displayBy($conn, $search_input, $search_by, bool $isEnrolment = false)
{
    $activeString = 'active';

    if ($search_by == 'code') {
        $stmt = $conn->prepare("SELECT * from `osers`.`subject` WHERE `code` = ? AND `type` = ?");
    } else if ($search_by == 'name') {
        $stmt = $conn->prepare("SELECT * from `osers`.`subject` WHERE `name` = ? AND `type` = ?");
    } else if ($search_by == 'lecturer') {
        $stmt = $conn->prepare("SELECT * from `osers`.`subject` WHERE `lecturer` = ? AND `type` = ?");
    } else if ($search_by == 'venue') {
        $stmt = $conn->prepare("SELECT * from `osers`.`subject` WHERE `venue` = ? AND `type` = ?");
    } else {
        $stmt = $conn->prepare("SELECT * from `osers`.`subject` WHERE `type` = ?");
    }

    if ($search_by == 'type') {
        $stmt->bind_param("s", $activeString);
    } else {
        $stmt->bind_param("ss", $search_input, $activeString);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    return displaySubject($result, $isEnrolment);
}

function adminDisplayBy($conn, $search_input, $search_by)
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
function displaySubject($result, bool $isEnrolment = false)
{
    // echo "im me";
    $_SESSION['displayed'] = 0;
    $foundResult = false;
    // var_dump($isEnrolment);

    while ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['displayed'] = 1; // to store whether or not there is a data returned. if not, we want to have a variable that can be used later to display a message "No search result found"
        $foundResult = true;
        $_SESSION['displayed-userSubject'] = 1;
        $code = $row['code'];
        $name = $row['name'];
        $lecturer = $row['lecturer'];
        $venue = $row['venue'];
        $type = $row['type'];
        // Create a Subject object and call a method in Subject class
        $subject = new Subject($code, $name, $lecturer, $venue, $type);
        $subject->display();
        // echo $_SESSION['displayed-userSubject'];
        if (unserialize($_SESSION['user'])->get_type() == 'admin') {
            echo "<form action='subjects.php' method='POST'> <div class='submitResetItem submitContainer'>
    <button type='submit' name='promptEdit' value='promptEdit'>Edit</button> <input type='hidden' name='subjectCode' value='$code'></div></form>";
            if ($type == 'active') {
                echo "<form action='subjects.php' method='POST'> <div class='submitResetItem submitContainer'><input type='hidden' name='subjectCode' value='$code'>
    <button type='submit' name='deactivate' value='deactivate'>Deactivate</button> </div></form>";
            }
            if ($type == 'inactive') {
                echo "<form action='subjects.php' method='POST'> <div class='submitResetItem submitContainer'><input type='hidden' name='subjectCode' value='$code'>
    <button type='submit' name='activate' value='activate'>Activate</button> </div></form>";
            }
            if ($type != 'removed') {
                echo "<form action='subjects.php' method='POST'><div class='submitResetItem submitContainer'><input type='hidden' name='subjectCode' value='$code'>
        <button type='submit' name='remove' value='remove'>Remove</button>
    </div></form>";
            }
        } else if ($isEnrolment && (unserialize($_SESSION['user'])->get_type() == 'student')) {
            echo "<form action='subjects.php' method='POST'> <input type='hidden' name='subjectCode' value='$code'>
            <button type='submit' name='enroll' value='Enroll'>Enroll</button></form>";
        }
    }
    return $foundResult;
}

// Function to enrol in a subject (FOR STUDENT USER)



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