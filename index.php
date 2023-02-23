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
    // ini_set('display_errors', 1);
    // error_reporting(E_ALL ^ E_NOTICE);
    ?>
    <div class="titleContainer">
        <h1>Subject Enrolment System</h1>
    </div>
    <div class="pageContainer">
        <div class="loginpageContainer">
            <div class="column">
                <div class="head">
                    <h1 class="title"> Log In</h1><br>
                    <img class="logo" src="https://uowplaybook.s3-ap-southeast-2.amazonaws.com/logo/logo-primary.png"
                        alt="UOW Logo">
                </div>
                <form action='index.php' method='POST'>
                    <div class="loginBox">
                        <div class="inputContainer">
                            <input class="email" type="email" name="email" placeholder="Email*" required>
                            <input class="password" type="password" name="password" placeholder="Password*" required>
                        </div>
                        <div class="submitResetContainer">
                            <div class="submitResetItem resetContainer">
                                <button type='reset' name='reset' value='Reset'>Reset</button>
                            </div>
                            <div class="submitResetItem submitContainer">
                                <button type='submit' name='login' value='Log In'>Log In</button>
                            </div>
                        </div>
                    </div>

                    <div class="wrap">
                        <p class="reg-text">Not registered yet? <span><a href="register.php">Register Here</a></span>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>