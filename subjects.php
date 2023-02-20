<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects</title>
    <link rel="stylesheet" href="styles.css">
</head>
<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'class_Subject.php';
include 'class_User.php';
mysqli_report(MYSQLI_REPORT_STRICT);

// Initialise and declare variables for MySQL connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "osers";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    exit('Error connecting to database'); //Should be a message a typical user could understand in production
}
$user = unserialize($_SESSION['user']);

// Greeting and user information
echo "<div class='greetings'>Hi, " . $user->get_name() . "<br><br>";
if ($user->get_type() == 'student') {
    echo "You are a " . $user->get_type();
    echo "<br><br>Student ID: " . $user->get_sID();
} else {
    echo "You are an " . $user->get_type();
    echo "<br><br>Staff ID: " . $user->get_sID();
}
echo "<br>Phone No.: " . $user->get_phone();
echo "<br>Email: " . $user->get_email();
echo "<br><br></div>";
// Search box feature
echo "
<div class = 'searchBox'>
    <form action='subjects.php' method='post'>
    <br>
    <div class='searchByContainer'>
        <label for='search_by'>Search by:</label>
        <select id='search_by' name='search_by'>
            <option value='code'>Subject Code</option>
            <option value='name'>Subject Name</option>
            <option value='lecturer'>Lecturer</option>
            <option value='venue'>Venue</option>
        </select>
    </div>
    <br>
    <div class='searchInputContainer'>
        <label for='search_input'>Search Input:</label>
        <input type='text' id='search_input' name='search_input'>
        <br><br>
    </div>
    <div class='searchButton'>
        <button type='submit' name='search' value='search'>Search</button>
    </div>

    </form>
    <form action='subjects.php' method='post'>
    
    <h2>Advanced Search</h2>
    <p>Use ; (semicolon) between multiple search terms</p>
    <div class='searchByContainer'>
        <label for='subject1'>Subject Code(s):</label>
        <input type='text' id='subject1' name='subject1'>
        <br><br>
    </div>
    <div class='searchByContainer'>
        <label for='name1'>Subject Name(s):</label>
        <input type='text' id='name1' name='name1'>
        <br><br>
    </div>
    <div class='searchByContainer'>
        <label for='lecturer1'>Lecturer(s):</label>
        <input type='text' id='lecturer1' name='lecturer1'>
        <br><br>
    </div>
    <div class='searchByContainer'>
        <label for='venue1'>Venue(s):</label>
        <input type='text' id='venue1' name='venue1'>
        <br><br>
    </div>
    <div class='searchByContainer'>
        <label for='status1'>Status(es):</label>
        <input type='text' id='status1' name='status1'>
        <br><br>
    </div>
    <div class='searchButton'>
        <button type='submit' name='search' value='advanced'>Search Advanced</button>
    </div>

    </form>

