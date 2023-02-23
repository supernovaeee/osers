<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Enrolment System</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    include('server.php');
    ?>
    <div class="titleContainer">
        <h1>Subject Enrolment System</h1>
    </div>
    <div class="pageContainer">
        <div class="column">
            <div class="head">
                <h1 class="title"> Register</h1><br>
            </div>
            <form action='register.php' method='POST'>
                <div class="wrap">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" placeholder="Enter name">
                </div>
                <div class="wrap">
                    <label for="sID">Student / Staff ID</label>
                    <input type="text" name="sID" id="sID" placeholder="Enter student or staff ID">
                </div>
                <div class="wrap">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter email">
                </div>
                <div class="wrap">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" id="phone" placeholder="Enter phone">
                </div>
                <div class="wrap">
                    <label for="type">Type</label>
                    <select name="type" id="type">
                        <option value="student">Student</option>
                        <option value="educator">Educator</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="wrap">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter password">
                </div>
                <div class="wrap">
                    <label for="password2">Confirm Password</label>
                    <input type="password" name="password2" id="password2" placeholder="Confirm password">
                </div>
                <div class="submitResetContainer">
                    <div class="submitResetItem resetContainer">
                        <button type='reset' name='reset' value='Reset'>Reset</button>
                    </div>
                    <div class="submitResetItem submitContainer">
                        <button type='submit' name='submit' value='Submit'>Submit</button>
                    </div>
                </div>
                <div class="wrap">
                    <p class="reg-text">Have an account? <span><a href="index.php">Log In</a></span></p>
                </div>
            </form>
        </div>
    </div>
</body>

</html>