</div>";
// To display options to filter subjects based on subject status: active, inactive, removed (ONLY FOR ADMIN USER)
if ($user->get_type() == 'admin') {
    echo "<form action='subjects.php' method='POST'> <div class='addDisplayContainer'>
    <div class='addSubjectContainer'><button type='prompt' name='prompt' value='Prompt'>Add Subject</button></div>
    <div class='displaySubjectContainer'><span>Display subjects:</span><br><br>
    <button type='active' name='active' value='active'>Active</button>
    <button type='inactive' name='inactive' value='inactive'>Inactive</button>
    <button type='removed' name='removed' value='removed'>Removed</button></div>
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
        $query = "INSERT INTO `subject`(`code`,`name`,`venue`, `type`) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssss', $code, $name, $venue, $type);

        // Execute the statement to insert the subject
        if ($stmt->execute()) {
            $success = 1;

            // Prepare a statement to insert the subject-user relation
            $query = "INSERT INTO `user-subject`(`sID`,`code`) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ss', $sID, $code);

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
            $stmt->bind_param('sss', $sID, $code, $subjectCode);

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

// <div class='searchByContainer'>
//     <label for='subject1'>Subject Code(s):</label>
//     <input type='text' id='subject1' name='subject1'>
//     <br><br>
// </div>
// <div class='searchByContainer'>
//     <label for='name1'>Subject Name(s):</label>
//     <input type='text' id='name1' name='name1'>
//     <br><br>
// </div>
// <div class='searchByContainer'>
//     <label for='lecturer1'>Lecturer(s):</label>
//     <input type='text' id='lecturer1' name='lecturer1'>
//     <br><br>
// </div>
// <div class='searchByContainer'>
//     <label for='venue1'>Venue(s):</label>
//     <input type='text' id='venue1' name='venue1'>
//     <br><br>
// </div>
// <div class='searchByContainer'>
//     <label for='status1'>Status(es):</label>
//     <input type='text' id='status1' name='status1'>
//     <br><br>
// </div>

// function checkAdvancedSearchQuery($conn, $stmt)
// {
//     if (isset($_POST['search']) && $_POST['search'] == 'advanced')
// getAdvancedSearchQuery($conn, $stmt);
// }

function getAdvancedSearchQuery($conn, $personal = false)
{
    global $user;
    $subjectColumn = "sub.`code`";
    $nameColumn = "sub.`name`";
    $lecturerColumn = "u.name";
    $venueColumn = "sub.`venue`";
    $typeColumn = "sub.`type`";


    function unionTerms($column, $valueCount)
    {
        // echo "\ncolumnn is " . $column;
        $str = "( ";
        for ($i = 0; $i < $valueCount; $i++) {
            $str .= $column . " = ? OR ";
        }
        // echo "\nfirst stringly" . $str;
        $str = substr($str, 0, strlen($str) - 3); // remove last OR
        $str .= ")";
        // echo "\n second stringly" . $str;
        return $str;
    }

    $sentence = "SELECT * from `osers`.`subject` sub WHERE ";

    if (isset($_POST['lecturer1']) && $_POST['lecturer1'] != '') {
        $sentence = "SELECT sub.*, u.name as lecturer_name FROM `osers`.`subject` sub 
            JOIN `osers`.`user-subject` us ON sub.`code` = us.`code`
            JOIN `osers`.`user` u ON u.`sID` = us.`sID`
            WHERE u.`type` = 'educator' AND ";

        // if ($personal)

    }

    $searchArray = array();
    $haveInput = false;

    if (isset($_POST['subject1']) && $_POST['subject1'] != '') {
        $haveInput = true;
        $code_arr = explode(';', $_POST['subject1'], 10); // max 10 search terms per type
        $sentence .= unionTerms($subjectColumn, count($code_arr)) . " AND ";
        array_push($searchArray, ...$code_arr);
    }

    if (isset($_POST['name1']) && $_POST['name1'] != '') {
        $haveInput = true;
        $name_arr = explode(';', $_POST['name1'], 10);
        $sentence .= unionTerms($nameColumn, count($name_arr)) . " AND ";
        array_push($searchArray, ...$name_arr);
    }

    if (isset($_POST['lecturer1']) && $_POST['lecturer1'] != '') { // lecturer is special case.
        $haveInput = true;
        $lecturer_arr = explode(';', $_POST['lecturer1'], 10);
        $sentence .= unionTerms($lecturerColumn, count($lecturer_arr)) . " AND ";
        array_push($searchArray, ...$lecturer_arr);
        ;
    }

    if (isset($_POST['venue1']) && $_POST['venue1'] != '') {
        $haveInput = true;
        $venue_arr = explode(';', $_POST['venue1'], 10);
        $sentence .= unionTerms($venueColumn, count($venue_arr)) . " AND ";
        array_push($searchArray, ...$venue_arr);
    }

    if (isset($_POST['status1']) && $_POST['status1'] != '') {
        $haveInput = true;
        $status_arr = explode(';', $_POST['status1'], 10);
        $sentence .= unionTerms($typeColumn, count($status_arr)) . " AND ";
        array_push($searchArray, ...$status_arr);
    }

    if (!$haveInput)
        return null;
    $sentence = substr($sentence, 0, strlen($sentence) - 4); // remove final AND

    if ($personal) {
        $old_sentence = $sentence;
        $sID = $user->get_sID();
        $new_sentence = "SELECT * FROM `user-subject` us2
                RIGHT OUTER JOIN (" . $old_sentence . ") res1
                ON us2.`code` = res1.`code`
                WHERE us2.`sID` = $sID";
        // echo $sentence;
    }

    function multiplyTypes($type, $num)
    {
        if ($num == 1)
            return $type;
        return $type . multiplyTypes($type, $num - 1);
    }

    // echo $sentence;
    $stmt = $conn->prepare($sentence);
    $stmt->bind_param(multiplyTypes("s", count($searchArray)), ...$searchArray);
    return $stmt;
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
    if (isset($_POST['notTeach'])) {
        notTeach($conn, $user->get_sID(), $_SESSION['subjecttoChange']);
    }
    // To handle search for subjects 
    if (isset($_POST['search']) && $_POST['search'] == 'search') {
        $search_input = $_POST['search_input'];
        $search_by = $_POST['search_by'];
        $_SESSION['displayed-userSubject'] = 0;
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
                        if ($user->get_type() == 'student') {
                            echo "<form action='subjects.php' method='POST'> <input type='hidden' name='subjectCode' value='$code'>
                        <button type='submit' name='withdraw' value='Withdraw'>Withdraw</button></form>";
                        } else {
                            echo "<form action='subjects.php' method='POST'> <input type='hidden' name='subjectCode' value='$code'>
                        <button type='submit' name='notTeach' value='notTeach'>Stop Teaching</button>&nbsp;<button type='submit' name='viewStudents' value='viewStudents'>View Students</button></form>";
                        }
                    }
                    break;
                case "name":
                    // $_SESSION['displayed-userSubject'] = 0;
                    $stmt2 = $conn->prepare("SELECT * from `osers`.`subject` WHERE `code` = ? AND `name` = ? ");
                    $stmt2->bind_param("ss", $code, $search_input);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();
                    if (displaySubject($conn, $result2)) {
                        if ($user->get_type() == 'student') {
                            echo "<form action='subjects.php' method='POST'> <input type='hidden' name='subjectCode' value='$code'>
                        <button type='submit' name='withdraw' value='Withdraw'>Withdraw</button></form>";
                        } else {
                            echo "<form action='subjects.php' method='POST'> <input type='hidden' name='subjectCode' value='$code'>
                        <button type='submit' name='notTeach' value='notTeach'>Stop Teaching</button>&nbsp;<button type='submit' name='viewStudents' value='viewStudents'>View Students</button></form>";
                        }
                    }

                    break;
                case "lecturer":
                    $activeString = 'active';
                    $username = $user->get_name();
                    // create a prepared statement with placeholders
                    $stmt = $conn->prepare("SELECT sub.*, u.name as lecturer_name FROM `osers`.`subject` sub 
                        JOIN `osers`.`user-subject` us ON sub.`code` = us.`code`
                        JOIN `osers`.`user` u ON u.`sID` = us.`sID`
                        WHERE sub.`code` = ? AND u.`name` = ? AND sub.`type` = ?");

                    $stmt->bind_param("sss", $code, $search_input, $activeString);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    // fetch the results
                    while ($row = mysqli_fetch_assoc($result)) {
                        $code = $row['code'];
                        $subject_name = $row['name'];
                        $venue = $row['venue'];
                        $type = $row['type'];
                        $lecturer_name = $row['lecturer_name'];
                        if ($user->get_type() == 'educator') {
                            // Check if the lecturer name is the same as the user name
                            if ($lecturer_name == $username) {
                                $_SESSION['displayed-userSubject'] = 1;
                                // Create a Subject object and call a method in Subject class
                                $subject = new Subject($code, $subject_name, $venue, $type);
                                $subject->display();
                                retrieveLecturer($conn, $code);
                                echo "<form action='subjects.php' method='POST'> <input type='hidden' name='subjectCode' value='$code'>
                                    <button type='submit' name='notTeach' value='notTeach'>Stop Teaching</button>&nbsp;<button type='submit' name='viewStudents' value='viewStudents'>View Students</button></form>";
                            } else {
                            }
                        } else {
                            $_SESSION['displayed-userSubject'] = 1;
                            // Create a Subject object and call a method in Subject class
                            $subject = new Subject($code, $subject_name, $venue, $type);
                            $subject->display();
                            retrieveLecturer($conn, $code);
                            echo "<form action='subjects.php' method='POST'> <input type='hidden' name='subjectCode' value='$code'>
                            <button type='submit' name='withdraw' value='Withdraw'>Withdraw</button></form>";
                        }

                    }
                    break;
                default:
                    // $_SESSION['displayed-userSubject'] = 0;

                    $stmt2 = $conn->prepare("SELECT * from `osers`.`subject` WHERE `code` = ? AND `venue` = ? ");
                    $stmt2->bind_param("ss", $code, $search_input);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();
                    if (displaySubject($conn, $result2)) {
                        if ($user->get_type() == 'student') {
                            echo "<form action='subjects.php' method='POST'> <input type='hidden' name='subjectCode' value='$code'>
                        <button type='submit' name='withdraw' value='Withdraw'>Withdraw</button></form>";
                        } else {
                            echo "<form action='subjects.php' method='POST'> <input type='hidden' name='subjectCode' value='$code'>
                        <button type='submit' name='notTeach' value='notTeach'>Stop Teaching</button>&nbsp;<button type='submit' name='viewStudents' value='viewStudents'>View Students</button></form>";
                        }
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
    } else if (isset($_POST['search']) && $_POST['search'] == 'advanced') {
        $stmt = getAdvancedSearchQuery($conn, personal: true);
        if ($stmt == null)
            echo "No search terms were entered into advanced search box!";
        else {
            function executeForDisplay($stmt, $isEnrolment = false, $isTeaching = false)
            {
                global $conn;
                $result = executeStmtDisplay($stmt);
                // var_dump($result);
                return displaySubject($conn, $result, $isEnrolment, $isTeaching);
            }

            if ($user->get_type() == 'admin') {
                // adminDisplayBy($conn, $search_input, $search_by);
                if (executeForDisplay($stmt) == false) {
                    echo "<h4>No search result found.</h4>";
                }
            } else if ($user->get_type() == 'student') {
                echo "<h2>Active Subjects</h2>";
                if (executeForDisplay($stmt, isEnrolment: true) == false) {
                    echo "<h4>No search result found.</h4>";
                }
            } else {
                echo "<h2>Active Subjects</h2>";
                if (executeForDisplay($stmt, isTeaching: true) == false) {
                    echo "<h4>No search result found.</h4>";
                }
            }
        }
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $code = $row['code'];
            if (displayBy($conn, $code, 'code')) {
                if ($user->get_type() == 'student') {
                    echo "<form action='subjects.php' method='POST'> <input type='hidden' name='subjectCode' value='$code'>
                <button type='submit' name='withdraw' value='Withdraw'>Withdraw</button></form>";
                } else {
                    echo "<form action='subjects.php' method='POST'> <input type='hidden' name='subjectCode' value='$code'>
                <button type='submit' name='notTeach' value='notTeach'>Stop Teaching</button>&nbsp;<button type='submit' name='viewStudents' value='viewStudents'>View Students</button></form>";
                }
            }

        }
    }

}
// To display all subjects (including subjects the user is not assigned to/not enrolled in)
// If user search something
if (isset($_POST['search'])) {

    if (isset($_POST['search']) && $_POST['search'] == 'advanced') {
        $stmt = getAdvancedSearchQuery(($conn));
        if ($stmt == null)
            echo "No search terms were entered into advanced search box!";
        else {
            function executeForDisplay($stmt, $isEnrolment = false, $isTeaching = false)
            {
                global $conn;
                $result = executeStmtDisplay($stmt);
                // var_dump($result);
                return displaySubject($conn, $result, $isEnrolment, $isTeaching);
            }

            if ($user->get_type() == 'admin') {
                // adminDisplayBy($conn, $search_input, $search_by);
                if (executeForDisplay($stmt) == false) {
                    echo "<h4>No search result found.</h4>";
                }
            } else if ($user->get_type() == 'student') {
                echo "<h2>Active Subjects</h2>";
                if (executeForDisplay($stmt, isEnrolment: true) == false) {
                    echo "<h4>No search result found.</h4>";
                }
            } else {
                echo "<h2>Active Subjects</h2>";
                if (executeForDisplay($stmt, isTeaching: true) == false) {
                    echo "<h4>No search result found.</h4>";
                }
            }
        }
    } else {
        $search_input = $_POST['search_input'];
        $search_by = $_POST['search_by'];
        if ($user->get_type() == 'admin') {
            adminDisplayBy($conn, $search_input, $search_by);
        } else if ($user->get_type() == 'student') {
            echo "<h2>Active Subjects</h2>";
            if (displayBy($conn, $search_input, $search_by, isEnrolment: true) == false) {
                echo "<h4>No search result found.</h4>";
            }
        } else {
            echo "<h2>Active Subjects</h2>";
            if (displayBy($conn, $search_input, $search_by, isTeaching: true) == false) {
                echo "<h4>No search result found.</h4>";
            }
        }
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
    if (isset($_POST['teach'])) {
        teachSubject($conn, $user->get_sID(), $_SESSION['subjecttoChange']);
    }

    if ($user->get_type() == 'admin') {
        adminDisplayBy($conn, 'active', 'type');
    } else if ($user->get_type() == 'student') {
        displayBy($conn, 'active', 'type', isEnrolment: true); // Enable Enroll button for student by isEnrolment = true as a parameter for displayBy
    } else {
        displayBy($conn, 'active', 'type', isTeaching: true); // Enable Teach button for student by isTeaching = true as a parameter for displayBy

    }
}
// VIEW STUDENTS 
// if (isset($_POST['viewStudents'])) {
// }

// // Function to display students list
// function displayStudents($conn, $subject)
// {
//     $
//     $subject = mysqli_real_escape_string($conn, $subject); // sanitise to escape special characters before passing it to SQL query
//     $stmt = $conn->prepare("SELECT * from `osers`.`user` WHERE `code` = ? AND `type` = ?");
//     $stmt->bind_param('ss', $code, $subject);


// }


// Function to display enroll result message
function enrollSubject($conn, $sID, $subject)
{
    // echo "you click enroll !";
    $subject = mysqli_real_escape_string($conn, $subject); // sanitise to escape special characters before passing it to SQL query
    $query = "INSERT INTO `user-subject`(`sID`,`code`) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $sID, $subject);
    // var_dump(($stmt->execute()));
    if ($stmt->execute()) {
        echo "You are now enrolled in " . $subject . " Please refresh the page.";
    } else {
        echo "You are already enrolled in " . $subject;
    }
    echo "<br>";
}

// Function to display withdraw result message
function withdrawSubject($conn, $sID, $subject)
{
    // echo "im here at wS";
    $subject = mysqli_real_escape_string($conn, $subject); // sanitise to escape special characters before passing it to SQL query
    $query = "DELETE FROM `user-subject` WHERE `sID` = ? AND `code` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $sID, $subject);
    // var_dump(($stmt->execute()));
    $stmt->execute();
    echo "You have withdrawn from " . $subject . " Please refresh the page.";
    echo "<br>";

}
// Function to display teach result message
function teachSubject($conn, $sID, $subject)
{
    // echo "you click enroll !";
    $subject = mysqli_real_escape_string($conn, $subject); // sanitise to escape special characters before passing it to SQL query
    $query = "INSERT INTO `user-subject`(`sID`,`code`) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $sID, $subject);
    // var_dump(($stmt->execute()));
    if ($stmt->execute()) {
        echo "You are now a lecturer for " . $subject . " Please refresh the page.";
    } else {
        echo "You are already a lecturer for " . $subject;
    }
    echo "<br>";
}
// Function to display stop teaching result message
function notTeach($conn, $sID, $subject)
{
    // echo "im here at wS";
    $subject = mysqli_real_escape_string($conn, $subject); // sanitise to escape special characters before passing it to SQL query
    $query = "DELETE FROM `user-subject` WHERE `sID` = ? AND `code` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $sID, $subject);
    // var_dump(($stmt->execute()));
    $stmt->execute();
    echo "You have stopped teaching " . $subject . " Please refresh the page.";
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
function displayBy($conn, $search_input, $search_by, bool $isEnrolment = false, bool $isTeaching = false)
{
    $activeString = 'active';

    if ($search_by == 'code') {
        $stmt = $conn->prepare("SELECT * from `osers`.`subject` WHERE `code` = ? AND `type` = ?");
    } else if ($search_by == 'name') {
        $stmt = $conn->prepare("SELECT * from `osers`.`subject` WHERE `name` = ? AND `type` = ?");
    } else if ($search_by == 'lecturer') {
        $sIDStmt = $conn->prepare("SELECT `sID` FROM `osers`.`user` WHERE `name` = ? AND `type` = 'educator'");
        $sIDStmt->bind_param("s", $search_input);
        $sIDStmt->execute();
        $sIDResult = $sIDStmt->get_result();
        $sIDRow = mysqli_fetch_assoc($sIDResult);
        $sID = $sIDRow['sID'];
        $stmt = $conn->prepare("SELECT `subject`.* FROM `osers`.`subject` 
            JOIN `osers`.`user-subject` ON `subject`.`code` = `user-subject`.`code` 
            WHERE `user-subject`.`sID` = ? AND `subject`.`type` = ?");
        $search_input = $sID;
    } else if ($search_by == 'venue') {
        $stmt = $conn->prepare("SELECT * from `osers`.`subject` WHERE `venue` = ? AND `type` = ?");
    } else {
        $stmt = $conn->prepare("SELECT * from `osers`.`subject` WHERE `type` = ?");
        $stmt->bind_param("s", $activeString);
    }
    if ($search_by == 'type') {
        $stmt->bind_param("s", $activeString);
    } else {
        $stmt->bind_param("ss", $search_input, $activeString);
    }

    // $success = $stmt->execute();
    // if (!$success) {
    //     // If there was an error executing the statement, display the error message
    //     echo "Error executing statement: " . $stmt->error;
    //     return;
    // }
    // // $stmt->execute();
    // $result = $stmt->get_result();
    $result = executeStmtDisplay($stmt);

    return displaySubject($conn, $result, $isEnrolment, $isTeaching);
}

function executeStmtDisplay($stmt)
{
    $success = $stmt->execute();
    if (!$success) {
        // If there was an error executing the statement, display the error message
        echo "Error executing statement: " . $stmt->error;
        return;
    }
    // $stmt->execute();
    return $stmt->get_result();
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
        $sIDStmt = $conn->prepare("SELECT `sID` FROM `osers`.`user` WHERE `name` = ? AND `type` = 'educator'");
        $sIDStmt->bind_param("s", $search_input);
        $sIDStmt->execute();
        $sIDResult = $sIDStmt->get_result();
        $sIDRow = mysqli_fetch_assoc($sIDResult);
        $sID = $sIDRow['sID'];
        $stmt = $conn->prepare("SELECT `subject`.* FROM `osers`.`subject` 
            JOIN `osers`.`user-subject` ON `subject`.`code` = `user-subject`.`code` 
            WHERE `user-subject`.`sID` = ?");
        $search_input = $sID;
    } else {
        $stmt = $conn->prepare("SELECT * from `osers`.`subject` WHERE `venue` = ? ");
    }
    if (!$stmt) {
        // If there was an error preparing the statement, display the error message
        echo "Error preparing statement: " . $conn->error;
        return;
    }
    $success = $stmt->bind_param("s", $search_input);
    if (!$success) {
        // If there was an error binding the parameters, display the error message
        echo "Error binding parameters: " . $stmt->error;
        return;
    }
    $success = $stmt->execute();
    if (!$success) {
        // If there was an error executing the statement, display the error message
        echo "Error executing statement: " . $stmt->error;
        return;
    }

    $result = $stmt->get_result();
    displaySubject($conn, $result);

}

// Function to display the result of the SQL query in displayBy()
function displaySubject($conn, $result, bool $isEnrolment = false, bool $isTeaching = false)
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
        $venue = $row['venue'];
        $type = $row['type'];
        // Create a Subject object and call a method in Subject class
        $subject = new Subject($code, $name, $venue, $type);
        $subject->display();
        retrieveLecturer($conn, $code);
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
        } else if ($isTeaching && (unserialize($_SESSION['user'])->get_type() == 'educator')) {
            echo "<form action='subjects.php' method='POST'> <input type='hidden' name='subjectCode' value='$code'>
            <button type='submit' name='teach' value='Teach'>Start Teaching</button></form>";
        }
    }
    return $foundResult;
}

// Function to display Lecturer based on the user-subject database
function retrieveLecturer($conn, $search_input)
{
    echo "Lecturer: <br>";
    $stmt = $conn->prepare("SELECT * from `osers`.`user-subject` WHERE `code` = ? ");
    $stmt->bind_param("s", $search_input);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = mysqli_fetch_assoc($result)) {
        $sID = $row['sID'];
        displayLecturer($conn, $sID);
    }
}

function displayLecturer($conn, $sID)
{
    // echo "im here";
    $educatorString = 'educator';
    $stmt = $conn->prepare("SELECT * from `osers`.`user` WHERE `sID` = ? AND `type` = ? ");
    // echo "finish stmt";

    $stmt->bind_param("ss", $sID, $educatorString);
    // echo "bind";
    $stmt->execute();
    // echo "execute";
    $result = $stmt->get_result();
    while ($row = mysqli_fetch_assoc($result)) {
        $name = $row['name'];
        echo " | " . $name . " | ";
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